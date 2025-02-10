<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactModel;

class ContactController extends Controller
{
    //

    public function store(Request $request)
{
    try {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Create a new contact record
        ContactModel::create($validatedData);

        // Return a success message
        return response()->json([
            'status' => 'success',
            'message' => 'Your Message submitted successfully!',
        ]);
    } catch (\Exception $e) {
        // Return an error message if something goes wrong
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while submitting contact information.',
            'details' => $e->getMessage(),
        ], 500);
    }
}


public function index()
{
    // Fetch all blogs from the database
    $contact = ContactModel::orderBy('id', 'desc')->get();

    // Return a success response with the blogs data
    return response()->json([
        'message' => 'Conact Message fetched successfully!',
        'data'    => $contact
    ], 200);
}

}
