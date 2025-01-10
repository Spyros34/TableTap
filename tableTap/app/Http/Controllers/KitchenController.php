<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Kitchen;
use App\Models\Shop;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;


class KitchenController extends Controller
{
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Get the first shop associated with the user
        $shop = $user->shops()->first();

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        // Get kitchens associated with the shop
        $kitchens = $shop->kitchens;

        // Pass the kitchens to the Inertia view
        return Inertia::render('Owner/Kitchen', [
            'kitchenItems' => $kitchens,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
{
    $user = Auth::user();
    $shop = $user->shops()->first();

    if (!$shop) {
        return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
    }

    $validatedData = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) use ($shop) {
                if (Kitchen::where('name', $value)->where('shop_id', $shop->id)->exists()) {
                    $fail("The $attribute has already been taken for this shop.");
                }
            },
        ],
        'password' => ['required', 'string', 'confirmed', 'min:8'],
    ]);

    $kitchen = Kitchen::create([
        'name' => $validatedData['name'],
        'password' => Hash::make($validatedData['password']),
        'shop_id' => $shop->id,
    ]);

    return Redirect::back()->with([
        'flash' => ['success' => 'Kitchen created successfully.'],
        'kitchenItem' => $kitchen, // Include the created kitchen in the response
    ]);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kitchen = Kitchen::findOrFail($id);
        
        // Validate the input data
        $validatedData = $request->validate([
            'name' => [
                'nullable',
                'string',
                'max:255',
                // Ensures the name is unique, excluding the current kitchen's ID
                'unique:kitchens,name,' . $kitchen->id
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Check if the name field is provided and update
        if ($request->filled('name')) {
            $kitchen->name = $validatedData['name'];
        }
        
        // Check if the password field is provided and update
        if ($request->filled('password')) {
            $kitchen->password = Hash::make($validatedData['password']);
        }
        
        $kitchen->save();
        
        return back()->with('flash', ['success' => 'Kitchen updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kitchen = Kitchen::findOrFail($id);
    
        // Delete the kitchen directly
        $kitchen->delete();
    
        // Redirect back to the kitchen index with a success message
        return back()->with('flash', ['success' => 'Kitchen deleted successfully.']);
    }
    


}
