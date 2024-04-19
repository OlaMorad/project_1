<?php

namespace App\Http\Controllers;

use App\Models\City;
use Exception;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function show_all_cities()
    {
        try {
            $cities = City::with('images')->get();
            // Modify the structure of the data
            $modified_cities = $cities->map(function ($cities) {
                // Unset imageable_id and imageable_type from the images object
                unset($cities->images->imageable_id, $cities->images->imageable_type);
                return $cities;
            });
            return response()->json(['data' => $modified_cities, 'massege' => 'ok', 'stauts' => 200]);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }
    public function halls($city_id)
    {
        try {
            $city = City::find($city_id);
            if (!$city) {
                return response()->json(['message' => 'City not found', 'status' => 400]);
            }
            $halls = $city->halls;
            return response()->json(['data' => $halls, 'message' => 'ok', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['data' => 'an exception occured', 'message' => $e->getMessage(), 'status' => 400]);
        }
    }
}
