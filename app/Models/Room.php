<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ruangan', 'kode_ruangan', 'jenis', 'lantai', 
        'kapasitas', 'deskripsi', 'foto', 'status'
    ];

    public function facilities()
    {
        return $this->hasMany(RoomFacility::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}