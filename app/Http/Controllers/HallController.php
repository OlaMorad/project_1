<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;

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
    public function create_hall(Request $request)
    {
        
        $hall = Hall::create([
            'name' =>$request->name
        ]);
    }
}
