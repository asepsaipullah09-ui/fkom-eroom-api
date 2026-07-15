<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'kategori' => 'required|in:perkuliahan,praktikum,rapat,seminar,lainnya',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tujuan' => 'required|string',
            'jumlah_peserta' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        
        // Tentukan status awal berdasarkan role
        $status = 'pending_admin'; 
        if ($user->role === 'mahasiswa_pjmk' || $user->role === 'mahasiswa_umum') {
            $status = 'pending_dosen';
        }

        $booking = Booking::create([
            'kode_booking' => 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id' => $user->id,
            'room_id' => $request->room_id,
            'mk_id' => $request->mk_id,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'tujuan' => $request->tujuan,
            'jumlah_peserta' => $request->jumlah_peserta,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => $booking
        ], 201);
    }

    public function myBookings(Request $request)
    {
        $bookings = Booking::with(['room', 'mataKuliah'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $bookings], 200);
    }

    public function allBookings(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $user->role !== 'dosen') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $bookings = Booking::with(['room', 'mataKuliah', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $bookings], 200);
    }

        public function approveBooking(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $user->role !== 'dosen') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        // LOGIKA BERURUTAN (STRICT)
        if ($user->role === 'dosen') {
            // Dosen hanya bisa approve jika status masih pending_dosen
            if ($booking->status !== 'pending_dosen') {
                return response()->json(['success' => false, 'message' => 'Booking tidak valid untuk disetujui Dosen'], 400);
            }
            $booking->update(['status' => 'pending_admin']);
        } 
        elseif ($user->role === 'admin') {
            // Admin hanya bisa approve jika Dosen sudah approve (status pending_admin)
            if ($booking->status !== 'pending_admin') {
                return response()->json(['success' => false, 'message' => 'Booking harus disetujui Dosen terlebih dahulu'], 400);
            }
            $booking->update(['status' => 'approved']);
        }

        // Buat Notifikasi
        Notification::create([
            'user_id' => $booking->user_id,
            'judul' => 'Update Booking',
            'pesan' => "Booking {$booking->kode_booking} telah disetujui oleh {$user->role}. Status sekarang: {$booking->status}",
            'booking_id' => $booking->id,
            'is_read' => false,
        ]);

        return response()->json(['success' => true, 'message' => 'Booking berhasil disetujui', 'data' => $booking->fresh()], 200);
    }

    public function rejectBooking(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role !== 'admin' && $user->role !== 'dosen') {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $validator = Validator::make($request->all(), ['alasan' => 'required|string']);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
        }

        $booking->update(['status' => 'rejected', 'alasan_tolak' => $request->alasan]);

        Notification::create([
            'user_id' => $booking->user_id,
            'judul' => 'Booking Ditolak',
            'pesan' => "Booking {$booking->kode_booking} ditolak. Alasan: {$request->alasan}",
            'booking_id' => $booking->id,
            'is_read' => false,
        ]);

        return response()->json(['success' => true, 'message' => 'Booking ditolak', 'data' => $booking->fresh()], 200);
    }
}