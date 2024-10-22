<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}