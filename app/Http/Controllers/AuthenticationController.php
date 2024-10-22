<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Role;
use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllersMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller 
{
    // Register User
    public function register(Request $request) {
        // Validate
        $fields = $request->validate([
            'username' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'min:3', 'confirmed'],
            'department_id' => ['required'],
            'role_id' => ['required']
        ],
        [   // Message Error
            'username.required' => 'The username is required.',
            'email.required' => 'The email is required.',
            'password.required' => 'The password is required.',
            'department_id' => 'Please select your department.',
            'role_id.required' => 'Please select a role.'
        ]);


        // Register
        $user = User::create($fields);

        // Login
        Auth::login($user);

        event(new Registered($user));

        // Redirect
        return redirect()->route('login');
    }

    // Email Verification Notice
    public function verifyNotice(Request $request) {
        return view('authentication.verify-email');
    }

    // Email Verification Handler
    public function verifyEmail(EmailVerificationRequest $request) {
        $request->fulfill();
    
        return redirect()->route('login');
    }

    // Resending the Verification Email
    public function verifyHandler(Request $request) {
        $request->user()->sendEmailVerificationNotification();
     
        return back()->with('message', 'Verification link sent!');
    }


    // Login User
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => ['required', 'max:255'],
            'password' => ['required']
        ]);

        // Try to login the user
        if (Auth::attempt($fields, $request->remember)) {
            return redirect()->intended();
        } else {
            return back()->withErrors([
                'failed' => 'The provided credentials do not match our records.'
            ]);
        }

    }

    public function logout(Request $request) {
        // Logout
        Auth::logout();

        // Invalidate User Session and Deleting CSRF Token
        $request->session()->invalidate();

        // Regenerate New CSRF Token
        $request->session()->regenerateToken();

        return redirect()->route('login'); 
    }

    public function index() {

        $data = Role::all();

        return view('authentication.register', ['data' => $data]);
    }

}
