<?php

namespace App\Http\Controllers\Auth;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function login(Request $request)
{
    \Log::info('Login attempt:', $request->all());

    $validated = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $customer = Customer::where('username', $validated['username'])->first();
    \Log::info('Customer:', ['customer' => $customer]);

    if (!$customer || !Hash::check($validated['password'], $customer->password)) {
        \Log::warning('Invalid credentials', $validated);
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    \Log::info('Login successful:', ['customer_id' => $customer->id]);

    return response()->json([
        'message' => 'Login successful',
        'customer' => [
            'id' => $customer->id,
            'name' => $customer->name,
            'username' => $customer->username,
        ],
    ]);
}

public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:customers',
        'email' => 'required|string|email|max:255|unique:customers',
        'password' => 'required|string|min:8|confirmed',
        'credit_card' => ['required', 'regex:/^[0-9]{16}$/'], // 16-digit format
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'region' => 'required|string|max:255',
        'tk' => 'required|string|size:5', // Greek postal codes are 5 digits
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422); // Unprocessable Entity
    }

    // Hash the password and create the customer
    $customer = Customer::create([
        'name' => $request->name,
        'surname' => $request->surname,
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'credit_card' => $request->credit_card,
        'address' => $request->address,
        'city' => $request->city,
        'region' => $request->region,
        'tk' => $request->tk,
    ]);

    return response()->json([
        'message' => 'Account created successfully',
        'customer' => $customer
    ], 201);
}
}