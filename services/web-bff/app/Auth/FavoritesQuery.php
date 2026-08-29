<?php

namespace App\Auth;

use Illuminate\Support\Facades\Session;

/**
 * Mimics the tiny slice of Eloquent's query builder the Blade views use
 * (`->favorites()->where('product_id', $id)->exists()`), backed by the
 * favorite product IDs cached in session (see FavoriteController).
 */
class FavoritesQuery
{
    private ?int $productId = null;

    public function where(string $column, int $value): static
    {
        $this->productId = $value;

        return $this;
    }

    public function exists(): bool
    {
        return in_array($this->productId, Session::get('favorite_ids', []));
    }
}
