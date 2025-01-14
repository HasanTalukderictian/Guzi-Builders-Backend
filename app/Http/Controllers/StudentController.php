<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
class StudentController extends Controller
{
    //
     
    public function hello()
{
    // Retrieve all students from the Student table
    $students = Student::all();

    // Return the students data as a JSON response
    return response()->json([
        'message' => 'Hello, here is the list of all students.',
        'students' => $students
    ]);
}

    public function store(Request $request)
    {
        // Validate the incoming data (optional but recommended)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'dept' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
        ]);

        // Create a new student using the validated data
        $student = Student::create([
            'name' => $validatedData['name'],
            'dept' => $validatedData['dept'],
            'university' => $validatedData['university'],
            'subject' => $validatedData['subject'],
        ]);

        // Return a response (you can customize this as per your needs)
        return response()->json([
            'message' => 'Student created successfully!',
            'student' => $student
        ], 201);  // 201 status code for resource creation
    }
}
