<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TaniLinkSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya inisialisasi akun Administrator utama jika belum ada
        User::firstOrCreate(
            ['email' => 'admin@tanilink.id'],
            [
                'name' => 'Admin TaniLink',
                'role' => 'admin',
                'phone' => '081234567890',
                'location' => 'Kantor Operasional TaniLink',
                'organization' => 'TaniLink Central Admin',
                'password' => Hash::make('admintanilink'),
            ]
        );
    }
}
