<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_booking', 'user_id', 'room_id', 'mk_id', 'kategori', 
        'tanggal', 'jam_mulai', 'jam_selesai', 'tujuan', 
        'jumlah_peserta', 'file_surat', 'status', 'alasan_tolak'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mk_id');
    }
}