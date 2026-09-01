<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Database\Seeder;

/**
 * ~26 real, recognizable gaming-gear products across CategorySeeder's
 * categories — enough for the catalog, search, and filter-by-category
 * views to all have something real to show. Each product reuses its
 * category's seed photo rather than getting its own (a deliberate
 * shortcut: distinct per-product photography for 26 items isn't worth
 * sourcing for a demo dataset, and every category still looks visually
 * distinct from every other one).
 */
class ProductSeeder extends Seeder
{
    /** Category name => [[name, description, price, quantity], ...]. */
    public const PRODUCTS = [
        'Gaming Consoles' => [
            ['PlayStation 5', "Sony's flagship console — ray tracing, ultra-fast SSD loading, and a growing exclusives library.", 499, 12],
            ['Xbox Series X', 'The most powerful Xbox yet, with Quick Resume and Game Pass built in.', 479, 9],
            ['Nintendo Switch OLED', 'A vivid OLED screen and the same handheld-or-docked flexibility Switch is known for.', 349, 20],
        ],
        'Keyboards & Mice' => [
            ['Razer BlackWidow V4', 'Full-size mechanical keyboard with hot-swappable switches and per-key RGB.', 169, 25],
            ['Logitech G Pro X Keyboard', 'Tenkeyless tournament-grade keyboard trusted by esports pros.', 149, 18],
            ['Razer DeathAdder V3', 'Ergonomic esports mouse with a 30,000 DPI optical sensor.', 89, 40],
            ['Logitech G502 HERO', 'Adjustable-weight gaming mouse with 11 programmable buttons.', 59, 35],
        ],
        'Audio & Headsets' => [
            ['SteelSeries Arctis Nova Pro', 'Flagship wireless headset with active noise cancellation and hot-swappable batteries.', 349, 14],
            ['HyperX Cloud II', 'Legendary comfort and 7.1 virtual surround sound at a mid-range price.', 99, 30],
            ['Razer BlackShark V2', 'Esports headset tuned for footstep clarity and positional accuracy.', 99, 22],
        ],
        'Speakers' => [
            ['Logitech Z625', '2.1 THX-certified speaker system with a powerful subwoofer.', 179, 10],
            ['JBL Quantum Duo', 'Compact desktop speakers with RGB lighting and Bluetooth.', 149, 12],
            ['Razer Nommo Pro', 'Dolby Atmos desktop speakers with a wireless subwoofer.', 499, 5],
        ],
        'Gaming Laptops' => [
            ['ASUS ROG Strix G16', '16-inch 240Hz display paired with a latest-gen GPU.', 1599, 8],
            ['Razer Blade 15', "An aluminum unibody gaming laptop that doesn't look like a gaming laptop.", 2199, 4],
            ['Lenovo Legion 7', 'High-refresh QHD display with serious thermal headroom.', 1799, 6],
        ],
        'Monitors & Displays' => [
            ['LG UltraGear 27GP950', '27" 4K Nano IPS monitor at 144Hz with G-SYNC compatibility.', 799, 9],
            ['Samsung Odyssey G9', 'An ultra-wide curved monitor that replaces a dual-monitor setup.', 1299, 5],
            ['ASUS ROG Swift PG279QM', '27" QHD 240Hz esports-grade monitor.', 849, 7],
        ],
        'Cameras & Streaming Gear' => [
            ['Logitech StreamCam', 'Vertical or horizontal 1080p60 webcam built for content creators.', 169, 15],
            ['Elgato Facecam', 'True 1080p60 webcam with manual controls and no fisheye distortion.', 199, 10],
            ['Razer Kiyo Pro', 'An adaptive light sensor webcam that performs in any lighting.', 179, 11],
        ],
        'Wearables' => [
            ['Garmin Instinct 2', 'A rugged GPS smartwatch built for outdoor durability.', 299, 13],
            ['Samsung Galaxy Watch6', 'A sleek Wear OS smartwatch with detailed health tracking.', 329, 16],
            ['Apple Watch Ultra', "Apple's most rugged, capable watch, with the longest battery life yet.", 799, 6],
        ],
    ];

    public function run(): void
    {
        $categories = Categories::all()->keyBy('name');

        foreach (self::PRODUCTS as $categoryName => $products) {
            $category = $categories[$categoryName] ?? null;
            if (! $category) {
                continue;
            }

            foreach ($products as [$name, $description, $price, $quantity]) {
                Product::create([
                    'name' => $name,
                    'description' => $description,
                    'imagepath' => $category->imagepath,
                    'quantity' => $quantity,
                    'price' => $price,
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
