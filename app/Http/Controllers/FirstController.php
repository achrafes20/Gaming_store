<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categories;
use App\Models\Review;

class FirstController extends Controller
{
    public function MainPage()
    {
        //Session::put('date','18-08-2004');
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
            'name' => ['required',  'max:100'], //unique:products dans le meme nom dans la DB
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
        return redirect('/');
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
            $result = Product::all();//::paginate(6);//afficher seulement 6 dans une page
            //$result=DB::table("products")->get();
            return view('product', ["products" => $result]);
        } else {
            $result = Product::where("category_id", $catid)->get(); //$result=DB::table("products")->where ("category_id",$catid)->get();
            return view('product', ["products" => $result]);
        }
    }
}
