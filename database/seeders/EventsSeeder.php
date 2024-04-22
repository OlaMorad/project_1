<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Assume we have event type IDs 1 and 2
        Event::create(['hall_id' => 1, 'event_type_id' => 1]);
        Event::create(['hall_id' => 2, 'event_type_id' => 2]);
    // Add more events as needed
    }
}
