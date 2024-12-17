<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class QRCodeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shop = $user->shops()->first();

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        // Get all tables associated with the shop
        $tables = $shop->tables()->get();

        return Inertia::render('Owner/QRCode', [
            'tableItems' => $tables,
        ]);
    }
}
