<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectModel;

class ProjectController extends Controller
{
    //


    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'text' => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp', // Ensure the uploaded file is an image
        ]);

        // Sanitize or escape title and description if necessary (optional)
        // In most cases, Laravel handles this well, but it's good to ensure no unwanted HTML is stored
        $validatedData['title'] = strip_tags($validatedData['title']);
        $validatedData['description'] = strip_tags($validatedData['description']);
        $validatedData['text'] = strip_tags($validatedData['text']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store the image in the "public/blogs" directory
            $imagePath = $request->file('image')->store('projects', 'public');
            $validatedData['image'] = $imagePath; // Save the file path
        } else {
            return response()->json(['error' => 'Image upload failed'], 400);
        }

        // Mass assignment protection: Ensure that only allowed fields are being mass assigned
        $projects = ProjectModel::create([
            'title'       => $validatedData['title'],
            'description' => $validatedData['description'],
            'text' => $validatedData['text'],
            'image'       => $validatedData['image']
        ]);

        // Return success response
        return response()->json([
            'message' => 'Service created successfully!',
            'data'    => $projects
        ], 201);
    }



        public function index()
        {
            // Fetch all blogs from the database
            $projects = ProjectModel::all();

            // Return a success response with the blogs data
            return response()->json([
                'message' => 'About fetched successfully!',
                'data'    => $projects
            ], 200);
        }


        public function destroy($id)
        {
            $projects = ProjectModel::find($id);

            if (!$projects) {
                return response()->json(['message' => 'service not found.'], 404);
            }

            // Delete the image file if it exists
            if ($projects->imageUrl) {
                Storage::delete('public/' . $projects->imageUrl);
            }

            // Delete the blog entry
            $projects->delete();

            return response()->json(['message' => 'projects deleted successfully.'], 200);
        }


        public function update(Request $request, $id)
        {
            // Find the blog by ID
            $projects = ProjectModel::find($id);

            if (!$projects) {
                return response()->json([
                    'error' => 'projects not found!',
                ], 404);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'title'       => 'required|string|max:255',
                'description' => 'required|string',
                'text' => 'required|string',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp', // Image is optional during update
            ]);

            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($projects->image && file_exists(public_path('storage/' . $projects->image))) {
                    unlink(public_path('storage/' . $projects->image));
                }

                // Store the new image in the "public/blogs" directory
                $imagePath = $request->file('image')->store('projects', 'public');
                $validatedData['image'] = $imagePath; // Save the new file path
            }

            // Update the blog with the validated data
            $projects->update($validatedData);

            // Return success response
            return response()->json([
                'message' => 'Blog updated successfully!',
                'data'    => $projects,
            ], 200);
        }

}
