<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Inertia\Inertia;
use App\Models\Table;
use Illuminate\Http\Request;
use Zxing\Qrcode\Decoder\Decoder;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Support\Facades\Storage;
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
        $qrCodeData = $request->input('qr_code_data');
    
        // Decode QR Code data and find the shop and table
        parse_str(parse_url($qrCodeData, PHP_URL_QUERY), $params);
    
        $tableId = $params['id'] ?? null;
        $shopId = $params['shop_id'] ?? null;
    
        if (!$tableId || !$shopId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid QR Code data.',
            ], 400);
        }
    
        $shop = Shop::find($shopId);
        $table = Table::find($tableId);
    
        if (!$shop || !$table) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop or Table not found.',
            ], 404);
        }
    
        return response()->json([
            'status' => 'success',
            'shop' => [
                'name' => $shop->name,
                'location' => $shop->location,
                'phone' => $shop->phone,
            ],
        ]);
    }

    public function scanQR(Request $request)
    {
        $shopId = $request->input('shop_id');
        $tableId = $request->input('table_id'); // Ensure this matches the Flutter app input.
    
        // Validate the input
        if (empty($shopId) || empty($tableId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing shop_id or table_id.',
            ], 400);
        }
    
        // Find the shop
        $shop = Shop::find($shopId);
        if (!$shop) {
            return response()->json([
                'status' => 'error',
                'message' => 'Shop not found.',
            ], 404);
        }
    
        // Find the table by ID using the shop_table_association pivot table
        $table = Table::where('id', $tableId)
            ->whereHas('shop', function ($query) use ($shopId) {
                $query->where('shop_table_association.shop_id', $shopId);
            })->first();
    
        if (!$table) {
            return response()->json([
                'status' => 'error',
                'message' => 'Table not found for the specified shop.',
            ], 404);
        }
    
        // Return the shop and table details
        return response()->json([
            'status' => 'success',
            'shop' => [
                'name' => $shop->brand,
                'location' => $shop->address,
                'phone' => $shop->phone_number,
            ],
            'table' => [
                'table_num' => $table->table_num,
            ],
        ]);
    }
}