<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomFacility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        User::create([
            'nama_lengkap' => 'Admin TU FKOM',
            'email' => 'admin@uniku.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'no_hp' => '081234567890',
            'is_active' => true,
        ]);

        User::create([
            'nama_lengkap' => 'Ahmad Mahasiswa',
            'email' => 'ahmad@uniku.ac.id',
            'password' => Hash::make('mhs123'),
            'role' => 'mahasiswa_pjmk',
            'nim' => '202401001',
            'no_hp' => '081234567893',
            'is_active' => true,
        ]);

        // Rooms
        $room1 = Room::create([
            'nama_ruangan' => 'Ruang Kelas A',
            'kode_ruangan' => 'R001',
            'jenis' => 'kelas',
            'lantai' => 1,
            'kapasitas' => 30,
            'deskripsi' => 'Ruang kelas untuk perkuliahan umum',
            'status' => 'tersedia',
        ]);

        $room2 = Room::create([
            'nama_ruangan' => 'Lab Komputer 1',
            'kode_ruangan' => 'LAB01',
            'jenis' => 'lab',
            'lantai' => 2,
            'kapasitas' => 40,
            'deskripsi' => 'Laboratorium komputer dengan 40 unit PC',
            'status' => 'tersedia',
        ]);

        // Facilities
        RoomFacility::create(['room_id' => $room1->id, 'nama_fasilitas' => 'Proyektor']);
        RoomFacility::create(['room_id' => $room1->id, 'nama_fasilitas' => 'AC']);
        RoomFacility::create(['room_id' => $room2->id, 'nama_fasilitas' => '40 Unit PC']);
        RoomFacility::create(['room_id' => $room2->id, 'nama_fasilitas' => 'LAN']);
    }
}