<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Services\CatalogClient;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CatalogClient $catalog) {}

    public function index(Request $request)
    {
        $userId = $request->attributes->get('auth_user')['id'];

        return Cart::where('user_id', $userId)->get()->map(function (Cart $item) {
            $product = $this->catalog->findProduct($item->product_id);

            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'product' => $product,
            ];
        });
    }

    public function store(Request $request)
    {
        $data = $request->validate(['product_id' => 'required|integer']);
        $userId = $request->attributes->get('auth_user')['id'];

        $product = $this->catalog->findProduct($data['product_id']);

        if (! $product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        if ($product['quantity'] <= 0) {
            return response()->json(['message' => 'This product is out of stock.'], 422);
        }

        $item = Cart::where('user_id', $userId)->where('product_id', $data['product_id'])->first();

        if ($item) {
            if ($item->quantity >= $product['quantity']) {
                return response()->json(['message' => 'Maximum stock reached for this product.'], 422);
            }
            $item->increment('quantity');
        } else {
            $item = Cart::create(['user_id' => $userId, 'product_id' => $data['product_id'], 'quantity' => 1]);
        }

        return response()->json($item, 201);
    }

    public function increment(Request $request, Cart $cart)
    {
        $this->authorizeOwner($request, $cart);

        $product = $this->catalog->findProduct($cart->product_id);

        if ($product && $cart->quantity < $product['quantity']) {
            $cart->increment('quantity');
        }

        return $cart;
    }

    public function decrement(Request $request, Cart $cart)
    {
        $this->authorizeOwner($request, $cart);

        if ($cart->quantity <= 1) {
            $cart->delete();

            return response()->noContent();
        }

        $cart->decrement('quantity');

        return $cart;
    }

    public function destroy(Request $request, Cart $cart)
    {
        $this->authorizeOwner($request, $cart);

        $cart->delete();

        return response()->noContent();
    }

    /** Cart items are looked up by ID alone (route model binding) — without this,
     *  any authenticated user could manipulate another user's cart by guessing IDs. */
    private function authorizeOwner(Request $request, Cart $cart): void
    {
        if ($cart->user_id !== $request->attributes->get('auth_user')['id']) {
            abort(404);
        }
    }
}
