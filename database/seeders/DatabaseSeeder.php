<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Review;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
    ['id' => 1, 'name' => 'Iphone', 'description' => 'Smartphones Apple performants et élégants.', 'imagepath' => 'assets\img\categories\iphone.jpg'],
    ['id' => 2, 'name' => 'Camera', 'description' => 'Appareils photo haute résolution pour amateurs et professionnels.', 'imagepath' => 'assets\img\categories\camera.jpg'],
    ['id' => 3, 'name' => 'Laptop', 'description' => 'Ordinateurs portables puissants pour le travail et le divertissement.', 'imagepath' => 'assets\img\categories\laptop.jpg'],
    ['id' => 4, 'name' => 'Headphones', 'description' => 'Casques audio sans fil avec réduction de bruit.', 'imagepath' => 'assets\img\categories\headphones.jpg'],
    ['id' => 5, 'name' => 'Smartwatch', 'description' => 'Montres connectées avec suivi de santé et notifications.', 'imagepath' => 'assets\img\categories\smartwatch.jpg'],
    ['id' => 6, 'name' => 'Tablet', 'description' => 'Tablettes légères idéales pour lire, naviguer ou regarder des vidéos.', 'imagepath' => 'assets\img\categories\tablet.jpg'],
    ['id' => 7, 'name' => 'Gaming Console', 'description' => 'Consoles de jeux dernière génération pour une expérience immersive.', 'imagepath' => 'assets\img\categories\gaming_console.jpg'],
    ['id' => 8, 'name' => 'TV', 'description' => 'Téléviseurs 4K UHD avec écran large et son Dolby.', 'imagepath' => 'assets\img\categories\tv.jpg'],
    ['id' => 9, 'name' => 'Drone', 'description' => 'Drones avec caméra pour prises de vues aériennes.', 'imagepath' => 'assets\img\categories\drone.jpg'],
    ['id' => 10, 'name' => 'Speaker', 'description' => 'Enceintes Bluetooth portables avec son clair et puissant.', 'imagepath' => 'assets\img\categories\speaker.jpg']
];

    DB::table('categories')->insertOrIgnore($categories);//il va l ajouter si n existe pas seulement !


for($i=1; $i<=25; $i++){
        Product::create([
            'name' => 'Product'.$i,
            'description'=>'This is product number'.$i,
            'price' => rand(100, 100),
            'quantity'=>rand(1, 50),
            'imagepath' => '',
            'category_id' => rand(1,10),
        ]);
    }

    for($i=1; $i<=3; $i++){
        Review::create([
            'name' => 'Review'.$i,
            'phone'=>'number'.$i,
            'email' => "test@gmail.com",
            'subject'=>rand(1, 50),
            'message' => '',

        ]);
    }
}


}
