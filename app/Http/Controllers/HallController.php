<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HallController extends Controller
{
    public function show_all_halls()
    {
        $halls = Hall::with('images')->get();
        return response()->json(['data' => $halls, 'message' => 'ok', 'stauts' => 200]);
    }
    public function get($id)
    {
        $hall = Hall::with('images')->find($id);
        if (!$hall) {
            return response()->json(['message' => 'hall not found', 'stauts' => 404]);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validation 
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'city_id' => 'required|exists:cities,id',
                'capacity' => 'required|integer',
                'description' => 'nullable|string',
                'location' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            // If validation fails, return validation errors
            return response()->json(['data' => $e->validator->errors(), 'message' => 'Validation failed', 'status' => false], 422);
            // create new hall
            $hall = Hall::create($validatedData);

            return response()->json([
                'message' => 'Hall created successfully',
                'hall' => $hall
            ], 201);
        } catch (Exception $e) {
            return response()->json(['data' => $e->getMessage(), 'message' => 'an exception occured', 'status' => 400]);
        }
    }
    public function delete_hall(Request $request)
    {
        try {
            $hall = Hall::findOrFail($request->id); // have a hall by ID

            if ($hall->delete()) { // try to delete a hall
                return response()->json([
                    'message' => 'Hall deleted successfully'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Failed to delete hall'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during the deletion process.',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            // Find the hall by ID
            $hall = Hall::with('images')->find($id);
            // Check if the hall exists
            if (!$hall) {
                return response()->json(['message' => 'Hall not found', 'status' => 404]);
            }
            // Update the attributes
            $hall->update([
                'name' => $request->name,
                'capacity' => $request->capacity,
                'description' => $request->description,
                'location' => $request->location,
            ]);

            return response()->json(['data' => $hall, 'message' => 'Hall updated successfully', 'status' => 200]);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the hall', 'status' => 500]);
        }
    }
}
