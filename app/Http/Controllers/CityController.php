<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function show_all_cities()
    {
        try {
            $cities = City::with('images')->get();
            // Modify the structure of the data
            $modified_cities = $cities->map(function ($cities) {
                // Modify each city's images to include the asset() function
                $cities->images->each(function ($image) {
                    $image->path = asset($image->path);
                });
                // Unset imageable_id and imageable_type from the images object
                unset($cities->images->imageable_id, $cities->images->imageable_type);
                unset($cities->deleted_at);

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
            $halls = $city->halls()->with('images')->get();
            if ($halls->isEmpty()) {
                return response()->json(['message' => 'There are no halls for this City', 'status' => 200]);
            }
            $Halls = $halls->map(function ($hall) {
                // Remove imageable_type and imageable_id from images
                $hall->images()->get();
                unset($hall->images->imageable_type);
                unset($hall->images->imageable_id);
                unset($hall->deleted_at);

                return $hall;
            });

            return response()->json(['data' => $Halls, 'message' => 'ok', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['data' => 'an exception occured', 'message' => $e->getMessage(), 'status' => 400]);
        }
    }
    public function store(Request $request)
    {
        try {
            // Validate form data
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['data' => $validator->errors(), 'message' => 'Incorrect or missing information', 'status' => 400]);
            }

            // Handle file upload and storage
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;

           // Storage::putFileAs('public', $image, $imageName);

            // Create a new city record
            $city = City::create([
                'name' => $request->name,
                // Add other city fields if needed
            ]);

            // Create a new image record
            $image = new Image();
            $image->path = $imagePath;
            $image->imageable_type = 'App\Models\City';
            $image->imageable_id = $city->id;
            $city->images()->save($image);

            // Remove unwanted fields from the output
            unset($city->images->imageable_type);
            unset($city->images->imageable_id);
            unset($city->deleted_at);

            return response()->json(['message' => 'City created successfully', 'city' => $city, 'status' => 201]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 500]);
        }
    }

    public function softDelete($id)
    {
        try {
            $city = City::find($id);
            if (!$city) {
                return response()->json(['message' => 'City not found', 'status' => 404], 404);
            }

            $city->delete();

            return response()->json(['message' => 'City soft deleted successfully', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 500]);
        }
    }
    public function forceDelete($id)
    {
        try {
            $city = City::withTrashed()->find($id);
            if (!$city) {
                return response()->json(['message' => 'City not found', 'status' => 404], 404);
            }

            $city->forceDelete();

            return response()->json(['message' => 'City force deleted successfully', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 500]);
        }
    }

    public function restore($id)
    {
        try {
            $city = City::withTrashed()->find($id);
            if (!$city) {
                return response()->json(['message' => 'City not found', 'status' => 404], 404);
            }

            $city->restore();

            return response()->json(['message' => 'City restored successfully', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 500]);
        }
    }
}
