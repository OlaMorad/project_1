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
            'images/photo_1.jpg',
            'images/photo_2.jpg',
            'images/photo_3.jpg',
            'images/photo_4.jpg',
            'images/photo_5.jpg',
            'images/photo_6.jpg',
            'images/photo_7.jpg',
            'images/photo_8.jpg',



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
        //     $image->save();

        //     // Associate the image with the event type
             $city->images()->save($image);
        }
    }
}
