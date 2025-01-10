<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaiterManagerController extends Controller
{
    /**
     * Show all orders for the waiter's shops.
     */
    /**
     * Show all orders for the waiter's shops.
     */
    public function index()
    {
        $waiter = Auth::user(); // or Auth::guard('waiter')->user();
        if (!$waiter) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized']);
        }

        // The shop IDs the Waiter belongs to
        $shopIds = $waiter->shops->pluck('id');

        // Fetch all orders except 'completed' ones, including the customer and table relationships
        $orders = Order::where('status', '!=', 'completed')
            ->whereHas('customer.table', function ($query) use ($shopIds) {
                $query->whereHas('shop', function ($sq) use ($shopIds) {
                    $sq->whereIn('shops.id', $shopIds);
                });
            })
            ->with(['customer.table', 'orderItems.product'])
            ->get();

        return Inertia::render('Waiter/Dashboard', [
            'orders' => $orders,
        ]);
    }

    /**
     * Mark an order as completed.
     */
    public function markOrderAsCompleted($id)
    {
        $waiter = Auth::user();
        if (!$waiter) {
            return back()->withErrors(['error' => 'Unauthorized']);
        }

        $shopIds = $waiter->shops->pluck('id');

        // Ensure the order belongs to the waiter's shops
        $order = Order::where('id', $id)
            ->whereHas('customer.table', function ($query) use ($shopIds) {
                $query->whereHas('shop', function ($shopQuery) use ($shopIds) {
                    $shopQuery->whereIn('shops.id', $shopIds);
                });
            })
            ->firstOrFail();

        // Update the order status to 'completed' in the DB
        $order->update(['status' => 'completed']);

        return back()->with('success', 'Order marked as completed.');
    }
}