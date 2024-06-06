<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class QRCodeController extends Controller
{
    public function index()
    {
        return Inertia::render('Owner/QRCode');
    }
}
