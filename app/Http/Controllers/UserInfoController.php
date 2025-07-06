<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Storage;

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
        $userInfo = UserInfo::all();

        return response()->json([
            'message' => 'User Info fetched successfully!',
            'data'    => $userInfo
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $userInfo = UserInfo::find($id);

        if (!$userInfo) {
            return response()->json(['message' => 'User info not found.'], 404);
        }

        $request->validate([
            'CompanyName' => 'required|string|max:255|unique:user_infos,CompanyName,' . $id,
            'YourName' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($userInfo->image && Storage::disk('public')->exists($userInfo->image)) {
                Storage::disk('public')->delete($userInfo->image);
            }
            // Store new image
            $userInfo->image = $request->file('image')->store('user_images', 'public');
        }

        $userInfo->CompanyName = $request->CompanyName;
        $userInfo->YourName = $request->YourName;
        $userInfo->save();

        return response()->json([
            'message' => 'User info updated successfully!',
            'data' => $userInfo
        ], 200);
    }
}
