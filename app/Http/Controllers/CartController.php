<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\orderdetails;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function cart(){
        $user_id=auth()->user()->id;
        $cartProducts=Cart::with('Product')->where ('user_id',$user_id)->get();//with('Product')->where ('user_id',$user_id)->get(); fait appelle au model cart


        return view('cart',['cartProducts'=>$cartProducts]);
    }

    public function Completeorder(){
        $user_id=auth()->user()->id;
        $cartProducts=Cart::with('Product')->where ('user_id',$user_id)->get();
    return view('Completeorder',['cartProducts'=>$cartProducts]);
}
public function StoreOrder(Request $request){
    $newOrder=new Order();
    $newOrder->name=$request->name;
    $newOrder->address=$request->address;
    $newOrder->email=$request->email;
    $newOrder->phone=$request->phone;
    $newOrder->note=$request->note;
    $user_id=auth()->user()->id;
    $newOrder->user_id=$user_id;
    $newOrder->save();
    $cartProducts=Cart::with('Product')->where('user_id',$user_id)->get();
    foreach($cartProducts as $item){
        $newOrderDetail=new orderdetails();
        $newOrderDetail->product_id=$item->product_id;
        $newOrderDetail->price=$item->Product->price;
        $newOrderDetail->quantity=$item->quantity;
        $newOrderDetail->order_id=$newOrder->id;
        $newOrderDetail->save();
}
Cart::where('user_id', $user_id)->delete();
session()->forget('discount');
return Redirect('/');
}

public function previousorder(Request $request){

    $user_id=auth()->user()->id;
    $result=Order::with('orderdetails')->where('user_id',$user_id)->get();

    return view('previousorder',['orders'=>$result]);
}

public function cart_increment($cartid){
    $cart=Cart::find($cartid);
    $cart->quantity++;
    $cart->save();
    return Redirect::back();
}
public function cart_decrement($cartid){
    $cart=Cart::find($cartid);
    $cart->quantity--;
    $cart->save();
    return Redirect::back();
}
}

