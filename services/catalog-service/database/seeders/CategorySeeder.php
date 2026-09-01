<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds a small, curated set of gaming-gear categories with a real image
 * each — not a placeholder gray box — so a fresh `docker compose up` looks
 * like a working store, not an empty admin panel. Images live in
 * database/seeders/images/ (bundled into this service's own repo, not
 * fetched from anywhere at seed time — reused from the stock photos
 * already shipped in web-bff's assets, so nothing new to license/source).
 */
class CategorySeeder extends Seeder
{
    /** Category name => [seed image filename, description]. */
    public const CATEGORIES = [
        'Gaming Consoles' => ['gaming_console.jpg', 'Home consoles for every kind of player — from couch co-op to competitive online play.'],
        'Keyboards & Mice' => ['keyboard.jpg', 'Mechanical keyboards and precision mice built for fast, accurate input.'],
        'Audio & Headsets' => ['headphones.jpg', 'Headsets and audio gear for immersive sound and clear team comms.'],
        'Speakers' => ['speaker.jpg', 'Desktop and room-filling speakers for music, movies, and gaming.'],
        'Gaming Laptops' => ['laptop.jpg', 'Portable powerhouses with the GPUs to back up the specs.'],
        'Monitors & Displays' => ['tv.jpg', 'High-refresh-rate monitors and big-screen displays for every setup.'],
        'Cameras & Streaming Gear' => ['camera.jpg', 'Webcams and streaming equipment for content creators.'],
        'Wearables' => ['smartwatch.jpg', 'Smartwatches and fitness trackers that keep up with an active lifestyle.'],
    ];

    public function run(): void
    {
        $sourceDir = database_path('seeders/images');
        $uploadsDir = public_path('uploads');
        if (! is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        foreach (self::CATEGORIES as $name => [$image, $description]) {
            $destination = Str::uuid().'-'.$image;
            copy("{$sourceDir}/{$image}", "{$uploadsDir}/{$destination}");

            Categories::create([
                'name' => $name,
                'description' => $description,
                'imagepath' => "uploads/{$destination}",
            ]);
        }
    }
}
