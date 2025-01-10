<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $owner = Auth::user();
    
        // Redirect if no shops are associated with the owner
        if ($owner->shops()->count() === 0) {
            return redirect()->route('create-shop');
        }
    
        // Fetch shops with related data
        $shops = $owner->shops()->with(['tables.customers.orders', 'waiters', 'kitchens'])->get();
    
        // Log fetched shops for debugging
        \Log::info('Shops Fetched:', $shops->toArray());
    
        $shopCount = $shops->count();
    
        $staffCount = $shops->sum(function ($shop) {
            return $shop->waiters->count() + $shop->kitchens->count();
        });
    
        $pendingOrdersCount = $shops->reduce(function ($total, $shop) {
            return $total + $shop->tables->flatMap(function ($table) {
                return $table->customers->flatMap(function ($customer) {
                    return $customer->orders->where('status', 'pending');
                });
            })->count();
        }, 0);
    

        return Inertia::render('Owner/Dashboard', [
            'dashboardData' => [
                'shopCount' => $shopCount,
                'staffCount' => $staffCount,
                'pendingOrdersCount' => $pendingOrdersCount,
            ],
        ]);
    }
}