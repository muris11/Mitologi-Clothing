<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RfidController extends Controller
{
    /**
     * Display a listing of RFID scans
     */
    public function index()
    {
        return view('admin.rfids.index');
    }

    /**
     * Display the specified RFID scan
     */
    public function show($id)
    {
        return view('admin.rfids.show', ['id' => $id]);
    }

    /**
     * Get latest RFID scan status (API endpoint for polling)
     */
    public function apiStatus()
    {
        // Mock data for now - in production, this would query the database
        $scans = [
            [
                'id' => 1,
                'uid' => 'A1B2C3D4',
                'status' => 'unassigned',
                'user_id' => null,
                'last_scanned_at' => now()->subSeconds(15)->toIso8601String(),
            ],
            [
                'id' => 2,
                'uid' => 'E5F6G7H8',
                'status' => 'assigned',
                'user_id' => 1,
                'last_scanned_at' => now()->subMinutes(2)->toIso8601String(),
            ],
            [
                'id' => 3,
                'uid' => 'I9J0K1L2',
                'status' => 'unassigned',
                'user_id' => null,
                'last_scanned_at' => now()->subMinutes(5)->toIso8601String(),
            ],
        ];

        return response()->json([
            'scans' => $scans,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
