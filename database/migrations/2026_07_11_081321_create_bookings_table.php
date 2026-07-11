<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('mk_id')->nullable()->constrained('mata_kuliah')->onDelete('set null');
            $table->enum('kategori', ['perkuliahan', 'praktikum', 'rapat', 'seminar', 'lainnya']);
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('tujuan');
            $table->integer('jumlah_peserta');
            $table->string('file_surat')->nullable();
            $table->enum('status', ['pending_dosen', 'pending_admin', 'approved', 'rejected', 'cancelled'])->default('pending_dosen');
            $table->text('alasan_tolak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};