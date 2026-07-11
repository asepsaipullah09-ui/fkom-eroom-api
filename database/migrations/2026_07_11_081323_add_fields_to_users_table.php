<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_lengkap')->after('name');
            $table->enum('role', ['admin', 'dosen', 'mahasiswa_pjmk', 'mahasiswa_umum'])->default('mahasiswa_umum')->after('email');
            $table->string('nim')->nullable()->after('role');
            $table->string('nip')->nullable()->after('nim');
            $table->string('no_hp')->nullable()->after('nip');
            $table->boolean('is_active')->default(true)->after('no_hp');
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_lengkap', 'role', 'nim', 'nip', 'no_hp', 'is_active']);
            $table->string('name')->after('id');
        });
    }
};