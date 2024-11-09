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
        // Fetch all kitchens from the database
        $kitchens = Kitchen::all();

        // Pass the kitchens to the Inertia view
        return Inertia::render('Owner/Kitchen', [
            'kitchenItems' => $kitchens
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
    // Validate the input data 
    $validatedData = $request->validate([
        'name' => ['required', 'string', 'max:255', 'unique:kitchens,name'],
        'password' => ['required', 'string', 'confirmed', 'min:8'],
    ]);

    // Retrieve the current shop based on the authenticated user
    $user = Auth::user();
    $shop = $user->shops()->first(); // Adjust as needed if a user has multiple shops

    if (!$shop) {
        return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
    }

    // Create the new kitchen
    $kitchen = Kitchen::create([
        'name' => $validatedData['name'],
        'password' => Hash::make($validatedData['password']),
    ]);

    // Associate the new kitchen with the user's shop and add created_at timestamp
    $shop->kitchens()->attach($kitchen->id, ['created_at' => now()]);

    // Return a JSON response to Inertia
    return Redirect::back()->with([
        'flash' => ['success' => 'Kitchen created successfully.'],
        'newKitchen' => $kitchen,
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
        
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        if ($request->has('name')) {
            $kitchen->name = $validatedData['name'];
        }
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
    
        // Detach the kitchen from all associated shops
        $kitchen->shops()->detach();
    
        // Delete the kitchen
        $kitchen->delete();
    
        // Redirect back to the kitchen index with a flash success message
        return back()->with('flash', ['success' => 'Kitchen deleted successfully.']);
    }
    


}
