<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Table;
use Illuminate\Http\Request;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Redirect;
use Endroid\QrCode\Builder\Builder; // Import the Builder
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;

class TableController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $shop = $user->shops()->first();

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        // Get all tables associated with the shop
        $tables = $shop->tables();

        return Inertia::render('Owner/QRCode', [
            'tableItems' => $tables,
        ]);
    }
    /**
     * Store a newly created table and generate its QR code.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'table_num' => 'required|string|max:255|unique:tables,table_num',
        ]);

        // Get the authenticated user and their shop
        $user = Auth::user();
        $shop = $user->shops()->first();

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        // Create the table
        $table = Table::create([
            'table_num' => $validatedData['table_num'],
        ]);

        // Attach the table to the shop via the pivot table
        $shop->tables()->attach($table->id, ['created_at' => now()]);

        // Generate the QR code data (URL with table and shop IDs)
        $qrCodeData = route('table.scan', ['id' => $table->id, 'shop_id' => $shop->id]);

        // Define the path to save the QR code image
        $qrCodeRelativePath = 'qrcodes/table_' . $table->table_num . '.png';
        $qrCodeAbsolutePath = public_path($qrCodeRelativePath);

        // Generate the QR code using the Builder
        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($qrCodeData)
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->margin(10)
            ->build();

        // Save the QR code image to the file system
        $result->saveToFile($qrCodeAbsolutePath);

        // Update the table with the QR code image path
        $table->qr_code = $qrCodeRelativePath;
        $table->save();

       // Redirect back to the index with success message
        return Redirect::route('qrcode')->with('success', 'Table created successfully.');
    }

    public function update(Request $request, $id)
{
    $user = Auth::user();
    $shop = $user->shops()->first();

    if (!$shop) {
        return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
    }

    $table = Table::findOrFail($id);

    if (!$shop->tables()->where('tables.id', $id)->exists()) {
        return Redirect::back()->withErrors(['error' => 'This table does not belong to your shop.']);
    }

    $validatedData = $request->validate([
        'table_num' => 'required|string|max:255|unique:tables,table_num,' . $table->id,
    ]);

    if ($validatedData['table_num'] !== $table->table_num) {
        $qrCodeData = route('table.scan', ['id' => $table->id, 'shop_id' => $shop->id]);
        $qrCodeRelativePath = 'qrcodes/table_' . $validatedData['table_num'] . '.png';
        $qrCodeAbsolutePath = public_path($qrCodeRelativePath);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($qrCodeData)
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->margin(10)
            ->build();

        $result->saveToFile($qrCodeAbsolutePath);

        if ($table->qr_code && file_exists(public_path($table->qr_code))) {
            unlink(public_path($table->qr_code));
        }

        $validatedData['qr_code'] = $qrCodeRelativePath;
    }

    $table->update($validatedData);

    return Redirect::back()->with('success', 'Table updated successfully.')
        ->with('updatedTable', $table); // Pass updated table
}

    public function destroy($id)
    {
        $user = Auth::user();
        $shop = $user->shops()->first();

        if (!$shop) {
            return Redirect::back()->withErrors(['error' => 'No shop associated with this user.']);
        }

        $table = Table::findOrFail($id);

        // Ensure the table is associated with the shop
        if (!$shop->tables()->where('tables.id', $id)->exists()) {
            return Redirect::back()->withErrors(['error' => 'This table does not belong to your shop.']);
        }

        // Detach the table from the shop and delete it
        $shop->tables()->detach($table->id);
        $table->delete();

        return Redirect::route('qrcode')->with('success', 'Table deleted successfully.');
    }


    /**
     * Serve the QR code image for a specific table.
     */
    public function showQRCodeImage($id)
    {
        $table = Table::findOrFail($id);
    
        if ($table->qr_code && file_exists(public_path($table->qr_code))) {
            // Extract the updated table number from the database
            $updatedFileName = 'qrcode_table_' . $table->table_num . '.png';
    
            // Return the file as a download with the updated filename
            return response()->download(public_path($table->qr_code), $updatedFileName);
        } else {
            abort(404, 'QR Code not found.');
        }
    }

    /**
     * Handle scanning of the QR code.
     */
    public function scan(Request $request)
    {
        $tableId = $request->query('id');
        $shopId = $request->query('shop_id');

        // Placeholder logic; adjust according to your mobile app integration
        return response()->json([
            'message' => 'QR code scanned successfully.',
            'table_id' => $tableId,
            'shop_id' => $shopId,
        ]);
    }
}