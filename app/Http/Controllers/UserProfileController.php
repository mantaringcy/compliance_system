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

    public function update(Request $request)
    {
        $user = auth()->user();
        $userPassword = Auth::user()->password;

        if ($request->input('update_type') === 'password') {
            // Set up the validator with basic rules
            $validator = Validator::make($request->all(), [
                'old_password' => ['required'],
                'new_password' => ['required', 'min:3', 'confirmed'],
            ]);
    
            // Add a custom validation error if the old password does not match
            $validator->after(function ($validator) use ($request, $user) {
                if (!Hash::check($request->old_password, $user->password)) {
                    $validator->errors()->add('old_password', 'The old password is incorrect.');
                }
            });
    
            // If validation fails, redirect back with errors and input
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
    
            // Update the password if validation passes
            $user->update(['password' => Hash::make($request->new_password)]);
    
            return redirect()->back()->with('success_password', 'Password updated successfully.');
        }

        if ($request->input('update_type') === 'profile') {
            // dd('profile');
            
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

            return redirect()->back()->with('success_profile', 'Profile updated successfully.');

        } 
        

        // return view('profile.my-account');
    }
}