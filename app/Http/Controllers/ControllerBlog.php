<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogModel;

class ControllerBlog extends Controller
{
    //

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp', // Ensure the uploaded file is an image
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store the image in the "public/blogs" directory
            $imagePath = $request->file('image')->store('blogs', 'public');
            $validatedData['image'] = $imagePath; // Save the file path
        } else {
            return response()->json(['error' => 'Image upload failed'], 400);
        }

        // Save the blog to the database
        $blog = BlogModel::create($validatedData);

        // Return success response
        return response()->json([
            'message' => 'Blog created successfully!',
            'data'    => $blog
        ], 201);
    }


    public function index()
{
    // Fetch all blogs from the database
    $blogs = BlogModel::all();

    // Return a success response with the blogs data
    return response()->json([
        'message' => 'Blogs fetched successfully!',
        'data'    => $blogs
    ], 200);
}



public function destroy($id)
    {
        $blog = BlogModel::find($id);

        if (!$blog) {
            return response()->json([
                'error' => 'Blog not found!',
            ], 404);
        }

        // Delete the associated image file if exists
        if ($blog->image && file_exists(public_path('storage/' . $blog->image))) {
            unlink(public_path('storage/' . $blog->image));
        }

        // Delete the blog record
        $blog->delete();

        return response()->json([
            'message' => 'Blog deleted successfully!',
        ], 200);
    }

    public function update(Request $request, $id)
{
    // Find the blog by ID
    $blog = BlogModel::find($id);

    if (!$blog) {
        return response()->json([
            'error' => 'Blog not found!',
        ], 404);
    }

    // Validate the incoming request data
    $validatedData = $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp', // Image is optional during update
    ]);

    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($blog->image && file_exists(public_path('storage/' . $blog->image))) {
            unlink(public_path('storage/' . $blog->image));
        }

        // Store the new image in the "public/blogs" directory
        $imagePath = $request->file('image')->store('blogs', 'public');
        $validatedData['image'] = $imagePath; // Save the new file path
    }

    // Update the blog with the validated data
    $blog->update($validatedData);

    // Return success response
    return response()->json([
        'message' => 'Blog updated successfully!',
        'data'    => $blog,
    ], 200);
}


public function show($id)
{
    // Find the project by ID
    $Bmodel = BlogModel::find($id);

    // Check if the project exists
    if (!$Bmodel) {
        return response()->json([
            'message' => 'BlogModel not found.',
            'data'    => null,
        ], 404);
    }

    // Return the project data
    return response()->json([
        'message' => 'BlogModel fetched successfully!',
        'data'    => $Bmodel,
    ], 200);
}


}



