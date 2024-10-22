<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $owner = Auth::user();

        // Check if the owner has no shops
        if ($owner->shops()->count() === 0) {
            return redirect()->route('create-shop');
        }

        return Inertia::render('Dashboard');
    }
}