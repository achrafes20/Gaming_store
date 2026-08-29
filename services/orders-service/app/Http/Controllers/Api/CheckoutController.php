<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Payments;
use App\Services\CatalogClient;
use App\Services\EventPublisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CheckoutController extends Controller
{
    public function __construct(
        private CatalogClient $catalog,
        private EventPublisher $events,
    ) {}

    public function index(Request $request)
    {
        $userId = $request->attributes->get('auth_user')['id'];

        return Order::with('orderDetails', 'payment')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    /** Admin-only: every order across all users (back-office order list). */
    public function all()
    {
        return Order::with('orderDetails', 'payment')->latest()->get();
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'region' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|digits:10',
            'email' => 'required|email',
            'note' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'payment_method' => 'required|in:card,cod',
        ];

        if ($request->payment_method === 'card') {
            $rules = array_merge($rules, [
                'card_number' => 'required|digits_between:13,19',
                'expiry_date' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
                'cvv' => 'required|digits_between:3,4',
                'card_name' => 'required|string|max:255',
            ]);
        }

        $data = $request->validate($rules);
        $user = $request->attributes->get('auth_user');

        $cartItems = Cart::where('user_id', $user['id'])->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 422);
        }

        // Prix/stock relus depuis catalog-service (source de vérité), jamais depuis le panier local.
        $lines = $cartItems->map(function (Cart $item) {
            $product = $this->catalog->findProduct($item->product_id);

            if (! $product || $product['quantity'] < $item->quantity) {
                throw new RuntimeException("Product {$item->product_id} unavailable or insufficient stock.");
            }

            return [
                'product_id' => $item->product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $item->quantity,
            ];
        });

        $total = $lines->sum(fn ($l) => $l['price'] * $l['quantity']);

        $discount = 0;
        $coupon = null;
        if (! empty($data['coupon_code'])) {
            $coupon = Coupon::where('code', $data['coupon_code'])->first();
            if ($coupon && $coupon->isValid() && ! $coupon->usedBy($user['id'])) {
                $discount = $coupon->calculateDiscount($total);
            }
        }

        try {
            $order = DB::transaction(function () use ($data, $user, $lines, $total, $discount, $coupon) {
                $order = Order::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'region' => $data['region'],
                    'city' => $data['city'],
                    'phone' => $data['phone'],
                    'note' => $data['note'] ?? null,
                    'user_id' => $user['id'],
                    'discount' => $discount,
                    'total' => $total,
                    'status' => $data['payment_method'] === 'card' ? 'paid' : 'pending',
                ]);

                foreach ($lines as $line) {
                    OrderDetails::create([...$line, 'order_id' => $order->id]);
                    $this->catalog->decrementStock($line['product_id'], $line['quantity']);
                }

                if ($data['payment_method'] === 'card') {
                    Payments::create([
                        'user_id' => $user['id'],
                        'order_id' => $order->id,
                        'card_number' => substr($data['card_number'], -4),
                        'expiry_date' => $data['expiry_date'],
                        'card_name' => $data['card_name'],
                        'status' => 'success',
                    ]);
                }

                if ($coupon) {
                    $coupon->markUsedBy($user['id']);
                }

                Cart::where('user_id', $user['id'])->delete();

                return $order;
            });
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

        $this->events->publish('order.created', [
            'order_id' => $order->id,
            'user_id' => $user['id'],
            'email' => $order->email,
            'name' => $order->name,
            'total' => (float) $order->total,
            'discount' => (float) $order->discount,
            'items' => $lines->toArray(),
        ]);

        return response()->json($order->load('orderDetails'), 201);
    }

    /** Called by catalog-service before accepting a product review. */
    public function hasPurchased(Request $request)
    {
        $data = $request->validate(['user_id' => 'required|integer', 'product_id' => 'required|integer']);

        $hasPurchased = OrderDetails::whereHas('order', function ($q) use ($data) {
            $q->where('user_id', $data['user_id']);
        })->where('product_id', $data['product_id'])->exists();

        return ['has_purchased' => $hasPurchased];
    }
}
