<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CommodityListing;
use App\Models\PreOrder;
use App\Models\PlantingProgress;
use App\Models\DeliveryTracking;

class TaniLinkSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $petani = User::firstOrCreate(
            ['email' => 'raden@tanilink.id'],
            [
                'name' => 'Pak Raden',
                'role' => 'petani',
                'phone' => '081234567890',
                'location' => 'Ambarawa, Kab. Malang',
                'organization' => 'Gapoktan Ambarawa',
                'password' => Hash::make('password123'),
            ]
        );

        $pembeli = User::firstOrCreate(
            ['email' => 'maya@grandhotel.com'],
            [
                'name' => 'Maya Anggraini (Grand Hotel)',
                'role' => 'pembeli',
                'phone' => '082198765432',
                'location' => 'Kota Malang',
                'organization' => 'Grand Hotel Malang (Purchasing F&B)',
                'password' => Hash::make('password123'),
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@tanilink.id'],
            [
                'name' => 'Admin Operasional',
                'role' => 'admin',
                'phone' => '085600001111',
                'location' => 'Malang HQ',
                'organization' => 'TaniLink Central Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Commodity Listings (Input Awal Petani)
        $listingCabai = CommodityListing::create([
            'user_id' => $petani->id,
            'commodity_name' => 'Cabai Rawit Merah',
            'category' => 'Cabai & Bumbu',
            'variety' => 'Ori 212',
            'land_area_m2' => 2500,
            'estimated_harvest_kg' => 500,
            'available_kg' => 0,
            'price_per_kg' => 25000,
            'harvest_estimated_date' => '2026-08-24',
            'location' => 'Ambarawa, Malang',
            'image_url' => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=600&q=80',
            'description' => 'Lahan siap tanam varietas unggul tahan hama antraknosa.',
            'status' => 'booked',
        ]);

        $listingTomat = CommodityListing::create([
            'user_id' => $petani->id,
            'commodity_name' => 'Tomat Merah Unggul',
            'category' => 'Sayuran',
            'variety' => 'Servo F1',
            'land_area_m2' => 1800,
            'estimated_harvest_kg' => 300,
            'available_kg' => 50,
            'price_per_kg' => 12000,
            'harvest_estimated_date' => '2026-09-10',
            'location' => 'Candi, Malang',
            'image_url' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80',
            'description' => 'Tomat segar berdaging tebal cocok untuk salad dan saus hotel.',
            'status' => 'available',
        ]);

        $listingBawang = CommodityListing::create([
            'user_id' => $petani->id,
            'commodity_name' => 'Bawang Merah Super Probolinggo',
            'category' => 'Cabai & Bumbu',
            'variety' => 'Biru Lancor',
            'land_area_m2' => 4000,
            'estimated_harvest_kg' => 1000,
            'available_kg' => 600,
            'price_per_kg' => 28000,
            'harvest_estimated_date' => '2026-10-01',
            'location' => 'Dringu, Probolinggo',
            'image_url' => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?auto=format&fit=crop&w=600&q=80',
            'description' => 'Kualitas ekspor aroma kuat dan kadar air rendah.',
            'status' => 'available',
        ]);

        $listingJagung = CommodityListing::create([
            'user_id' => $petani->id,
            'commodity_name' => 'Jagung Manis Madu',
            'category' => 'Palawija',
            'variety' => 'Talenta',
            'land_area_m2' => 5000,
            'estimated_harvest_kg' => 2000,
            'available_kg' => 1200,
            'price_per_kg' => 8500,
            'harvest_estimated_date' => '2026-10-15',
            'location' => 'Pujon, Batu',
            'image_url' => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?auto=format&fit=crop&w=600&q=80',
            'description' => 'Biji manis padat bebas pestisida kimia berbahaya.',
            'status' => 'available',
        ]);

        // 3. Pre-Order Aktif (Sudah Dipesan Grand Hotel)
        $preOrder = PreOrder::create([
            'order_code' => 'TL-2026-0812',
            'commodity_listing_id' => $listingCabai->id,
            'farmer_id' => $petani->id,
            'buyer_id' => $pembeli->id,
            'quantity_kg' => 500,
            'price_per_kg' => 25000,
            'total_amount' => 12500000,
            'dp_amount' => 3750000, // 30%
            'escrow_locked_amount' => 8750000, // 70%
            'status' => 'dp_cair',
            'current_stage' => 'Fase Pembungaan (60%)',
            'stage_percentage' => 65,
        ]);

        // 4. Planting Progress (Milestone Lapangan Petani)
        PlantingProgress::create([
            'pre_order_id' => $preOrder->id,
            'stage_name' => 'Fase Benih & Semai',
            'note' => 'Bibit varietas Ori 212 mulai disemai dengan pupuk kandang matang.',
            'photo_url' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?auto=format&fit=crop&w=600&q=80',
            'location_tag' => 'Lahan Blok A, Ambarawa',
            'reported_at' => now()->subDays(60),
            'is_verified' => true,
        ]);

        PlantingProgress::create([
            'pre_order_id' => $preOrder->id,
            'stage_name' => 'Fase Vegetatif & Pertunasan',
            'note' => 'Pertumbuhan tunas daun merata, sistem irigasi tetes berjalan baik tanpa serangan hama.',
            'photo_url' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=600&q=80',
            'location_tag' => 'Lahan Blok A, Ambarawa',
            'reported_at' => now()->subDays(30),
            'is_verified' => true,
        ]);

        PlantingProgress::create([
            'pre_order_id' => $preOrder->id,
            'stage_name' => 'Fase Pembungaan & Bakal Buah (60%)',
            'note' => 'Bunga lebat dan bakal buah mulai terbentuk merata. Pemupukan kalium lanjutan dilakukan.',
            'photo_url' => 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80',
            'location_tag' => 'Lahan Blok A, Ambarawa',
            'reported_at' => now()->subDays(1),
            'is_verified' => true,
        ]);

        // 5. Delivery Tracking (Terintegrasi ke dalam Pesanan Pembeli & Petani)
        DeliveryTracking::create([
            'pre_order_id' => $preOrder->id,
            'pickup_address' => 'Lahan Pak Raden, Ambarawa, Kec. Bululawang, Kab. Malang',
            'destination_address' => 'Grand Hotel Malang, Jl. Ijen No. 77, Kota Malang',
            'distance_km' => 18.0,
            'estimated_time' => '45 Menit',
            'fuel_saving_percentage' => 28,
            'status' => 'scheduled',
            'pickup_time' => now()->addDays(14)->setTime(6, 30),
            'driver_name' => 'Budi Santoso',
            'driver_vehicle' => 'Pickup L300 (N 8241 AB)',
            'cargo_crates' => 25,
        ]);
    }
}

