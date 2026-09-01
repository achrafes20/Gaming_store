<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\ReviewProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Truncates first so this is safe to re-run (`php artisan db:seed`) —
     * demo data replacing demo data, not piling up duplicates every time.
     * FK-respecting order: reviews/photos before products, products before
     * categories.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ReviewProduct::truncate();
        ProductPhoto::truncate();
        Product::truncate();
        Categories::truncate();
        Schema::enableForeignKeyConstraints();

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            ReviewProductSeeder::class,
        ]);
    }
}
