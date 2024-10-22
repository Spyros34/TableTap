<?php
namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Kitchen;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

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
        //
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
    
       // Use standard Laravel flash messaging
       // Return a JSON response for successful deletion
        return response()->json(['success' => 'Kitchen item deleted successfully.']);
       
    }
    


}
