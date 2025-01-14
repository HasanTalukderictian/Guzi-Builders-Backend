<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testominal;

class TestominalController extends Controller
{
    //

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'required|string',
            'rating'      => 'required|string',
            'comment'     => 'required|string',
            'designation' => 'nullable|string|max:255',  // Added designation
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        // Sanitize data
        $validatedData['name'] = strip_tags($validatedData['name']);
        $validatedData['designation'] = strip_tags($validatedData['designation']);
        $validatedData['rating'] = strip_tags($validatedData['rating']);
        $validatedData['comment'] = strip_tags($validatedData['comment']);
        $validatedData['designation'] = strip_tags($validatedData['designation'] ?? '');

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects', 'public');
            $validatedData['image'] = $imagePath;
        } else {
            return response()->json(['error' => 'Image upload failed'], 400);
        }

        // Create the record
        $testominal = Testominal::create([
            'name'        => $validatedData['name'],
            'designation' => $validatedData['designation'],
            'rating'      => $validatedData['rating'],
            'comment'     => $validatedData['comment'],
            'designation' => $validatedData['designation'], // Ensure designation is passed
            'image'       => $validatedData['image'],
        ]);

        // Return response
        return response()->json([
            'message' => 'Testominal created successfully!',
            'data'    => $testominal,
        ], 201);
    }




        public function index()
        {
            // Fetch all blogs from the database
            $testominal = Testominal::all();

            // Return a success response with the blogs data
            return response()->json([
                'message' => 'About fetched successfully!',
                'data'    => $testominal
            ], 200);
        }


        public function destroy($id)
        {
            $testominal = Testominal::find($id);

            if (!$testominal) {
                return response()->json(['message' => 'service not found.'], 404);
            }

            // Delete the image file if it exists
            if ($testominal->imageUrl) {
                Storage::delete('public/' . $testominal->imageUrl);
            }

            // Delete the blog entry
            $testominal->delete();

            return response()->json(['message' => 'testominal deleted successfully.'], 200);
        }


        public function update(Request $request, $id)
        {
            // Find the blog by ID
            $testominal = Testominal::find($id);

            if (!$testominal) {
                return response()->json([
                    'error' => 'testominal not found!',
                ], 404);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'name'       => 'required|string|max:255',
                'designation' => 'required|string',
                'rating' => 'required|string',
                'comment' => 'required|string',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp', // Image is optional during update
            ]);

            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($testominal->image && file_exists(public_path('storage/' . $testominal->image))) {
                    unlink(public_path('storage/' . $testominal->image));
                }

                // Store the new image in the "public/blogs" directory
                $imagePath = $request->file('image')->store('testominal', 'public');
                $validatedData['image'] = $imagePath; // Save the new file path
            }

            // Update the blog with the validated data
            $testominal->update($validatedData);

            // Return success response
            return response()->json([
                'message' => 'Blog updated successfully!',
                'data'    => $testominal,
            ], 200);
        }

}
