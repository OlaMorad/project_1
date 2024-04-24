<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { // Manually declare city IDs, hall data, capacity, and price for each hall
        $hallsData = [
            [
                'name' => 'Hall A',
                'location' => 'Location A',
                'description' => 'Description of Hall A',
                'capacity' => 100, // Capacity of Hall A
                'city_id' => 1, // City ID 1
                'price' => 50.00, // Price per hour for Hall A
            ],
            [
                'name' => 'Hall B',
                'location' => 'Location B',
                'description' => 'Description of Hall B',
                'capacity' => 150, // Capacity of Hall B
                'city_id' => 1, // City ID 1
                'price' => 70.00, // Price per hour for Hall B
            ],
            [
                'name' => 'Hall C',
                'location' => 'Location C',
                'description' => 'Description of Hall C',
                'capacity' => 120, // Capacity of Hall C
                'city_id' => 2, // City ID 2
                'price' => 60.00, // Price per hour for Hall C
            ],
            [
                'name' => 'Hall D',
                'location' => 'Location D',
                'description' => 'Description of Hall D',
                'capacity' => 130, // Capacity of Hall D
                'city_id' => 2, // City ID 2
                'price' => 55.00, // Price per hour for Hall D
            ],
            [
                'name' => 'Hall E',
                'location' => 'Location E',
                'description' => 'Description of Hall E',
                'capacity' => 110, // Capacity of Hall E
                'city_id' => 3, // City ID 3
                'price' => 45.00, // Price per hour for Hall E
            ],
            [
                'name' => 'Hall F',
                'location' => 'Location F',
                'description' => 'Description of Hall F',
                'capacity' => 140, // Capacity of Hall F
                'city_id' => 3, // City ID 3
                'price' => 65.00, // Price per hour for Hall F
            ],
            [
                'name' => 'Hall G',
                'location' => 'Location G',
                'description' => 'Description of Hall G',
                'capacity' => 160, // Capacity of Hall G
                'city_id' => 4, // City ID 4
                'price' => 75.00, // Price per hour for Hall G
            ],
            [
                'name' => 'Hall H',
                'location' => 'Location H',
                'description' => 'Description of Hall H',
                'capacity' => 170, // Capacity of Hall H
                'city_id' => 4, // City ID 4
                'price' => 80.00, // Price per hour for Hall H
            ],
            [
                'name' => 'Hall I',
                'location' => 'Location I',
                'description' => 'Description of Hall I',
                'capacity' => 180, // Capacity of Hall I
                'city_id' => 5, // City ID 5
                'price' => 90.00, // Price per hour for Hall I
            ],
            [
                'name' => 'Hall J',
                'location' => 'Location J',
                'description' => 'Description of Hall J',
                'capacity' => 200, // Capacity of Hall J
                'city_id' => 5, // City ID 5
                'price' => 100.00, // Price per hour for Hall J
            ],
            [
                'name' => 'Hall K',
                'location' => 'Location K',
                'description' => 'Description of Hall K',
                'capacity' => 120, // Capacity of Hall K
                'city_id' => 6, // City ID 6
                'price' => 55.00, // Price per hour for Hall K
            ],
            [
                'name' => 'Hall L',
                'location' => 'Location L',
                'description' => 'Description of Hall L',
                'capacity' => 130, // Capacity of Hall L
                'city_id' => 6, // City ID 6
                'price' => 60.00, // Price per hour for Hall L
            ],
            [
                'name' => 'Hall M',
                'location' => 'Location M',
                'description' => 'Description of Hall M',
                'capacity' => 140, // Capacity of Hall M
                'city_id' => 7, // City ID 7
                'price' => 65.00, // Price per hour for Hall M
            ],
            [
                'name' => 'Hall N',
                'location' => 'Location N',
                'description' => 'Description of Hall N',
                'capacity' => 150, // Capacity of Hall N
                'city_id' => 7, // City ID 7
                'price' => 70.00, // Price per hour for Hall N
            ],
            
            
            // Add more halls for the remaining cities
        ];
        // Define image paths for each hall
        $imagePaths = [
            'https://c4.wallpaperflare.com/wallpaper/285/471/731/night-cyberpunk-futuristic-city-artwork-wallpaper-preview.jpg',
            'https://c4.wallpaperflare.com/wallpaper/953/495/816/anime-anime-girls-barefoot-bubbles-wallpaper-preview.jpg',
            'https://c0.wallpaperflare.com/preview/285/1009/176/concery-wallpaper-festival-party.jpg',
            'https://c4.wallpaperflare.com/wallpaper/222/981/89/candles-cake-cake-bokeh-wallpaper-preview.jpg',
            'https://c4.wallpaperflare.com/wallpaper/330/474/532/macro-music-music-blur-wallpaper-preview.jpg',
            'https://c4.wallpaperflare.com/wallpaper/832/200/979/life-concert-music-party-wallpaper-preview.jpg',
            'https://c0.wallpaperflare.com/preview/22/220/959/audio-audio-mixer-close-up-club.jpg',
            'https://c4.wallpaperflare.com/wallpaper/291/819/697/illustration-city-anime-painting-wallpaper-preview.jpg',
            'https://c1.wallpaperflare.com/preview/557/365/273/antwerp-station-hall-building-station.jpg',
            'https://c4.wallpaperflare.com/wallpaper/500/455/679/architecture-modern-azerbaijan-town-square-wallpaper-preview.jpg',
            'https://c1.wallpaperflare.com/preview/888/459/609/governor-s-mansion-montgomery-alabama-grand-staircase.jpg',
            'https://c4.wallpaperflare.com/wallpaper/76/834/475/kolkata-victoria-memorial-hall-wallpaper-preview.jpg',
            'https://c4.wallpaperflare.com/wallpaper/328/804/963/building-tourist-attraction-baroque-architecture-religious-institute-wallpaper-preview.jpg',
            'https://c4.wallpaperflare.com/wallpaper/772/298/406/palace-of-versailles-hall-of-mirrors-hd-wallpaper-wallpaper-preview.jpg',
            // Add image paths here
        ];
        // Loop through each hall data and image path simultaneously
        foreach ($hallsData as $index => $hallData) {
            // Create the hall
            
            $hall = Hall::create($hallData);
          //  $totalHalls = $hall->count();
            // Create an image for the hall
            $image = new Image();
            $image->path = $imagePaths[$index]; // Use the corresponding image path
            $image->imageable_type = 'App\Models\Hall';
            $image->imageable_id = $hall->id; // Use the ID of the created hall
            $image->save();

            // Associate the image with the hall
            $hall->images()->save($image);
        }
}
}
