<?php

namespace App\Http\Controllers;

use App\Services\CatalogClient;
use App\Services\OrdersClient;
use App\Services\UsersClient;
use Illuminate\Http\Request;

class FirstController extends Controller
{
    public function MainPage(CatalogClient $catalog)
    {
        $categories = collect($catalog->categories()['body']);

        return view('welcome', ['categories' => $categories]);
    }

    public function Categories_page(CatalogClient $catalog)
    {
        $categories = collect($catalog->categories()['body']);
        $products = collect($catalog->products()['body']->data ?? []);

        return view('categories', ['categories' => $categories, 'products' => $products]);
    }

    public function Product_page(CatalogClient $catalog, $catid = null)
    {
        $query = $catid ? ['category_id' => $catid] : [];
        $products = collect($catalog->products($query)['body']->data ?? []);

        return view('product', ['products' => $products]);
    }

    public function search(Request $request, CatalogClient $catalog)
    {
        $products = collect($catalog->products(['q' => $request->searchkey])['body']->data ?? []);

        return view('product', ['products' => $products]);
    }

    public function reviews(UsersClient $users)
    {
        $reviews = collect($users->contactReviews()['body']);

        return view('reviews', ['reviews' => $reviews]);
    }

    public function storereview(Request $request, UsersClient $users)
    {
        $request->validate([
            'name' => ['required', 'max:100'],
            'phone' => 'required',
            'email' => 'required',
            'message' => 'required',
            'subject' => 'required',
        ]);

        $users->storeContactReview($request->only('name', 'phone', 'email', 'subject', 'message'));

        return back()->with('success', 'Review submitted successfully!');
    }

    public function RemoveReview($reviewid, UsersClient $users)
    {
        $users->deleteContactReview($reviewid);

        return back()->with('success', 'Review deleted successfully!');
    }

    public function sub(Request $request, UsersClient $users)
    {
        $request->validate(['email' => 'required|email']);

        $users->subscribe($request->email);

        return back()->with('success', 'Subscription successful!');
    }

    public function orders(OrdersClient $orders)
    {
        // Cross-service admin view: full order list lives in orders-service.
        $result = collect($orders->allOrders()['body']);

        return view('orders', ['orders' => $result]);
    }
}
