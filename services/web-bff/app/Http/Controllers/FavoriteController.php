<?php

namespace App\Http\Controllers;

use App\Services\CatalogClient;
use App\Services\UsersClient;
use Illuminate\Support\Facades\Session;

class FavoriteController extends Controller
{
    public function index(UsersClient $users, CatalogClient $catalog)
    {
        $favorites = collect($users->favorites()['body'])->map(function ($fav) use ($catalog) {
            $product = $catalog->product($fav->product_id)['body'];

            return (object) ['id' => $fav->id, 'product' => $product];
        });

        return view('favorites', ['favorites' => $favorites]);
    }

    public function toggle($productId, UsersClient $users)
    {
        $this->applyToggle($productId, $users);

        return back()->with('success', 'Favorites updated!');
    }

    public function store($productId, UsersClient $users)
    {
        $this->applyToggle($productId, $users);

        return back()->with('success', 'Product added to favorites!');
    }

    public function destroy($productId, UsersClient $users)
    {
        $this->applyToggle($productId, $users);

        return back()->with('success', 'Product removed from favorites.');
    }

    private function applyToggle($productId, UsersClient $users): void
    {
        $result = $users->toggleFavorite($productId)['body'];
        $ids = Session::get('favorite_ids', []);

        Session::put('favorite_ids', $result->favorited
            ? array_unique([...$ids, (int) $productId])
            : array_values(array_diff($ids, [(int) $productId])));
    }
}
