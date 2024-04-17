<?php

namespace Database\Seeders;

use App\Models\Event_Type;
use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $photo = Event_Type::find(1);
        $image = new Image();
        $image->path = 'images\sunflower-1127174_1280.jpg';
        $image->imageable_type ='App\Models\Event_Type';
        $image->imageable_id = 1;
        $image->save();

        $photo->images()->save($image);
   

    }
}
