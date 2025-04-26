<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    //


    // public function store(Request $request)
    // {
    //     // Validate request
    //     $request->validate([
    //         'employee_id'   => 'required|string|max:255|unique:employees,employee_id',
    //         'employee_name' => 'required|string|max:255',
    //         'status'        => 'required|in:Active,Deactive',
    //         'joining_date'  => 'required|string', // Format: d-m-Y
    //         'designation'   => 'required|string|max:255',
    //         'department'    => 'required|string|max:255',
    //         'contact_no'    => 'required|digits_between:10,11|unique:employees,contact_no',
    //         'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // ✅ Corrected here
    //     ]);

    //     // Convert joining date to correct format
    //     $joiningDate = Carbon::createFromFormat('d-m-Y', $request->joining_date)->format('Y-m-d');

    //     $imagePath = null;

    //     if ($request->hasFile('images')) {
    //         $image = $request->file('images');
    //         $imageName = time() . '.' . $image->getClientOriginalExtension();
    //         $image->move(public_path('uploads'), $imageName);
    //     }

    //     // Store employee record
    //     Employee::create([
    //         'employee_id'   => $request->employee_id,
    //         'employee_name' => $request->employee_name,
    //         'status'        => $request->status,
    //         'joining_date'  => $joiningDate,
    //         'designation'   => $request->designation,
    //         'department'    => $request->department,
    //         'contact_no'    => $request->contact_no,
    //         'image'        => $imagePath,
    //     ]);

    //     return response()->json(['success' => true, 'message' => 'Employee added successfully!']);
    // }


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







    // public function filterEmployees(Request $request)
    // {
    //     if (!$request->filled('employee_id') && !$request->filled('employee_name')) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Please provide at least one filter value.'
    //         ], 400);
    //     }

    //     $query = Employee::query();

    //     $query->where(function ($q) use ($request) {
    //         if ($request->filled('employee_id')) {
    //             $q->orWhere('employee_id', 'like', '%' . $request->employee_id . '%');
    //         }

    //         if ($request->filled('employee_name')) {
    //             $q->orWhere('employee_name', 'like', '%' . $request->employee_name . '%');
    //         }
    //     });

    //     $employees = $query->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $employees
    //     ]);
    // }


    public function filterEmployees(Request $request)
{
    $query = $request->query('query');

    $employees = Employee::where('employee_name', 'like', "%$query%")->get();

    return response()->json(['data' => $employees]);
}

    // public function destroy($id)
    // {
    //     // Find the employee by ID
    //     $employee = Employee::find($id);

    //     // Check if employee exists
    //     if (!$employee) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Employee not found.'
    //         ], 404);
    //     }

    //     // Delete the employee record
    //     $employee->delete();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Employee deleted successfully!'
    //     ]);
    // }



    public function update(Request $request, $id)
{
    // Find the employee by ID
    $employee = Employee::find($id);

    // Check if employee exists
    if (!$employee) {
        return response()->json([
            'success' => false,
            'message' => 'Employee not found.'
        ], 404);
    }

    // Validate the request data
    $request->validate([
        'employee_id'   => 'required|string|max:255|unique:employees,employee_id,' . $employee->id,
        'employee_name' => 'required|string|max:255',
        'status'         => 'required|in:Active,Deactive',
        'joining_date'   => 'required|string', // Ensure correct format
        'designation'    => 'required|string|max:255',
        'department'     => 'required|string|max:255',
        'contact_no'     => 'required|digits_between:10,11|unique:employees,contact_no,' . $employee->id,
        'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Update the employee fields
    $employee->employee_id   = $request->employee_id;
    $employee->employee_name = $request->employee_name;
    $employee->status         = $request->status;
    $employee->joining_date   = Carbon::createFromFormat('d-m-Y', $request->joining_date)->format('Y-m-d');
    $employee->designation    = $request->designation;
    $employee->department     = $request->department;
    $employee->contact_no     = $request->contact_no;

    // Handle image upload if present
    if ($request->hasFile('image')) {
        // Delete the old image if a new one is uploaded
        if ($employee->image && file_exists(public_path('storage/' . $employee->image))) {
            unlink(public_path('storage/' . $employee->image));
        }

        // Store the new image
        $imagePath = $request->file('image')->store('employees', 'public');
        $employee->image = $imagePath;
    }

    // Save the updated employee
    $employee->save();

    return response()->json([
        'success' => true,
        'message' => 'Employee updated successfully!',
        'data' => $employee
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




}
