<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutModel;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    //


    public function store(Request $request)
{
    // Check if there's already a record in the database
    $existingBlog = AboutModel::first(); // Check if any record exists

    if ($existingBlog) {
        return response()->json(['message' => 'A blog already exists. Only one blog can be posted.'], 400);
    }

    $validatedData = $request->validate([
        'heading' => 'required|string|max:255',
        'description' => 'required|string',
        'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Adjust image validation as needed
    ]);

    // Handle image upload if provided
    if ($request->hasFile('imageUrl')) {
        $imagePath = $request->file('imageUrl')->store('images', 'public');
        $validatedData['imageUrl'] = $imagePath;
    }

    // Create the new blog record
    $blog = AboutModel::create($validatedData);

    return response()->json($blog, 201);
}


    public function index()
    {
        // Fetch all blogs from the database
        $about = AboutModel::all();

        // Return a success response with the blogs data
        return response()->json([
            'message' => 'About fetched successfully!',
            'data'    => $about
        ], 200);
    }


    public function destroy($id)
{
    $about = AboutModel::find($id);

    if (!$about) {
        return response()->json(['message' => 'about not found.'], 404);
    }

    // Delete the image file if it exists
    if ($about->imageUrl) {
        Storage::delete('public/' . $about->imageUrl);
    }

    // Delete the blog entry
    $about->delete();

    return response()->json(['message' => 'about deleted successfully.'], 200);
}


public function update(Request $request, $id)
{
    $blog = AboutModel::find($id);

    if (!$blog) {
        return response()->json(['message' => 'Blog not found.'], 404);
    }

    $validatedData = $request->validate([
        'heading' => 'required|string|max:255',
        'description' => 'required|string',
        'imageUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Adjust image validation as needed
    ]);

    // Handle image upload if provided
    if ($request->hasFile('imageUrl')) {
        $imagePath = $request->file('imageUrl')->store('images', 'public');
        $validatedData['imageUrl'] = $imagePath;
    }

    // Update the blog
    $blog->update($validatedData);

    return response()->json($blog, 200);
}



}
