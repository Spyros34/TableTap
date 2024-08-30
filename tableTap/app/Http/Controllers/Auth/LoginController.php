<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function loginOwner(Request $request)
{
    $credentials = $request->only('username', 'password');

    // Log the credentials being attempted
    logger()->info('Attempting login with credentials: ', $credentials);

    if (Auth::guard('owner')->attempt($credentials)) {
        return redirect()->intended('/dashboard');
    }

    // Log a warning if login failed
    logger()->warning('Login failed for username: ' . $credentials['username']);

    return back()->withErrors(['username' => 'Invalid username or password']);
}

    public function loginKitchen(Request $request)
    {
        $credentials = $request->only('name', 'password');
        if (Auth::guard('kitchen')->attempt($credentials)) {
            return redirect()->intended('/kitchen');
        }
        return back()->withErrors(['name' => 'Invalid credentials']);
    }

    public function loginWaiter(Request $request)
    {
        $credentials = $request->only('username', 'password');
        if (Auth::guard('waiter')->attempt($credentials)) {
            return redirect()->intended('/waiter');
        }
        return back()->withErrors(['username' => 'Invalid credentials']);
    }
}