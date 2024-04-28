<?php

namespace Database\Seeders;

use App\Models\Event_Type;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed some event types
        $eventTypes = [
            ['name' => 'Wedding'],
            ['name' => 'Graduation'],
            ['name' => 'Engagement'],
            ['name' => 'Birthday'],
            ['name' => 'Annual wedding Anniversary '],
            ['name' => 'Business dinner'],
         //   ['name' => 'Mother day'],
            ['name' => 'Valentine'],



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
            // Add more image paths as needed
        ];

        // Loop through each event type and image path simultaneously
        foreach ($eventTypes as $index => $eventType) {
            // Create the event type
            $event = Event_Type::create($eventType);
            // Create an image for the event type
            $image = new Image();
            $image->path = $imagePaths[$index]; // Use the corresponding image path
            $image->imageable_type = 'App\Models\Event_Type';
            $image->imageable_id = $event->id; // Use the ID of the created event type
            $image->save();

            // Associate the image with the event type
            $event->images()->save($image);
        }
    }
}
