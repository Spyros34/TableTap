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

        // Fetch the associated shop using the updated shopRelation method
        $shop = $kitchenUser->shopRelation;

        if (!$shop) {
            return redirect()->route('login.kitchen')->withErrors(['error' => 'No shop associated with this kitchen user.']);
        }

        $shopId = $shop->id;

        // Fetch products related to the shop
        $products = Product::whereIn('id', function ($query) use ($shopId) {
            $query->select('product_id')
                ->from('shop_product')
                ->where('shop_id', $shopId);
        })->get();

        // Fetch only pending orders related to the shop
        $orders = Order::where('status', 'pending')
            ->whereHas('customer.table', function ($query) use ($shopId) {
                $query->whereHas('shop', function ($shopQuery) use ($shopId) {
                    $shopQuery->where('shops.id', $shopId);
                });
            })
            ->with(['orderItems.product', 'customer'])
            ->get();

        // Return the Inertia view with products and pending orders
        return Inertia::render('Kitchen/Dashboard', [
            'products' => $products,
            'orders'   => $orders,
        ]);
    }
    
    /**
     * Reload pending orders.
     */
    public function reloadOrders()
    {
        $kitchenUser = Auth::guard('kitchen')->user();

        if (!$kitchenUser) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $shop = $kitchenUser->shopRelation;

        if (!$shop) {
            return response()->json(['error' => 'No shop associated with this kitchen user.'], 403);
        }

        $shopId = $shop->id;

        $orders = Order::where('status', 'pending')
            ->whereHas('customer.table', function ($query) use ($shopId) {
                $query->whereHas('shop', function ($shopQuery) use ($shopId) {
                    $shopQuery->where('shops.id', $shopId);
                });
            })
            ->with(['orderItems.product', 'customer'])
            ->get();

        return response()->json(['orders' => $orders]);
    }

 /**
     * Get orders with items.
     */
    public function getOrdersWithItems()
    {
        $kitchenUser = Auth::guard('kitchen')->user();

        if (!$kitchenUser) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }

        $shop = $kitchenUser->shopRelation;

        if (!$shop) {
            return response()->json(['error' => 'No shop associated with this kitchen user.'], 403);
        }

        $shopId = $shop->id;

        $productIds = DB::table('shop_product')
            ->where('shop_id', $shopId)
            ->pluck('product_id');

        $orderItemIds = DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->pluck('id');

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

        if (!$kitchenUser) {
            return back()->withErrors(['error' => 'Unauthorized access.']);
        }

        $shop = $kitchenUser->shopRelation;

        if (!$shop) {
            return back()->withErrors(['error' => 'No shop associated with this kitchen user.']);
        }

        $shopId = $shop->id;

        $isProductAssociated = Product::where('id', $id)
            ->whereIn('id', function ($query) use ($shopId) {
                $query->select('product_id')
                    ->from('shop_product')
                    ->where('shop_id', $shopId);
            })
            ->exists();

        if (!$isProductAssociated) {
            return back()->withErrors(['error' => 'Unauthorized action.']);
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

        if (!$kitchenUser) {
            return back()->withErrors(['error' => 'Unauthorized access.']);
        }

        $shop = $kitchenUser->shopRelation;

        if (!$shop) {
            return back()->withErrors(['error' => 'No shop associated with this kitchen user.']);
        }

        $shopId = $shop->id;

        $isOrderAssociated = Order::where('id', $id)
            ->whereHas('customer.table', function ($query) use ($shopId) {
                $query->whereHas('shop', function ($shopQuery) use ($shopId) {
                    $shopQuery->where('shops.id', $shopId);
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