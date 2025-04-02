<?php

namespace App\Http\Controllers\Auth;

use App\Models\Waiter;
use App\Models\Kitchen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to log in with an extra check to ensure the user is an admin.
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect to the admin dashboard 
            return redirect()->route('admin.dashboard')->with('success', 'Login successful.');
        }

        return back()->withErrors([
            'username' => 'Invalid credentials or you are not authorized as an admin.',
        ]);
    }

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
            'shop_id'  => 'required|exists:shops,id', // Validate shop_id exists
        ]);
    
        $shopId = $credentials['shop_id'];
    
        // Attempt to log in using the 'kitchen' guard
        if (Auth::guard('kitchen')->attempt([
            'name' => $credentials['username'],
            'password' => $credentials['password'],
            'shop_id' => $shopId, // Ensure shop_id matches
        ])) {
            $kitchenUser = Auth::guard('kitchen')->user();
    
            // Ensure the authenticated user is a Kitchen instance
            if (!$kitchenUser instanceof Kitchen) {
                Auth::guard('kitchen')->logout();
                return back()->withErrors(['username' => 'Invalid user type.']);
            }
    
            // Store the shop information in the session for later use
            session(['kitchen_shop_id' => $shopId]);
    
            // Redirect to the kitchen dashboard
            return redirect()->route('kitchen.dashboard')->with('success', 'Login successful.');
        }
    
        // If login fails, redirect back with an error message
        return back()->withErrors([
            'username' => 'Invalid username, password, or shop.',
        ]);
    }

      /**
     * Log in a Waiter.
     */
    public function loginWaiter(Request $request)
    {
        // Validate input credentials
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        // Attempt to log in using the 'waiter' guard
        if (Auth::guard('waiter')->attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password']
        ])) {
            // Retrieve the logged-in user from the 'waiter' guard
            $waiterUser = Auth::guard('waiter')->user();

            // Ensure the authenticated user is indeed a Waiter
            if (!$waiterUser instanceof Waiter) {
                Auth::guard('waiter')->logout();
                return back()->withErrors(['username' => 'Invalid user type.']);
            }

            // Check if the waiter is associated with at least one shop
            if ($waiterUser->shops()->count() === 0) {
                Auth::guard('waiter')->logout();
                return redirect()->route('create-shop')->withErrors([
                    'username' => 'No shop is associated with this waiter user.',
                ]);
            }

            // On success, redirect to the waiter dashboard
            return redirect()->route('waiter.dashboard')->with('success', 'Login successful.');
        }

        // If login fails, redirect back with an error
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