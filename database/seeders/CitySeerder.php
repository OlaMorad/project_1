<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeerder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed some cities
        $cities = [
            ['name' => 'Damascus'],
            ['name' => 'Latakia'],
            ['name' => 'Aleppo'],
            ['name' => 'Hama'],
            ['name' => 'Homs'],
            ['name' => 'Hasaka'],
            ['name' => 'DerAlZoor'],



            // Add more as needed
        ];
        // Define the paths to your images
        $imagePaths = [
            'https://c4.wallpaperflare.com/wallpaper/285/471/731/night-cyberpunk-futuristic-city-artwork-wallpaper-preview.jpg',
            'https://c4.wallpaperflare.com/wallpaper/953/495/816/anime-anime-girls-barefoot-bubbles-wallpaper-preview.jpg',
            'https://c0.wallpaperflare.com/preview/285/1009/176/concery-wallpaper-festival-party.jpg',
            'https://c4.wallpaperflare.com/wallpaper/222/981/89/candles-cake-cake-bokeh-wallpaper-preview.jpg',
            'https://c4.wallpaperflare.com/wallpaper/330/474/532/macro-music-music-blur-wallpaper-preview.jpg',
            'https://c4.wallpaperflare.com/wallpaper/832/200/979/life-concert-music-party-wallpaper-preview.jpg',
            'https://c0.wallpaperflare.com/preview/22/220/959/audio-audio-mixer-close-up-club.jpg',
            'https://c4.wallpaperflare.com/wallpaper/291/819/697/illustration-city-anime-painting-wallpaper-preview.jpg',
            // Add more image paths as needed
        ];
        // Loop through each event type and image path simultaneously
        foreach ($cities as $index => $city) {
            // Create the event type
            $city =City::create($city);
            // Create an image for the event type
            $image = new Image();
            $image->path = $imagePaths[$index]; // Use the corresponding image path
            $image->imageable_type = 'App\Models\City';
            $image->imageable_id = $city->id; // Use the ID of the created event type
            $image->save();

            // Associate the image with the event type
            $city->images()->save($image);
        }
    }
}
