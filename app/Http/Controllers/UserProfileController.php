<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function index()
    {
        return view('profile.my-account');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $userPassword = Auth::user()->password;

        // Validate and update profile details
        $fields = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'department_id' => 'required|integer',
            'role_id' => 'required|integer',
        ]);

        $user->update([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'department_id' => $fields['department_id'],
            'role_id' => $fields['role_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ]);
    }

    public function updatePassword(Request $request)
    {

        // Validate the request
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'min:3', 'confirmed'],
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Check if the old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The old password is incorrect.'
            ]);
        } 

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.'
        ]);
        // return redirect()->back()->with('success_password', 'Password updated successfully.');


    }
}