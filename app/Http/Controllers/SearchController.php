<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Event;
use App\Models\Event_Type;
use Illuminate\Http\Request;
use App\Models\Hall;
use Exception;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $halls = [];

            $halls['name'] = Hall::where('name', 'like', '%' . $request->name . '%')->with('images')->get();
            $halls['location'] = Hall::where('location', 'like', '%' . $request->location . '%')->with('images')->get();

            $halls['city'] = Hall::whereHas('city', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->city_name . '%');
            })->with('images')->get();
            // results
            $results = [];
            $results['halls'] = $halls;

            $results = Hall::query()
                ->when($request->name, function ($query, $name) {
                    $query->where('name', 'like', '%' . $name . '%');
                })
                ->when($request->location, function ($query, $location) {
                    $query->where('location', 'like', '%' . $location . '%');
                })
                ->when($request->city_name, function ($query, $cityName) {
                    $query->whereHas('city', function ($query) use ($cityName) {
                        $query->where('name', 'like', '%' . $cityName . '%');
                    });
                })->with('images')
                ->get();
            $halls = $results->map(function ($hall) {
                // Remove imageable_type and imageable_id from images
                $hall->images()->get();
                unset($hall->images->imageable_type);
                unset($hall->images->imageable_id);
                return $hall;
            });

            /* $results = Hall::query()
                ->when($request->city_name, function ($query, $cityName) {
                    $query->whereHas('city', function ($query) use ($cityName) {
                        $query->where('name', 'like', '%' . $cityName . '%');
                    });
                })
                ->get(); */



            return response()->json(['results' => $results], 200);

            return $this->jsonResponse($halls, 'ok', 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during the search process.',
                'error' => $e->getMessage()
            ], 400);
        }
    
    }
}
