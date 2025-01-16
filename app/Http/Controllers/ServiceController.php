<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceModel;
class ServiceController extends Controller
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

    // Sanitize or escape title and description if necessary (optional)
    // In most cases, Laravel handles this well, but it's good to ensure no unwanted HTML is stored
    $validatedData['title'] = strip_tags($validatedData['title']);
    $validatedData['description'] = strip_tags($validatedData['description']);

    // Handle image upload
    if ($request->hasFile('image')) {
        // Store the image in the "public/blogs" directory
        $imagePath = $request->file('image')->store('blogs', 'public');
        $validatedData['image'] = $imagePath; // Save the file path
    } else {
        return response()->json(['error' => 'Image upload failed'], 400);
    }

    // Mass assignment protection: Ensure that only allowed fields are being mass assigned
    $service = ServiceModel::create([
        'title'       => $validatedData['title'],
        'description' => $validatedData['description'],
        'image'       => $validatedData['image']
    ]);

    // Return success response
    return response()->json([
        'message' => 'Service created successfully!',
        'data'    => $service
    ], 201);
}



    public function index()
    {
        // Fetch all blogs from the database
        $service = ServiceModel::all();

        // Return a success response with the blogs data
        return response()->json([
            'message' => 'About fetched successfully!',
            'data'    => $service
        ], 200);
    }


    public function destroy($id)
    {
        $service = ServiceModel::find($id);

        if (!$service) {
            return response()->json(['message' => 'service not found.'], 404);
        }

        // Delete the image file if it exists
        if ($service->imageUrl) {
            Storage::delete('public/' . $service->imageUrl);
        }

        // Delete the blog entry
        $service->delete();

        return response()->json(['message' => 'service deleted successfully.'], 200);
    }


    public function update(Request $request, $id)
    {
        // Find the blog by ID
        $blog = ServiceModel::find($id);

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
    // Find the service by ID
    $service = ServiceModel::find($id);

    // Check if the service exists
    if (!$service) {
        return response()->json([
            'error' => 'Service not found!',
        ], 404);
    }

    // Return the service data
    return response()->json([
        'message' => 'Service fetched successfully!',
        'data'    => $service,
    ], 200);
}





}
