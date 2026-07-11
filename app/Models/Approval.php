<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'approver_id', 'role_approver', 'aksi', 'catatan'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}