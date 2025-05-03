<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserInfo;

class UserInfoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'CompanyName' => 'required|string|max:255|unique:user_infos,CompanyName',
            'YourName' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('user_images', 'public');
        }

        $userInfo = UserInfo::create([
            'CompanyName' => $request->CompanyName,
            'YourName' => $request->YourName,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => true, 'data' => $userInfo]);
    }



    public function index()
    {
        // Fetch all blogs from the database
        $userInfo = UserInfo::all();

        // Return a success response with the blogs data
        return response()->json([
            'message' => 'User Info fetched successfully!',
            'data'    => $userInfo
        ], 200);
    }





}
