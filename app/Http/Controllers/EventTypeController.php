<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Event_Type;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventTypeController extends Controller
{
    public function show_all_event_type()
    {
        try {
            $event_type = Event_Type::with('images')->get();
            // $event_type=Event_Type::all();
            // Modify the structure of the data
            $modified_event_types = $event_type->map(function ($event_type) {
                // Unset imageable_id and imageable_type from the images object
                unset($event_type->images->imageable_id, $event_type->images->imageable_type);
                 return $event_type;
            });
            return response()->json(['data' => $modified_event_types, 'massege' => 'ok', 'stauts' => 200]);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
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

    public function halls($event_type_id)
    {
        try {
            // Retrieve the event type by ID
            // $eventType = DB::table('events')->where('event_type_id', $event_type_id)->get();
            // $eventTypes = Event_Type::where('id', strtolower($event_type_id->event_type_id))->first();
            $eventTypes = Event::where('event_type_id', $event_type_id)->get();
            //  $eventType = Event_Type::findOrFail($event_type_id);
            if ($eventTypes->isEmpty()) {
                return response()->json(['message' => 'there is no halls in this event_type', 'status' => 200]);
            }
            $halls = collect();
            foreach ($eventTypes as $eventType) {
                // Add the event type to the collection
                $halls->push($eventType);
                // $halls = array_merge([$eventType, $eventType->halls]);
                // Add the halls associated with the event type to the collection
                // $halls = $halls->merge($eventType->halls);

            }

            // Filter out null values from the collection
            $halls = $halls->filter();

            // Return the halls as JSON response
            return response()->json(['data' => $halls, 'message' => 'ok', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }
}
