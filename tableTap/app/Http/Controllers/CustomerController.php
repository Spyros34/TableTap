<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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

    public function addCard(Request $request)
{
    try {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            // Card number must be a string containing only digits, with a length between 13 and 19.
            'card_number' => ['required', 'string', 'regex:/^\d{13,19}$/'],
        ]);

        $customer = Customer::find($validated['customer_id']);
        $existingCards = $customer->credit_card ? explode(',', $customer->credit_card) : [];

        if (in_array($validated['card_number'], $existingCards)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Card already exists.',
            ], 400);
        }

        $existingCards[] = $validated['card_number'];
        $customer->credit_card = implode(',', $existingCards);
        $customer->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Card added successfully.',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Validation failed.',
            'errors'  => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

public function deleteCard(Request $request)
{
    try {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'card_number' => 'required|string',
        ]);

        $customer = Customer::find($validated['customer_id']);
        $existingCards = $customer->credit_card ? explode(',', $customer->credit_card) : [];

        if (!in_array($validated['card_number'], $existingCards)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Card not found.',
            ], 404);
        }

        // Prevent deletion if only one card exists
        if (count($existingCards) <= 1) {
            return response()->json([
                'status'  => 'error',
                'message' => 'At least one card must remain.',
            ], 400);
        }

        $updatedCards = array_filter($existingCards, function ($card) use ($validated) {
            return $card != $validated['card_number'];
        });
        $customer->credit_card = implode(',', $updatedCards);
        $customer->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Card deleted successfully.',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Validation failed.',
            'errors'  => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
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

    public function getProfile(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
    ]);

    $customer = Customer::find($validated['customer_id']);

    return response()->json([
        'status' => 'success',
        'customer' => $customer,
    ]);
}

public function updateProfile(Request $request)
{
    try {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name'        => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'surname'     => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]*$/'],
            'username'    => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9\.\-]+$/'],
            'email'       => 'required|email|max:255',
            'address'     => 'nullable|string|max:255',
            'city'        => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'region'      => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z\s]+$/'],
            'tk'          => ['nullable', 'string', 'max:10', 'regex:/^\d+$/'],
        ]);

        $customer = Customer::find($validated['customer_id']);

        $customer->update([
            'name'     => $validated['name'],
            'surname'  => $validated['surname'] ?? $customer->surname,
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'address'  => $validated['address'] ?? $customer->address,
            'city'     => $validated['city'] ?? $customer->city,
            'region'   => $validated['region'] ?? $customer->region,
            'tk'       => $validated['tk'] ?? $customer->tk,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Profile updated successfully.',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Validation failed.',
            'errors'  => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        // Optionally log the exception for debugging
        // Log::error($e);
        return response()->json([
            'status'  => 'error',
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

public function changePassword(Request $request)
{
    try {
        $validated = $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8',
        ]);

        $customer = Customer::find($validated['customer_id']);

        // Check if the current password is correct
        if (!Hash::check($validated['current_password'], $customer->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Current password is incorrect.',
            ], 400);
        }

        // Update the password after hashing the new password
        $customer->password = Hash::make($validated['new_password']);
        $customer->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Password changed successfully.',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Validation failed.',
            'errors'  => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'An error occurred: ' . $e->getMessage(),
        ], 500);
    }
}


}