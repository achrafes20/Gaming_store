<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\orderdetails;
use App\Models\Product;
use App\Models\Payments;
use App\Mail\OrderConfirmedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cart()
    {
        $user_id = Auth::id();
        $cartProducts = Cart::with('Product')->where('user_id', $user_id)->get();
        return view('cart', ['cartProducts' => $cartProducts]);
    }

    public function Completeorder()
    {
        $user_id = Auth::id();
        $cartProducts = Cart::with('Product')->where('user_id', $user_id)->get();
        return view('Completeorder', ['cartProducts' => $cartProducts]);
    }
    public function StoreOrder(Request $request)
    {
        $rules = [
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'region'  => 'required|string',
            'city'    => 'required|string',
            'phone'   => 'required|digits:10',
        ];
        if ($request->payment_method === 'card') {
            $rules = array_merge($rules, [
                'card_number' => 'required|digits_between:13,19',
                'expiry_date' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
                'cvv'         => 'required|digits_between:3,4',
                'card_name'   => 'required|string|max:255',
            ]);
        }
        $request->validate($rules);
        $cartProducts = Cart::where('user_id', Auth::id())->with('product')->get();
        $somme = $cartProducts->sum(fn($item) => $item->product->price * $item->quantity);
        $newOrder = new Order();
        $newOrder->name = $request->name;
        $newOrder->address = $request->address;
        $newOrder->region = $request->region;
        $newOrder->city = $request->city;
        $newOrder->email = $request->email;
        $newOrder->phone = $request->phone;
        $newOrder->note = $request->note;
        $user_id = Auth::id();
        $newOrder->user_id = $user_id;
        $newOrder->discount = session('coupon.discount', 0);
        $newOrder->total = $somme;
        $newOrder->save();
        foreach ($cartProducts as $item) {
            $newOrderDetail = new OrderDetails();
            $newOrderDetail->product_id = $item->product_id;
            $newOrderDetail->price = $item->Product->price;
            $newOrderDetail->quantity = $item->quantity;
            $newOrderDetail->order_id = $newOrder->id;

            $pr = Product::find($newOrderDetail->product_id);
            if ($pr) {
                $pr->quantity -= $newOrderDetail->quantity;
                $pr->save();
            }

            $newOrderDetail->save();
        }
        Cart::where('user_id', $user_id)->delete();
        $order = Order::findOrFail($newOrder->id);
        if ($request->payment_method === 'card') {
            Payments::create([
                'user_id'     => Auth::id(),
                'order_id'    => $newOrder->id,
                'card_number' => $request->card_number,
                'expiry_date' => $request->expiry_date,
                'cvv'         => $request->cvv,
                'card_name'   => $request->card_name,
                'status'      => 'success',
            ]);
            $newOrder->status = 'paid';
            $newOrder->save();
        }
        session()->forget('coupon');
        Mail::to($newOrder->email)->send(new OrderConfirmedMail($newOrder, $somme));
        return view('order-confirmation', [
            'orderId' => $newOrder->id,
            'order' => $newOrder,
            'sommeOrder' => $somme
        ]);
    }
    public function previousorder(Request $request)
    {
        $user_id = Auth::id();
        $result = Order::with('orderdetails')->where('user_id', $user_id)->get();
        return view('previousorder', ['orders' => $result]);
    }
    public function cart_increment($cartid)
    {
        $cart = Cart::findOrFail($cartid);
        $maxStock = $cart->product->quantity;
        if ($cart->quantity < $maxStock) {
            $cart->quantity++;
            $cart->save();
        }
        return back();
    }
    public function cart_decrement($cartid)
    {
        $cart = Cart::find($cartid);
        $cart->quantity--;
        $cart->save();
        return back();
    }
    public function addproducttocart($productid)
    {
        $user_id = Auth::id();
        $product = Product::find($productid);
        if (!$product) {
            return redirect()->back()->withErrors(['Product not found.']);
        }
        if ($product->quantity <= 0) {
            return redirect()->back()->withErrors(['This product is out of stock.']);
        }

        $result = Cart::where('user_id', $user_id)
            ->where('product_id', $productid)
            ->first();
        if ($result) {
            if ($result->quantity < $product->quantity) {
                $result->quantity++;
                $result->save();
            } else {
                return redirect()->back()->withErrors(['Maximum stock reached for this product.']);
            }
        } else {
            $newCart = new Cart();
            $newCart->product_id = $productid;
            $newCart->user_id = $user_id;
            $newCart->quantity = 1;
            $newCart->save();
        }
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function deletecartitem($cartid)
    {
        Cart::find($cartid)->delete();
        return redirect('/cart');
    }
}
