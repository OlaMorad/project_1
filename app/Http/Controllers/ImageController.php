<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif',
        ]);
        $image = $request->file('image')->getClientOriginalExtension();
        $path = $request->file('image')->store('images');

    }
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalName();
        $image->move(public_path('images'), $imageName);
        // Save path to database
       
        return response()->json(['message' => 'Image uploaded successfully', 'image' => $imageName]);
    }

    public function index()
    {
        $images = Image::all();

        return response()->json(['images' => $images]);
    }
}
