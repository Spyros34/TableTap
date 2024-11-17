<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Waiter;

class WaiterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all waiters associated with the authenticated user's shop
        $user = Auth::user();
        $shop = $user->shops()->first(); // Adjust if a user has multiple shops

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        $waiters = $shop->waiters;

        return Inertia::render('Owner/Waiter', [
            'waiterItems' => $waiters,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:waiters,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $shop = $user->shops()->first();

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        // Create the waiter
        $waiter = Waiter::create([
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Attach waiter to shop
        $shop->waiters()->attach($waiter->id, ['created_at' => now()]);

        return Redirect::back()->with([
            'flash' => ['success' => 'Waiter created successfully.'],
            'newWaiter' => $waiter,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $waiter = Waiter::findOrFail($id);

        // Validate the input data
        $validatedData = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', 'unique:waiters,username,' . $id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update the waiter's information
        if ($request->filled('name')) {
            $waiter->name = $validatedData['name'];
        }
        if ($request->filled('surname')) {
            $waiter->surname = $validatedData['surname'];
        }
        if ($request->filled('username')) {
            $waiter->username = $validatedData['username'];
        }
        if ($request->filled('password')) {
            $waiter->password = Hash::make($validatedData['password']);
        }

        $waiter->save();

        return Redirect::back()->with('flash', ['success' => 'Waiter updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $waiter = Waiter::findOrFail($id);

        // Detach the waiter from all associated shops
        $waiter->shops()->detach();

        // Delete the waiter
        $waiter->delete();

        return Redirect::back()->with('flash', ['success' => 'Waiter deleted successfully.']);
    }
}