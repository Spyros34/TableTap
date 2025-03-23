<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the user's shop (assuming a user has one shop)
        $shop = $user->shops()->first();

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        // Get all products associated with the shop
        $products = $shop->products;

        return Inertia::render('Owner/Products', [
            'productItems' => $products,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */


     public function store(Request $request)
     {
        // Validate the request data
        $validatedData = $request->validate([
            'name'         => 'required|string|max:255|unique:products,name',
            'price'        => 'required|numeric|min:0',
            'quantity'     => 'required|integer|min:0',
            'availability' => 'required|boolean',
        ]);

        // Get the authenticated user and their shop
        $user = Auth::user();
        $shop = $user->shops()->first();

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        // Create the product
        $product = Product::create($validatedData);

        // Attach the product to the shop
        $shop->products()->attach($product->id, ['created_at' => now()]);

        // Return back with flash message and new product data
        return Redirect::back()->with([
            'flash'      => ['success' => 'Product created successfully.'],
            'newProduct' => $product,
        ]);
     }
 

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);
    
        // Get the authenticated user and their shop
        $user = Auth::user();
        $shop = $user->shops()->first();
    
        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }
    
        // Validate the request data
        $validatedData = $request->validate([
            'name' => [
                'nullable',
                'string',
                'max:255',
                // Ensure unique names only within the current shop
                function ($attribute, $value, $fail) use ($shop, $product) {
                    $existingProduct = $shop->products()
                        ->where('name', $value)
                        ->where('products.id', '!=', $product->id) // Specify table name
                        ->first();
                    if ($existingProduct) {
                        $fail('The product name already exists in your shop.');
                    }
                },
            ],
            'price'        => 'nullable|numeric|min:0',
            'quantity'     => 'nullable|integer|min:0',
            'availability' => 'nullable|boolean',
        ]);
    
        // Update product fields if provided
        if ($request->filled('name')) {
            $product->name = $validatedData['name'];
        }
        if ($request->filled('price')) {
            $product->price = $validatedData['price'];
        }
        if ($request->filled('quantity')) {
            $product->quantity = $validatedData['quantity'];
        }
        if ($request->filled('availability')) {
            $product->availability = $validatedData['availability'];
        }
    
        // Save the changes
        $product->save();
    
        // Return back with flash message
        return Redirect::back()->with('flash', ['success' => 'Product updated successfully.']);
    }

    public function getProducts(Request $request)
{
    $shopId = $request->input('shop_id');

    if (!$shopId) {
        return response()->json([
            'status' => 'error',
            'message' => 'Missing shop_id.',
        ], 400);
    }

    $shop = Shop::find($shopId);

    if (!$shop) {
        return response()->json([
            'status' => 'error',
            'message' => 'Shop not found.',
        ], 404);
    }

    // Explicitly specify the table name for the `id` field to avoid ambiguity
    $products = $shop->products()
        ->select([
            'products.id as product_id', // Specify `products.id` and alias it
            'products.name',
            'products.price',
            'products.quantity',
            'products.availability',
            'products.description'
        ])
        ->get();

    return response()->json([
        'status' => 'success',
        'products' => $products,
    ]);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);
    
        // Detach the product from all associated shops
        $product->shops()->detach(); // Use 'shops()' instead of 'shop()'
    
        // Delete the product
        $product->delete();
    
        // Return back with a success message
        return Redirect::back()->with('flash', [
            'success' => 'Product deleted successfully.',
        ]);
    }
}