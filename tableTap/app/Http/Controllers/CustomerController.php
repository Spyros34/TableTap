<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{

    public function associateCustomerTable(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'table_id'    => 'required|exists:tables,id',
    ]);

    try {
        // Attach the customer to the table
        DB::table('customer_table')->updateOrInsert(
            [
                'customer_id' => $validated['customer_id'],
                'table_id'    => $validated['table_id'],
            ],
            [
                'created_at' => now(), // Only used if it's a new record
                'updated_at' => now(),
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Customer assigned to table successfully.',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to assign customer to table.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
    public function getCreditCards(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = Customer::find($request->input('customer_id'));

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Customer not found.',
            ], 404);
        }

        // Parse the string into an array (assuming comma-separated cards)
        $creditCards = $customer->credit_card
            ? explode(',', $customer->credit_card)
            : [];

        return response()->json([
            'status' => 'success',
            'credit_cards' => $creditCards,
        ]);
    }

    public function placeOrder(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id'   => 'required|exists:customers,id',
            'shop_id'       => 'required|exists:shops,id',
            'total_price'   => 'required|numeric|min:0',
            'payment_method'=> 'required|in:Cash,Card',
            'selected_card' => 'nullable|required_if:payment_method,Card|string',
            'items'         => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);
    
        try {
            DB::transaction(function () use ($validatedData) {
                // Create the Order with Payment Method
                $order = Order::create([
                    'customer_id'   => $validatedData['customer_id'],
                    'status'        => 'pending',
                    'payment_method'=> $validatedData['payment_method'], 
                ]);
    
                // Create Order Items
                foreach ($validatedData['items'] as $item) {
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item['product_id'],
                        'amount'     => $item['quantity'],
                    ]);
                }

                 // Subtract the ordered quantity from the product's available quantity
                $product = Product::find($item['product_id']);
                if ($product) {
                    $newQuantity = $product->quantity - $item['quantity'];
                        $product->update(['quantity' => $newQuantity]);
                }
    
                // Get all waiters assigned to the shop
                $waiters = DB::table('shop_waiter')
                    ->where('shop_id', $validatedData['shop_id'])
                    ->pluck('waiter_id');
    
                // Assign each waiter to the order
                foreach ($waiters as $waiterId) {
                    DB::table('waiter_order')->insert([
                        'waiter_id' => $waiterId,
                        'order_id'  => $order->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    
            return response()->json([
                'status'  => 'success',
                'message' => 'Order placed successfully!',
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to place order. Please try again.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}