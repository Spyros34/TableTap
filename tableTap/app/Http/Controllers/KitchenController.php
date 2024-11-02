<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Kitchen;
use Illuminate\Http\Request; 
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
    $validatedData = $request->validate([
        'name' => ['required', 'string', 'max:255', 'unique:kitchens,name'],
        'password' => ['required', 'string', 'confirmed', 'min:8'],
    ]);

    // Create the new kitchen
    $kitchen = Kitchen::create([
        'name' => $validatedData['name'],
        'password' => Hash::make($validatedData['password']),
    ]);

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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kitchen = Kitchen::findOrFail($id);
        $kitchen->delete();
    
        // Redirect back to the kitchen index with a flash success message
        return Redirect::route('kitchen')->with('flash', ['success' => 'Kitchen item deleted successfully.']);
    }
    


}
