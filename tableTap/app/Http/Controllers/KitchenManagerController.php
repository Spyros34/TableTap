<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KitchenManagerController extends Controller
{
    /**
     * Display products and orders for the kitchen.
     */
    public function index()
    {
        $kitchenUser = Auth::guard('kitchen')->user();
    
        if (!$kitchenUser) {
            return redirect()->route('login.kitchen')->withErrors(['error' => 'Unauthorized access.']);
        }
    
        $shopIds = $kitchenUser->shops->pluck('id');
    
        // Fetch products related to the kitchen's shop
        $products = Product::whereIn('id', function ($query) use ($shopIds) {
            $query->select('product_id')
                  ->from('shop_product')
                  ->whereIn('shop_id', $shopIds);
        })->get();
    
        // Fetch only pending orders
        $orders = Order::where('status', 'pending')
            ->whereHas('customer.table', function ($query) use ($shopIds) {
                $query->whereHas('shop', function ($shopQuery) use ($shopIds) {
                    $shopQuery->whereIn('shops.id', $shopIds);
                });
            })
            ->with(['orderItems.product', 'customer'])
            ->get();
    
        // Return the Inertia view with only pending orders
        return Inertia::render('Kitchen/Dashboard', [
            'products' => $products,
            'orders'   => $orders,
        ]);
    }
    
    /**
     * Optional helper if you want a dedicated route to refetch pending orders only:
     */
    public function reloadOrders()
    {
        $kitchenUser = Auth::guard('kitchen')->user();
    
        if (!$kitchenUser) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }
    
        $shopIds = $kitchenUser->shops->pluck('id');
    
        $orders = Order::where('status', 'pending')
            ->whereHas('customer.table', function ($query) use ($shopIds) {
                $query->whereHas('shop', function ($shopQuery) use ($shopIds) {
                    $shopQuery->whereIn('shops.id', $shopIds);
                });
            })
            ->with(['orderItems.product', 'customer'])
            ->get();
    
        // Return JSON for a partial reload
        return response()->json([
            'orders' => $orders,
        ]);
    }

public function getOrdersWithItems()
{
    $kitchenUser = Auth::guard('kitchen')->user();

    if (!$kitchenUser) {
        return response()->json(['error' => 'Unauthorized access.'], 403);
    }

    // Step 1: Find the shop IDs the kitchen user is associated with
    $shopIds = $kitchenUser->shops->pluck('id');

    // Step 2: Get all product IDs from shop_product for these shop IDs
    $productIds = DB::table('shop_product')
        ->whereIn('shop_id', $shopIds)
        ->pluck('product_id');

    // Step 3: Get all order item IDs where product_id matches
    $orderItemIds = DB::table('order_items')
        ->whereIn('product_id', $productIds)
        ->pluck('id');

    // Step 4: Get all orders that match the retrieved order item IDs
    $orders = Order::whereIn('id', function ($query) use ($orderItemIds) {
        $query->select('order_id')
            ->from('order_items')
            ->whereIn('id', $orderItemIds);
    })
        ->with(['customer', 'orderItems.product'])
        ->get();

    return response()->json($orders);
}

    /**
     * Update product details (quantity or availability).
     */
    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:0',
            'availability' => 'nullable|boolean',
        ]);
    
        $kitchenUser = Auth::guard('kitchen')->user();
        $shopIds = $kitchenUser->shops->pluck('id');
    
        // Validate product association with the shop
        $isProductAssociated = Product::where('id', $id)
            ->whereIn('id', function ($query) use ($shopIds) {
                $query->select('product_id')
                    ->from('shop_product')
                    ->whereIn('shop_id', $shopIds);
            })
            ->exists();
    
        if (!$isProductAssociated) {
            return back()->with('error', 'Unauthorized action.');
        }
    
        $product = Product::findOrFail($id);
        $product->update($validated);
    
        return back()->with('success', 'Product updated successfully.');
    }
    /**
     * Mark an order as ready.
     */
    public function markOrderAsReady($id)
    {
        $kitchenUser = Auth::guard('kitchen')->user();
        $shopIds = $kitchenUser->shops->pluck('id');

        $isOrderAssociated = Order::where('id', $id)
            ->whereHas('customer.table', function ($query) use ($shopIds) {
                $query->whereHas('shop', function ($shopQuery) use ($shopIds) {
                    $shopQuery->whereIn('shops.id', $shopIds);
                });
            })
            ->exists();

        if (!$isOrderAssociated) {
            return back()->withErrors(['error' => 'Unauthorized action.']);
        }

        $order = Order::findOrFail($id);
        $order->update(['status' => 'ready']);

        return back()->with('success', 'Order marked as ready.');
    }

   
}