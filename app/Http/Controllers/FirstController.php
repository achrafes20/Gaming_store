<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categories;
use App\Models\Review;
use App\Models\Order;
use App\Models\Sub;

class FirstController extends Controller
{
    public function MainPage()
    {
        session()->forget('date');
        $result = Categories::all();
        return view('welcome', ["categories" => $result]);
    }
    public function reviews()
    {
        $result = Review::all();
        return view('reviews', ["reviews" => $result]);
    }
    public function storereview(Request $request)
    {
        $request->validate([
            'name' => ['required',  'max:100'],
            'phone' => 'required',
            'email' => 'required',
            'message' => 'required',
            'subject' => 'required'
        ]);
        $newReview = new Review();
        $newReview->name = $request->name;
        $newReview->phone = $request->phone;
        $newReview->email = $request->email;
        $newReview->subject = $request->subject;
        $newReview->message = $request->message;
        $newReview->save();
        return redirect()->back()->with('success', 'Review submitted successfully!');
    }
    public function Categories_page()
    {
        $result = Categories::all();
        $result2 = Product::all();
        return view('categories', ["categories" => $result], ["products" => $result2]);
    }
    public function Product_page($catid = null)
    {
        if (!$catid) {
            $result = Product::all();
            return view('product', ["products" => $result]);
        } else {
            $result = Product::where("category_id", $catid)->get();
            return view('product', ["products" => $result]);
        }
    }
    public function orders()
    {
        $orders = Order::all();
        return view('orders', ['orders' => $orders]);
    }
    public function sub(Request $request)
    {
        $request->validate([
            'email' => 'required'

        ]);
        $newsub = new Sub();
        $newsub->email = $request->email;
        $newsub->save();
        return redirect()->back()->with('success', 'Subscription successful!');
    }

    public function search(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->searchkey . '%')->get();
        return view('product', ['products' => $products]);
    }
}
