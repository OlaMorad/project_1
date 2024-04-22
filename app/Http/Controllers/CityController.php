<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
    public function store(Request $request)
    {
        // Validate form data
        $validate = $request->validate([
            'name' => 'required',
            'image_url' => 'required|url',
        ]);
        // Create a new city record
        $city = City::create([
            'name' => $request->name,
            // Add other city fields if needed
        ]);
        $imagePath = $request->image_url;
        // Create a new image record
        $image = new Image();
        $image->path = $imagePath;
        $image->imageable_type = 'App\Models\City';
        $image->imageable_id = $city->id;
        $city->images()->save($image);
        // $image->save();
        $city = City::with('images')->find($city->id);
        return response()->json(['message' => 'City created successfully', 'city' => $city], 201);
    }
}
