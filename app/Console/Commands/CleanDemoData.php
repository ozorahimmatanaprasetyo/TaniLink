<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CommodityListing;
use App\Models\PreOrder;
use App\Models\PlantingProgress;
use App\Models\DeliveryTracking;

class CleanDemoData extends Command
{
    protected $signature = 'tanilink:clean-demo';
    protected $description = 'Bersihkan data demo dan reset akun admin ke kondisi awal';

    public function handle()
    {
        $this->info('Memeriksa data database...');

        // 1. Hitung sebelum pembersihan
        $this->line('Users awal: ' . User::count());
        $this->line('Listings awal: ' . CommodityListing::count());
        $this->line('Orders awal: ' . PreOrder::count());

        // 2. Bersihkan progres dan tracking
        PlantingProgress::query()->delete();
        DeliveryTracking::query()->delete();

        // 3. Bersihkan pesanan
        PreOrder::query()->delete();

        // 4. Bersihkan daftar komoditas demo
        CommodityListing::query()->delete();

        // 5. Hapus akun demo petani dan konsumen (raden@tanilink.id, maya@grandhotel.com, dll yang bukan admin)
        User::where('role', '!=', 'admin')->delete();

        // 6. Pastikan akun admin ada, dan update passwordnya
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::where('email', 'admin@tanilink.id')->first();
        }

        if ($admin) {
            $admin->password = Hash::make('admintanilink');
            $admin->role = 'admin';
            $admin->name = 'Admin TaniLink';
            $admin->email = 'admin@tanilink.id';
            $admin->save();
            $this->info('Akun admin berhasil diupdate dengan email: ' . $admin->email);
        } else {
            User::create([
                'name' => 'Admin TaniLink',
                'email' => 'admin@tanilink.id',
                'role' => 'admin',
                'password' => Hash::make('admintanilink'),
            ]);
            $this->info('Akun admin baru berhasil dibuat: admin@tanilink.id');
        }

        $this->info('Pembersihan data demo selesai!');
        $this->line('Users tersisa: ' . User::count() . ' (hanya admin)');
        $this->line('Listings tersisa: ' . CommodityListing::count());
        $this->line('Orders tersisa: ' . PreOrder::count());

        return 0;
    }
}

