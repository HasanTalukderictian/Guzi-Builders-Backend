<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class EmployeeController extends Controller
{


    public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required|string',
        'employee_name' => 'required|string',
        'status' => 'required|string',
        'joining_date' => 'required|date',
        'designation' => 'required|string',
        'department' => 'required|string',
        'contact_no' => 'required|string',
        'image' => 'nullable|image|max:2048', // max 2MB
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('employee_images', 'public');
    } else {
        $imagePath = null;
    }

    Employee::create([
        'employee_id' => $request->employee_id,
        'employee_name' => $request->employee_name,
        'status' => $request->status,
        'joining_date' => $request->joining_date,
        'designation' => $request->designation,
        'department' => $request->department,
        'contact_no' => $request->contact_no,
        'image' => $imagePath,
    ]);

    return response()->json(['message' => 'Employee added successfully!'], 201);
}




public function update(Request $request, $id)
{
    // Find employee by ID
    $employee = Employee::find($id);

    if (!$employee) {
        return response()->json(['message' => 'Employee not found'], 404);
    }

    // Catch validation errors and return as JSON
    try {
        $request->validate([
            'employee_id' => 'required|string|unique:employees,employee_id,' . $id,
            'employee_name' => 'required|string',
            'status' => 'required|in:Active,Deactive',
            'joining_date' => 'required|date',
            'designation' => 'required|string',
            'department' => 'required|string',
            'contact_no' => 'required|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:51200', // max 50MB

        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors(),
        ], 422);
    }

    // Handle image upload if present
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($employee->image && Storage::disk('public')->exists($employee->image)) {
            Storage::disk('public')->delete($employee->image);
        }

        $imagePath = $request->file('image')->store('employee_images', 'public');
        $employee->image = $imagePath;
    }

    // Update other fields
    $employee->employee_id = $request->employee_id;
    $employee->employee_name = $request->employee_name;
    $employee->status = $request->status;
    $employee->joining_date = $request->joining_date;
    $employee->designation = $request->designation;
    $employee->department = $request->department;
    $employee->contact_no = $request->contact_no;

    $employee->save();

    return response()->json([
        'message' => 'Employee updated successfully!',
        'data' => $employee
    ], 200);
}


public function index(Request $request)
{
    $search = $request->query('search');

    $query = Employee::query();

    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('employee_id', 'like', "%$search%")
              ->orWhere('employee_name', 'like', "%$search%");
        });
    }

    // Always order by latest (assuming 'id' is auto-increment)
    $employees = $query->orderBy('id', 'desc')->get();

    return response()->json([
        'data' => $employees,
    ]);
}



public function show($id)
{
    // Find the employee by ID
    $employee = Employee::find($id);

    // Check if the employee exists
    if (!$employee) {
        return response()->json([
            'success' => false,
            'message' => 'Employee not found.'
        ], 404);
    }

    // Return the employee details in the response
    return response()->json([
        'success' => true,
        'data' => $employee
    ]);
}

public function destroy($id)
{
    $employee = Employee::find($id);
    if (!$employee) {
        return response()->json(['message' => 'Employee not found'], 404);
    }

    $employee->delete();
    return response()->json(['message' => 'Employee deleted successfully']);
}



}
