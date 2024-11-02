<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    // ProfileController.php

// ProfileController.php

// ProfileController.php
public function edit(Request $request): Response
{
    $user = $request->user();
    $shop = $user->shops()->first(); // Assuming there's a relationship set up for shops

    return Inertia::render('Profile/Edit', [
        'owner' => [
            'name' => $user->name,
            'surname' => $user->surname,
            'username' => $user->username,
            'email' => $user->email,
        ],
        'shop' => $shop ? [
            'storeName' => $shop->brand,
            'storeType' => $shop->type,
            'address' => $shop->address,
            'region' => $shop->region,
            'city' => $shop->city,
            'postalCode' => $shop->tk,
            'phone' => $shop->phone_number,
        ] : null,
    ]);
}


    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = array_filter($request->validated(), fn($value) => $value !== null);
    
        if (empty($validated)) {
            return back()->withErrors(['general' => 'Please fill at least one field before submitting.']);
        }
    
        $owner = $request->user();
    
        // Update only the fields in `owners` table
        $owner->fill(array_filter($validated, function ($value, $key) {
            return in_array($key, ['name', 'surname', 'username', 'email']);
        }, ARRAY_FILTER_USE_BOTH));
        $owner->save();
    
        // Update related Shop fields if shop exists
        if ($owner->shops()->exists()) {
            $shop = $owner->shops()->first();
            $shopData = [
                'brand' => $validated['storeName'] ?? $shop->brand,
                'type' => $validated['storeType'] ?? $shop->type,
                'address' => $validated['address'] ?? $shop->address,
                'region' => $validated['region'] ?? $shop->region,
                'city' => $validated['city'] ?? $shop->city,
                'tk' => $validated['postalCode'] ?? $shop->tk,
                'phone_number' => $validated['phone'] ?? $shop->phone_number,
            ];
            $shop->update(array_filter($shopData));
        }
    
        return Redirect::route('profile.edit')->with('flash', ['success' => 'Profile updated successfully.']);
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // Ensure password confirmation
        ]);

        $user = Auth::user();

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            // If not, throw a validation error
            throw ValidationException::withMessages([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Redirect with a success message
        return back()->with('flash', ['success' => 'Password updated successfully.']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
