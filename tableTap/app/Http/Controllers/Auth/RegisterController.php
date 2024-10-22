<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validate the user input
        $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:owners,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create a new owner with hashed password
        $owner = Owner::create([
            'name' => $request->firstName,
            'surname' => $request->lastName,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Automatically log the owner in
        Auth::login($owner);

        // Redirect to the create-shop route so the owner can create a shop
        return redirect()->route('create-shop'); // Redirect to the create-shop page after registration
    }
}