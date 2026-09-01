<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Review;
use App\Models\Sub;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Truncates first so this is safe to re-run — see
     * catalog-service's DatabaseSeeder for the same reasoning.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Favorite::truncate();
        Review::truncate();
        Sub::truncate();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        $this->call([
            UserSeeder::class,
            FavoriteSeeder::class,
            SubSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
