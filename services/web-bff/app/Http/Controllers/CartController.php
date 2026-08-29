<?php

namespace App\Http\Controllers;

use App\Services\OrdersClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function cart(OrdersClient $orders)
    {
        $cartProducts = collect($orders->cart()['body']);

        return view('cart', ['cartProducts' => $cartProducts]);
    }

    public function Completeorder(OrdersClient $orders)
    {
        $cartProducts = collect($orders->cart()['body']);

        return view('Completeorder', ['cartProducts' => $cartProducts]);
    }

    public function StoreOrder(Request $request, OrdersClient $orders)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'region' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|digits:10',
        ];

        if ($request->payment_method === 'card') {
            $rules = array_merge($rules, [
                'card_number' => 'required|digits_between:13,19',
                'expiry_date' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
                'cvv' => 'required|digits_between:3,4',
                'card_name' => 'required|string|max:255',
            ]);
        }

        $request->validate($rules);

        $data = $request->only(
            'name', 'address', 'region', 'city', 'phone', 'email', 'note',
            'payment_method', 'card_number', 'expiry_date', 'cvv', 'card_name'
        );
        $data['coupon_code'] = Session::get('coupon.code');

        $result = $orders->checkout($data);

        if ($result['status'] !== 201) {
            return back()->withErrors(['checkout' => $result['body']->message ?? 'Checkout failed.']);
        }

        Session::forget('coupon');
        $order = $result['body'];

        return view('order-confirmation', [
            'orderId' => $order->id,
            'order' => $order,
            'sommeOrder' => $order->total,
        ]);
    }

    public function previousorder(OrdersClient $orders)
    {
        $result = collect($orders->orders()['body']);

        return view('previousorder', ['orders' => $result]);
    }

    public function cart_increment($cartid, OrdersClient $orders)
    {
        $orders->incrementCart($cartid);

        return back();
    }

    public function cart_decrement($cartid, OrdersClient $orders)
    {
        $orders->decrementCart($cartid);

        return back();
    }

    public function addproducttocart($productid, OrdersClient $orders)
    {
        $result = $orders->addToCart($productid);

        if (! in_array($result['status'], [200, 201])) {
            return redirect()->back()->withErrors([$result['body']->message ?? 'Could not add product to cart.']);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function deletecartitem($cartid, OrdersClient $orders)
    {
        $orders->removeFromCart($cartid);

        return redirect('/cart');
    }
}
