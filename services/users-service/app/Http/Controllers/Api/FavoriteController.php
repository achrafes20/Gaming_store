<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->attributes->get('auth_user')['id'];

        return Favorite::where('user_id', $userId)->get();
    }

    public function toggle(Request $request, int $productId)
    {
        $userId = $request->attributes->get('auth_user')['id'];

        $favorite = Favorite::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();

            return ['favorited' => false];
        }

        Favorite::create(['user_id' => $userId, 'product_id' => $productId]);

        return ['favorited' => true];
    }
}
