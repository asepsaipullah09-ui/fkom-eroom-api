<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('facilities')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar ruangan',
            'data' => $rooms
        ], 200);
    }

    public function show($id)
    {
        $room = Room::with('facilities')->find($id);
        
        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Ruangan tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail ruangan',
            'data' => $room
        ], 200);
    }
}