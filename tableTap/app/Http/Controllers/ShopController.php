<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function create()
    {
        return Inertia::render('CreateShop');
    }

    public function store(Request $request)
{
    $request->validate([
        'storeName' => ['required', 'string', 'max:255', 'unique:shops,brand'],
        'storeType' => ['required', 'string', 'max:255'],
        'address' => ['required', 'string', 'max:255'],
        'region' => ['required', 'string', 'max:255'],
        'city' => ['required', 'string', 'max:255'],
        'postalCode' => ['required', 'string', 'max:20'],
        'phone' => ['required', 'string', 'max:20'],
    ]);

    $shop = Shop::create([
        'brand' => $request->storeName,
        'type' => $request->storeType,
        'address' => $request->address,
        'region' => $request->region,
        'city' => $request->city,
        'tk' => $request->postalCode,
        'phone_number' => $request->phone,
        'owner_id' => Auth::id(),
    ]);

    $owner = Auth::user();
    $owner->shops()->attach($shop->id);

    return redirect()->route('dashboard')->with('success', 'Shop created successfully.');
}

    public function list()
    {
        try {
            $shops = Shop::all(['id', 'brand']); // Fetch 'brand' instead of 'name'
            return response()->json(['shops' => $shops], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching shops: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch shops.'], 500);
        }
    }

    public function getShops(Request $request)
    {
        try {
            // Eager-load products for each shop
            $shops = Shop::with('products')->get();

            return response()->json([
                'status' => 'success',
                'shops'  => $shops,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch shops.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}