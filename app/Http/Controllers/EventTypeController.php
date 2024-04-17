<?php

namespace App\Http\Controllers;

use App\Models\Event_Type;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    public function show_all_event_type()
    {
        $event_type = Event_Type::with('images')->get();
        // $event_type=Event_Type::all();
        // Modify the structure of the data
        $modified_event_types = $event_type->map(function ($event_type) {
            // Unset imageable_id and imageable_type from the images object
            unset($event_type->images->imageable_id, $event_type->images->imageable_type);
            return $event_type;
        });
        return response()->json(['data'=> $modified_event_types,'massege'=>'ok','stauts'=>200]);
    }
    public function show($id)
    {
        // Retrieve the event_type with its related image using eager loading
        $eventType = Event_Type::with('images')->find($id);

        // Check if the event_type exists
        if (!$eventType) {
            return response()->json(['message' => 'Event type not found'], 404);
        }

        // Return the event_type with its related image
        return response()->json(['event_type' => $eventType], 200);
    }
}
