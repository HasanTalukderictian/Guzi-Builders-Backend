<?php

Namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
class TeamController extends Controller
{
    //

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'Name'        => 'required|string|max:255',
            'Designation' => 'required|string',
            'socialMediaLink'      => 'required|string',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        // Sanitize data
        $validatedData['Name'] = strip_tags($validatedData['Name']);
        $validatedData['Designation'] = strip_tags($validatedData['Designation']);
        $validatedData['socialMediaLink'] = strip_tags($validatedData['socialMediaLink']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('team', 'public');
            $validatedData['image'] = $imagePath;
        } else {
            return response()->json(['error' => 'Image upload failed'], 400);
        }

        // Create the record
        $team = Team::create([
            'Name'        => $validatedData['Name'],
            'Designation' => $validatedData['Designation'],
            'socialMediaLink'      => $validatedData['socialMediaLink'],
            'image'       => $validatedData['image'],
        ]);

        // Return response
        return response()->json([
            'message' => 'Testominal created successfully!',
            'data'    => $team,
        ], 201);
    }


        public function index()
        {
            // Fetch all blogs from the database
            $team = Team::all();

            // Return a success response with the blogs data
            return response()->json([
                'message' => 'About fetched successfully!',
                'data'    => $team
            ], 200);
        }


        public function destroy($id)
        {
            $team = Team::find($id);

            if (!$team) {
                return response()->json(['message' => 'service not found.'], 404);
            }

            // Delete the image file if it exists
            if ($team->imageUrl) {
                Storage::delete('public/' . $team->imageUrl);
            }

            // Delete the blog entry
            $team->delete();

            return response()->json(['message' => 'testominal deleted successfully.'], 200);
        }


        public function update(Request $request, $id)
        {
            // Find the blog by ID
            $team = Team::find($id);

            if (!$team) {
                return response()->json([
                    'error' => 'team not found!',
                ], 404);
            }

            // Validate the incoming request data
            $validatedData = $request->validate([
                'Name'       => 'required|string|max:255',
                'Designation' => 'required|string',
                'socialMediaLink' => 'required|string',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            ]);

            // Handle image upload if a new image is provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($team->image && file_exists(public_path('storage/' . $team->image))) {
                    unlink(public_path('storage/' . $team->image));
                }

                // Store the new image in the "public/blogs" directory

                $imagePath = $request->file('image')->store('team', 'public');
                $validatedData['image'] = $imagePath;  // Save the new file path
            }

            // Update the blog with the validated data
            $team->update($validatedData);

            // Return success response
            return response()->json([
                'message' => 'Blog updated successfully!',
                'data'    => $team,
            ], 200);
        }

}
