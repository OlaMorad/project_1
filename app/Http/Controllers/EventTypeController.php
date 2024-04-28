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
                // Modify each city's images to include the asset() function
                $event_type->images->each(function ($image) {
                    $image->path = asset($image->path);
                });
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
            //  $eventTypes = Event::where('event_type_id', $event_type_id)->with('halls')->get();
            $eventType = Event_Type::findOrFail($event_type_id);
            // Retrieve the event type by ID
            //    $event_type = Event_type::findOrFail($event_type_id);

            // Eager load the halls associated with the event type
            // $halls = $event_type->halls;
            $halls = $eventType->halls()->with('images')->get();


            if ($halls->isEmpty()) {
                return response()->json(['message' => 'There are no halls for this event type', 'status' => 200]);
            }
            //  $halls = collect();
            //foreach ($eventTypes as $eventType) {
            // Add the event type to the collection
            //   $halls->push($eventType);
            // $halls = array_merge([$eventType, $eventType->halls]);
            // Add the halls associated with the event type to the collection
            // $halls = $halls->merge($eventType->halls);

            //  }

            // Filter out null values from the collection
            //  $halls = $halls->filter();

            // Transform the halls data to remove pivot and imageable_type/imageable_id
            $transformedHalls = $halls->map(function ($hall) {
                // Remove the pivot field
                unset($hall->pivot);

                // Remove imageable_type and imageable_id from images
                $hall->images()->get();
                unset($hall->images->imageable_type);
                unset($hall->images->imageable_id);
                unset($hall->deleted_at);


                return $hall;
            });


            // Return the halls as JSON response
            return response()->json(['data' => $transformedHalls, 'message' => 'ok', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }
}
