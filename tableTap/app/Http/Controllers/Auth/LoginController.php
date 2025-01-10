<?php

namespace App\Http\Controllers\Auth;

use App\Models\Kitchen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginOwner(Request $request)
    {
        // Validate the credentials
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log in the owner
        if (Auth::attempt($credentials)) {
            $owner = Auth::user();

            // If no shop exists, redirect to shop creation page
            if ($owner->shops()->count() === 0) {
                return redirect()->route('create-shop');
            }

            // Redirect to the dashboard
            return redirect()->intended('/');
        }

        // If login fails, return with an error
        return back()->withErrors([
            'username' => 'Invalid username or password',
        ]);
    }

    public function loginKitchen(Request $request)
{
    // Validate input credentials
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string|min:8',
    ]);

    // Attempt to log in using the 'kitchen' guard
    if (Auth::guard('kitchen')->attempt(['name' => $credentials['username'], 'password' => $credentials['password']])) {
        $kitchenUser = Auth::guard('kitchen')->user();

        // Ensure the authenticated user is a Kitchen instance
        if (!$kitchenUser instanceof Kitchen) {
            Auth::guard('kitchen')->logout();
            return back()->withErrors(['username' => 'Invalid user type.']);
        }

        // Check if the kitchen user is associated with shops
        if ($kitchenUser->shops()->count() === 0) {
            Auth::guard('kitchen')->logout();
            return redirect()->route('create-shop')->withErrors([
                'username' => 'No shop is associated with this kitchen user.',
            ]);
        }

        // Redirect to the kitchen dashboard
        return redirect()->route('kitchen.dashboard')->with('success', 'Login successful.');
    }

    // If login fails, redirect back with an error message
    return back()->withErrors([
        'username' => 'Invalid username or password.',
    ]);
}

    public function logout(Request $request)
    {
        Auth::logout(); //logout the user 


        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login'); // Redirect to the login page
    }
}