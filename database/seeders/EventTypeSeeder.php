<?php

namespace Database\Seeders;

use App\Models\Event_Type;
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
            ['name' => 'marriage'],
            ['name' => 'graduation'],
            ['name' => 'engagement'],
            ['name' => 'birthday'],

            // Add more as needed
        ];

        // Insert data into database
        foreach ($eventTypes as $eventType) {
            Event_Type::create($eventType);
        }
    }
}
