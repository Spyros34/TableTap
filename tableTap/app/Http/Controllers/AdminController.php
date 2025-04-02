<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {
        // Retrieve the "system_enabled" row
        $setting = SystemSetting::where('key', 'system_enabled')->first();
        
        // Convert string "true"/"false" to an actual Boolean
        $enabled = $setting && $setting->value === 'true';
    
        // Pass the boolean to the Inertia view
        return Inertia::render('Admin/SystemStatus', [
            'enabled' => $enabled,            // A true or false boolean
            'flash'   => session('success'),  // Optional flash message
        ]);
    }
   
    // Display the system status page using Inertia.
   // Show the system status
// AdminController.php
public function showSystemStatus()
{
    // Grab the row: "system_enabled => 'true' or 'false'"
    $setting = SystemSetting::where('key', 'system_enabled')->first();
    // Convert it to a real bool: "true" => true, "false" => false
    $enabled = $setting && $setting->value === 'true';

    return Inertia::render('Admin/SystemStatus', [
        'enabled' => $enabled, // a real boolean
        'flash'   => session('success'),
    ]);
}
// Update the system status
public function updateSystemStatus(Request $request)
{
    $request->validate([
        'enabled' => 'required|boolean',
    ]);

    // Store 'true' or 'false' as a string
    SystemSetting::updateOrCreate(
        ['key' => 'system_enabled'],
        ['value' => $request->boolean('enabled') ? 'true' : 'false']
    );

    // Redirect back to showSystemStatus
    return redirect()->route('admin.system_status.show')
                     ->with('success', 'System status updated successfully.');
}

public function logout(Request $request)
{
    // Log out using the 'admin' guard
    Auth::guard('admin')->logout();

    // Invalidate and regenerate the session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirect to the admin login page with a success message
    return redirect()->route('login.admin')->with('success', 'Logged out successfully.');
}
}