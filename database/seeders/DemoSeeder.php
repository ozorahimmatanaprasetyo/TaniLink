<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\CommodityListing;
use App\Models\HarvestBatch;
use App\Models\PreOrder;
use App\Models\PlantingProgress;
use App\Models\DeliveryTracking;
use App\Models\OrderActivity;
use App\Models\CategoryProposal;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    /**
     * Seeder untuk Dataset DEMO:
     * - Safety Guard: Hanya mengeksekusi pada database 'tanilink_demo'
     * - 1 Akun Admin (admin@tanilink.id / admintanilink)
     * - 5 Akun Petani & 5 Akun Konsumen Fiktif (@example.test)
     * - 12 Komoditas Pertanian Unggulan
     * - 18 Batch Panen (4 fase perkembangan lengkap)
     * - Tepat 50 Pesanan Pre-Order tersebar 5 bulan terakhir (Juni–Oktober 2026):
     *     * Juni 2026: 6 pesanan
     *     * Juli 2026: 8 pesanan
     *     * Agustus 2026: 10 pesanan
     *     * September 2026: 12 pesanan
     *     * Oktober 2026: 14 pesanan
     *     * Total: 50 pesanan
     * - Seluruh grafik laporan admin (Bar Bulanan/Tahunan, Line Omzet, Donut Status, Top Komoditas) terisi proporsional.
     */
    public function run(): void
    {
        // -----------------------------------------------------------------
        // 1. SAFETY CHECK: Lindungi Database Clean & Produksi
        // -----------------------------------------------------------------
        $currentDb = DB::connection()->getDatabaseName();
        if ($currentDb !== 'tanilink_demo') {
            throw new \RuntimeException("DEMO SEEDER DIHENTIKAN: Seeder ini hanya boleh dieksekusi pada database 'tanilink_demo'. Database aktif saat ini: '{$currentDb}'.");
        }

        // -----------------------------------------------------------------
        // 2. BERSIHKAN DATA DEMO LAMA SECARA BERURUTAN (FOREIGN KEY SAFE)
        // -----------------------------------------------------------------
        DeliveryTracking::query()->delete();
        OrderActivity::query()->delete();
        PlantingProgress::query()->delete();
        PreOrder::query()->delete();
        HarvestBatch::query()->delete();
        CommodityListing::query()->delete();
        CategoryProposal::query()->delete();
        User::where('role', '!=', 'admin')->delete();

        // Pastikan akun Admin dan konfigurasi sistem tersedia via CleanSeeder
        $this->call(CleanSeeder::class);

        // -----------------------------------------------------------------
        // 3. SEED 5 AKUN PETANI FIKTIF (Kredensial Profesional)
        // -----------------------------------------------------------------
        $farmersData = [
            [
                'name' => 'Pak Joko Widodo Subroto',
                'email' => 'petani.joko@example.test',
                'phone' => '081200000001',
                'organization' => 'Kelompok Tani Makmur Bersama',
                'location' => 'Batu, Jawa Timur',
                'latitude' => -7.8712,
                'longitude' => 112.5273,
                'bank_name' => 'Bank Mandiri',
                'bank_account' => '9000011122233',
                'bank_holder' => 'Joko Widodo Subroto',
                'password' => Hash::make('password123'),
                'role' => 'petani',
                'created_at' => Carbon::create(2026, 5, 1, 8, 0, 0, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Ibu Siti Aminah Lestari',
                'email' => 'petani.siti@example.test',
                'phone' => '081200000002',
                'organization' => 'Gapoktan Sumber Berkah Organik',
                'location' => 'Malang, Jawa Timur',
                'latitude' => -7.9839,
                'longitude' => 112.6214,
                'bank_name' => 'Bank BCA',
                'bank_account' => '8000044455566',
                'bank_holder' => 'Siti Aminah Lestari',
                'password' => Hash::make('password123'),
                'role' => 'petani',
                'created_at' => Carbon::create(2026, 5, 1, 8, 30, 0, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Pak Wayan Sudarma',
                'email' => 'petani.wayan@example.test',
                'phone' => '081200000003',
                'organization' => 'Subak Tani Subur Alami',
                'location' => 'Kediri, Jawa Timur',
                'latitude' => -7.8480,
                'longitude' => 112.0178,
                'bank_name' => 'Bank BRI',
                'bank_account' => '5000077788899',
                'bank_holder' => 'Wayan Sudarma',
                'password' => Hash::make('password123'),
                'role' => 'petani',
                'created_at' => Carbon::create(2026, 5, 2, 9, 0, 0, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Pak Asep Sunandar',
                'email' => 'petani.asep@example.test',
                'phone' => '081200000004',
                'organization' => 'Koperasi Tani Parahyangan Asri',
                'location' => 'Bandung, Jawa Barat',
                'latitude' => -6.9175,
                'longitude' => 107.6191,
                'bank_name' => 'Bank BNI',
                'bank_account' => '4000012398765',
                'bank_holder' => 'Asep Sunandar',
                'password' => Hash::make('password123'),
                'role' => 'petani',
                'created_at' => Carbon::create(2026, 5, 2, 9, 30, 0, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Pak Budi Hartono Sejati',
                'email' => 'petani.budi@example.test',
                'phone' => '081200000005',
                'organization' => 'Tani Lestari Unggul Bersatu',
                'location' => 'Magelang, Jawa Tengah',
                'latitude' => -7.4706,
                'longitude' => 110.2178,
                'bank_name' => 'Bank Jatim',
                'bank_account' => '3000098765432',
                'bank_holder' => 'Budi Hartono Sejati',
                'password' => Hash::make('password123'),
                'role' => 'petani',
                'created_at' => Carbon::create(2026, 5, 3, 10, 0, 0, 'Asia/Jakarta'),
            ],
        ];

        $farmers = [];
        foreach ($farmersData as $fd) {
            $farmers[] = User::create($fd);
        }

        // -----------------------------------------------------------------
        // 4. SEED 5 AKUN KONSUMEN / PEMBELI FIKTIF
        // -----------------------------------------------------------------
        $buyersData = [
            [
                'name' => 'Restoran Selera Nusantara',
                'email' => 'horeka.nusantara@example.test',
                'phone' => '082100000001',
                'organization' => 'PT Kuliner Cita Rasa Sejati',
                'location' => 'Surabaya, Jawa Timur',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
                'created_at' => Carbon::create(2026, 5, 5, 11, 0, 0, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Ibu Ratna Kumalasari',
                'email' => 'konsumen.ratna@example.test',
                'phone' => '082100000002',
                'organization' => 'Dapur Sehat Rumahan',
                'location' => 'Malang, Jawa Timur',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
                'created_at' => Carbon::create(2026, 5, 6, 11, 30, 0, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Supermarket Segar Mart',
                'email' => 'retail.segarmart@example.test',
                'phone' => '082100000003',
                'organization' => 'CV Retail Agro Makmur',
                'location' => 'Sidoarjo, Jawa Timur',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
                'created_at' => Carbon::create(2026, 5, 7, 12, 0, 0, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Katering Berkah Sedap',
                'email' => 'katering.berkah@example.test',
                'phone' => '082100000004',
                'organization' => 'Usaha Katering Keluarga Mandiri',
                'location' => 'Semarang, Jawa Tengah',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
                'created_at' => Carbon::create(2026, 5, 8, 12, 30, 0, 'Asia/Jakarta'),
            ],
            [
                'name' => 'Hotel Grand Harmoni Malang',
                'email' => 'hotel.grandharmoni@example.test',
                'phone' => '082100000005',
                'organization' => 'Divisi Pengadaan Bahan Pangan Horeka',
                'location' => 'Kota Batu, Jawa Timur',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
                'created_at' => Carbon::create(2026, 5, 9, 13, 0, 0, 'Asia/Jakarta'),
            ],
        ];

        $buyers = [];
        foreach ($buyersData as $bd) {
            $buyers[] = User::create($bd);
        }

        // -----------------------------------------------------------------
        // 5. SEED 12 KOMODITAS PERTANIAN UNGGULAN
        // -----------------------------------------------------------------
        $commoditiesData = [
            [
                'farmer_idx' => 0,
                'name' => 'Cabai Rawit Merah Ori 212',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Ori 212 Super Pedas',
                'grade' => 'Grade A',
                'land_area' => 3500,
                'est_harvest' => 2800,
                'price' => 32000,
                'image_url' => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Cabai rawit merah varietas unggul dengan ketahanan simpan tinggi, cocok untuk kebutuhan industri sambal dan horeka.'
            ],
            [
                'farmer_idx' => 0,
                'name' => 'Cabai Merah Keriting Kastilo',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Kastilo F1',
                'grade' => 'Grade A',
                'land_area' => 4000,
                'est_harvest' => 3500,
                'price' => 28000,
                'image_url' => 'https://images.unsplash.com/photo-1590779033100-9f60a05a013d?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Cabai keriting merah segar panen langsung dari kebun dataran tinggi dengan warna merah merata dan mengkilap.'
            ],
            [
                'farmer_idx' => 1,
                'name' => 'Bawang Merah Bima Brebes',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Bima Brebes Super',
                'grade' => 'Grade A',
                'land_area' => 5000,
                'est_harvest' => 4500,
                'price' => 25000,
                'image_url' => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Bawang merah dengan umbi padat, aroma kuat, dan daya simpan alami hingga 2 bulan pasca panen.'
            ],
            [
                'farmer_idx' => 1,
                'name' => 'Tomat Ceri Hidroponik Ruby',
                'category' => 'Sayuran',
                'variety' => 'Ruby Cherry F1',
                'grade' => 'Grade A',
                'land_area' => 1500,
                'est_harvest' => 1500,
                'price' => 35000,
                'image_url' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Tomat ceri manis segar bernutrisi tinggi hasil budidaya greenhouse hidroponik bebas pestisida kimia sintetis.'
            ],
            [
                'farmer_idx' => 2,
                'name' => 'Kentang Granola Lembang Super',
                'category' => 'Palawija',
                'variety' => 'Granola G2',
                'grade' => 'Grade A',
                'land_area' => 6000,
                'est_harvest' => 8000,
                'price' => 16000,
                'image_url' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Kentang sayur kualitas premium ukuran seragam, cocok untuk olahan katering, perkedel, dan sayur sop restoran.'
            ],
            [
                'farmer_idx' => 2,
                'name' => 'Wortel Brastagi Manis',
                'category' => 'Sayuran',
                'variety' => 'Kuroda Seleksi',
                'grade' => 'Grade A',
                'land_area' => 3000,
                'est_harvest' => 3500,
                'price' => 14000,
                'image_url' => 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Wortel oranye cerah berkulit mulus dan renyah dengan rasa manis alami kaya vitamin A.'
            ],
            [
                'farmer_idx' => 3,
                'name' => 'Jagung Manis Bonanza F1',
                'category' => 'Palawija',
                'variety' => 'Bonanza 9 F1',
                'grade' => 'Grade A',
                'land_area' => 7000,
                'est_harvest' => 7500,
                'price' => 9000,
                'image_url' => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Jagung manis bertongkol penuh dengan tingkat kemanisan brix 13-14, sangat diminati katering dan restoran.'
            ],
            [
                'farmer_idx' => 3,
                'name' => 'Kubis Bulat Dataran Tinggi',
                'category' => 'Sayuran',
                'variety' => 'Green Nova',
                'grade' => 'Grade A',
                'land_area' => 3500,
                'est_harvest' => 5000,
                'price' => 8000,
                'image_url' => 'https://images.unsplash.com/photo-1628771065518-0d82f1938462?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Kubis krop padat tanpa cacat ulat, disortir bersih sebelum penimbangan dan pengiriman.'
            ],
            [
                'farmer_idx' => 4,
                'name' => 'Bawang Putih Tunggal Lanang',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Lanang Herbal',
                'grade' => 'Grade A',
                'land_area' => 2000,
                'est_harvest' => 1200,
                'price' => 65000,
                'image_url' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Bawang lanang berkhasiat herbal tinggi, disukai industri minuman kesehatan dan pengolahan jamu.'
            ],
            [
                'farmer_idx' => 4,
                'name' => 'Jahe Merah Gajah Unggulan',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Rimpang Merah Tebal',
                'grade' => 'Grade A',
                'land_area' => 3000,
                'est_harvest' => 2500,
                'price' => 38000,
                'image_url' => 'https://images.unsplash.com/photo-1615485500704-8e990f9900f7?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Rimpang jahe merah segar berumur panen 10 bulan dengan kadar minyak atsiri pekat dan pedas mantap.'
            ],
            [
                'farmer_idx' => 0,
                'name' => 'Brokoli Hijau Dataran Tinggi',
                'category' => 'Sayuran',
                'variety' => 'Lucky F1',
                'grade' => 'Grade A',
                'land_area' => 2500,
                'est_harvest' => 2000,
                'price' => 24000,
                'image_url' => 'https://images.unsplash.com/photo-1459411621453-7b03977f4bfc?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Kuntum bunga brokoli hijau rapat segar, dipetik pagi hari menjelang serah terima kontrak.'
            ],
            [
                'farmer_idx' => 1,
                'name' => 'Labu Madu Butternut Squash',
                'category' => 'Buah',
                'variety' => 'Jacqueline F1',
                'grade' => 'Grade A',
                'land_area' => 3000,
                'est_harvest' => 3600,
                'price' => 18000,
                'image_url' => 'https://images.unsplash.com/photo-1570586435893-ab4e76b2d039?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Labu madu manis bertekstur pulen lembut dan legit, cocok untuk makanan pendamping ASI dan menu bakery.'
            ],
        ];

        $listings = [];
        foreach ($commoditiesData as $idx => $cd) {
            $farmer = $farmers[$cd['farmer_idx']];
            $listings[] = CommodityListing::create([
                'user_id' => $farmer->id,
                'commodity_name' => $cd['name'],
                'category' => $cd['category'],
                'variety' => $cd['variety'],
                'grade' => $cd['grade'],
                'land_area_m2' => $cd['land_area'],
                'estimated_harvest_kg' => $cd['est_harvest'],
                'available_kg' => $cd['est_harvest'],
                'price_per_kg' => $cd['price'],
                'harvest_estimated_date' => Carbon::create(2026, 11, 15, 0, 0, 0, 'Asia/Jakarta')->addDays($idx * 3)->toDateString(),
                'location' => $farmer->location,
                'latitude' => $farmer->latitude,
                'longitude' => $farmer->longitude,
                'image_url' => $cd['image_url'],
                'description' => $cd['desc'],
                'status' => 'available',
                'created_at' => Carbon::create(2026, 5, 10, 8, 0, 0, 'Asia/Jakarta')->addDays($idx),
                'updated_at' => Carbon::create(2026, 5, 10, 8, 0, 0, 'Asia/Jakarta')->addDays($idx),
            ]);
        }

        // -----------------------------------------------------------------
        // 6. SEED 18 BATCH PANEN (Distribusi Siklus Mei - Desember 2026)
        // -----------------------------------------------------------------
        $batchesConfig = [
            // [listing_idx, code, name, est_date, est_kg, phase, phase_status, status, p1_at, p2_at, p3_at, p4_at]
            // Batch Selesai di Bulan Juni-September
            [0, 'BATCH-0001-01', 'Panen Juli 2026', '2026-07-15', 1200, 'Siap Panen', 'selesai', 'selesai', '2026-05-15', '2026-06-05', '2026-06-25', '2026-07-15'],
            [0, 'BATCH-0001-02', 'Panen November 2026', '2026-11-20', 1600, 'Pertumbuhan Tanaman', 'berlangsung', 'aktif', '2026-09-10', null, null, null],
            [1, 'BATCH-0002-01', 'Panen Agustus 2026', '2026-08-10', 1500, 'Siap Panen', 'selesai', 'selesai', '2026-06-01', '2026-06-25', '2026-07-20', '2026-08-10'],
            [1, 'BATCH-0002-02', 'Panen November 2026', '2026-11-26', 2000, 'Pertumbuhan Tanaman', 'berlangsung', 'aktif', '2026-09-20', null, null, null],
            [2, 'BATCH-0003-01', 'Panen Juli 2026', '2026-07-25', 2000, 'Siap Panen', 'selesai', 'selesai', '2026-05-20', '2026-06-10', '2026-07-05', '2026-07-25'],
            [2, 'BATCH-0003-02', 'Panen Desember 2026', '2026-12-05', 2500, 'Persiapan & Penanaman', 'berlangsung', 'aktif', '2026-10-15', null, null, null],
            [3, 'BATCH-0004-01', 'Panen Agustus 2026', '2026-08-20', 700, 'Siap Panen', 'selesai', 'selesai', '2026-06-15', '2026-07-05', '2026-07-30', '2026-08-20'],
            [3, 'BATCH-0004-02', 'Panen November 2026', '2026-11-18', 800, 'Menjelang Panen', 'berlangsung', 'aktif', '2026-09-01', '2026-09-25', null, null],
            [4, 'BATCH-0005-01', 'Panen September 2026', '2026-09-15', 4000, 'Siap Panen', 'selesai', 'selesai', '2026-06-20', '2026-07-25', '2026-08-25', '2026-09-15'],
            [4, 'BATCH-0005-02', 'Panen Desember 2026', '2026-12-15', 4000, 'Persiapan & Penanaman', 'berlangsung', 'aktif', '2026-10-20', null, null, null],
            [5, 'BATCH-0006-01', 'Panen September 2026', '2026-09-25', 1500, 'Siap Panen', 'selesai', 'selesai', '2026-07-01', '2026-07-30', '2026-08-30', '2026-09-25'],
            [5, 'BATCH-0006-02', 'Panen Desember 2026', '2026-12-20', 2000, 'Pertumbuhan Tanaman', 'berlangsung', 'aktif', '2026-10-05', null, null, null],
            [6, 'BATCH-0007-01', 'Panen Oktober 2026', '2026-10-10', 3500, 'Siap Panen', 'selesai', 'selesai', '2026-07-15', '2026-08-15', '2026-09-15', '2026-10-10'],
            [6, 'BATCH-0007-02', 'Panen Desember 2026', '2026-12-25', 4000, 'Persiapan & Penanaman', 'berlangsung', 'aktif', null, null, null, null],
            [7, 'BATCH-0008-01', 'Panen November 2026', '2026-11-10', 2500, 'Menjelang Panen', 'berlangsung', 'aktif', '2026-08-25', '2026-09-20', null, null],
            [8, 'BATCH-0009-01', 'Panen November 2026', '2026-11-28', 1200, 'Pertumbuhan Tanaman', 'berlangsung', 'aktif', '2026-09-15', null, null, null],
            [9, 'BATCH-0010-01', 'Panen Desember 2026', '2026-12-10', 2500, 'Pertumbuhan Tanaman', 'berlangsung', 'aktif', '2026-09-05', null, null, null],
            [10, 'BATCH-0011-01', 'Panen November 2026', '2026-11-15', 2000, 'Menjelang Panen', 'berlangsung', 'aktif', '2026-08-30', '2026-09-28', null, null],
        ];

        $batches = [];
        foreach ($batchesConfig as $bc) {
            $l = $listings[$bc[0]];
            $batches[] = HarvestBatch::create([
                'commodity_listing_id' => $l->id,
                'farmer_id' => $l->user_id,
                'batch_code' => $bc[1],
                'batch_name' => $bc[2],
                'estimated_harvest_date' => $bc[3],
                'estimated_harvest_kg' => $bc[4],
                'allocated_kg' => 0,
                'current_phase' => $bc[5],
                'current_phase_status' => $bc[6],
                'status' => $bc[7],
                'phase1_completed_at' => $bc[8] ? Carbon::parse($bc[8] . ' 10:00:00', 'Asia/Jakarta') : null,
                'phase2_completed_at' => $bc[9] ? Carbon::parse($bc[9] . ' 11:00:00', 'Asia/Jakarta') : null,
                'phase3_completed_at' => $bc[10] ? Carbon::parse($bc[10] . ' 14:00:00', 'Asia/Jakarta') : null,
                'phase4_completed_at' => $bc[11] ? Carbon::parse($bc[11] . ' 09:00:00', 'Asia/Jakarta') : null,
                'created_at' => Carbon::parse(($bc[8] ?: '2026-08-01') . ' 08:00:00', 'Asia/Jakarta'),
                'updated_at' => Carbon::now('Asia/Jakarta'),
            ]);
        }

        // Seeding Dokumentasi Perkembangan Tanaman (PlantingProgress) untuk setiap batch
        foreach ($batches as $b) {
            PlantingProgress::create([
                'harvest_batch_id' => $b->id,
                'stage_name' => 'Persiapan & Penanaman',
                'stage_status' => 'selesai',
                'note' => 'Pengolahan tanah, pembuatan bedengan, pemasangan mulsa, dan penanaman bibit unggul selesai terlaksana sesuai SOP.',
                'photo_url' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=600&auto=format&fit=crop&q=80',
                'location_tag' => 'Petak Lahan Blok Utama',
                'reported_at' => $b->phase1_completed_at ?: Carbon::now('Asia/Jakarta')->subDays(40),
            ]);

            if (in_array($b->current_phase, ['Pertumbuhan Tanaman', 'Menjelang Panen', 'Siap Panen'])) {
                PlantingProgress::create([
                    'harvest_batch_id' => $b->id,
                    'stage_name' => 'Pertumbuhan Tanaman',
                    'stage_status' => in_array($b->current_phase, ['Menjelang Panen', 'Siap Panen']) ? 'selesai' : 'berlangsung',
                    'note' => 'Pertumbuhan vegetatif daun dan batang optimal. Pengairan berkala dan aplikasi pupuk hayati organik berjalan lancar.',
                    'photo_url' => 'https://images.unsplash.com/photo-1592417817098-8f3d6eb22521?w=600&auto=format&fit=crop&q=80',
                    'location_tag' => 'Petak Lahan Blok Utama',
                    'reported_at' => $b->phase2_completed_at ?: Carbon::now('Asia/Jakarta')->subDays(20),
                ]);
            }

            if (in_array($b->current_phase, ['Menjelang Panen', 'Siap Panen'])) {
                PlantingProgress::create([
                    'harvest_batch_id' => $b->id,
                    'stage_name' => 'Menjelang Panen',
                    'stage_status' => $b->current_phase === 'Siap Panen' ? 'selesai' : 'berlangsung',
                    'note' => 'Pembentukan buah/umbi merata, ukuran seragam dan bobot memenuhi standar mutu komersial pre-order.',
                    'photo_url' => 'https://images.unsplash.com/photo-1590779033100-9f60a05a013d?w=600&auto=format&fit=crop&q=80',
                    'location_tag' => 'Petak Lahan Blok Utama',
                    'reported_at' => $b->phase3_completed_at ?: Carbon::now('Asia/Jakarta')->subDays(7),
                ]);
            }
        }

        // -----------------------------------------------------------------
        // 7. SEED TEPAT 50 PRE-ORDER (Juni: 6, Juli: 8, Agu: 10, Sep: 12, Okt: 14)
        // -----------------------------------------------------------------
        // Struktur: [date (Y-m-d), batch_idx, buyer_idx, qty_kg, status, opt_reason]
        $ordersRaw = [
            // ==========================================
            // JUNI 2026: 6 PESANAN
            // ==========================================
            ['2026-06-05 09:15:00', 0, 0, 200, 'selesai', null],
            ['2026-06-11 11:30:00', 2, 1, 150, 'selesai', null],
            ['2026-06-16 14:00:00', 4, 2, 250, 'selesai', null],
            ['2026-06-20 10:45:00', 0, 3, 180, 'selesai', null],
            ['2026-06-24 16:20:00', 2, 4, 300, 'dibatalkan', 'Kapasitas angkut ekspedisi logistik mitra pada rute ini sedang penuh.'],
            ['2026-06-28 13:10:00', 6, 0, 120, 'selesai', null],

            // ==========================================
            // JULI 2026: 8 PESANAN
            // ==========================================
            ['2026-07-04 08:30:00', 0, 1, 150, 'selesai', null],
            ['2026-07-08 10:15:00', 2, 2, 220, 'selesai', null],
            ['2026-07-13 13:40:00', 4, 3, 200, 'selesai', null],
            ['2026-07-17 09:50:00', 6, 4, 140, 'selesai', null],
            ['2026-07-21 15:25:00', 8, 0, 350, 'selesai', null],
            ['2026-07-25 11:10:00', 10, 1, 180, 'selesai', null],
            ['2026-07-28 14:35:00', 0, 2, 500, 'dibatalkan', 'Konsumen membatalkan sepihak karena perubahan alur pengadaan dapur cabang.'],
            ['2026-07-31 16:00:00', 8, 3, 250, 'pesanan_aktif', null],

            // ==========================================
            // AGUSTUS 2026: 10 PESANAN
            // ==========================================
            ['2026-08-03 09:20:00', 2, 4, 180, 'selesai', null],
            ['2026-08-06 11:45:00', 6, 0, 160, 'selesai', null],
            ['2026-08-10 14:15:00', 8, 1, 400, 'selesai', null],
            ['2026-08-14 10:30:00', 10, 2, 220, 'siap_panen', null],
            ['2026-08-17 13:50:00', 12, 3, 300, 'siap_panen', null],
            ['2026-08-20 08:40:00', 14, 4, 150, 'siap_panen', null],
            ['2026-08-23 15:10:00', 8, 0, 280, 'dalam_pengiriman', null],
            ['2026-08-26 12:00:00', 10, 1, 200, 'dalam_pengiriman', null],
            ['2026-08-28 16:30:00', 1, 2, 200, 'pesanan_aktif', null],
            ['2026-08-31 11:15:00', 4, 3, 600, 'dibatalkan', 'Spesifikasi ukuran umbi yang diminta pembeli di luar toleransi panen musim ini.'],

            // ==========================================
            // SEPTEMBER 2026: 12 PESANAN
            // ==========================================
            ['2026-09-02 08:50:00', 10, 4, 180, 'dalam_pengiriman', null],
            ['2026-09-05 10:20:00', 12, 0, 320, 'dalam_pengiriman', null],
            ['2026-09-08 13:45:00', 14, 1, 160, 'siap_panen', null],
            ['2026-09-11 09:10:00', 16, 2, 200, 'siap_panen', null],
            ['2026-09-14 14:30:00', 1, 3, 220, 'pesanan_aktif', null],
            ['2026-09-17 11:00:00', 3, 4, 250, 'pesanan_aktif', null],
            ['2026-09-20 15:40:00', 7, 0, 130, 'pesanan_aktif', null],
            ['2026-09-22 10:15:00', 11, 1, 210, 'pesanan_aktif', null],
            ['2026-09-25 12:50:00', 13, 2, 280, 'menunggu_verifikasi_dp', null],
            ['2026-09-27 16:20:00', 15, 3, 100, 'menunggu_pembayaran_dp', null],
            ['2026-09-29 09:30:00', 17, 4, 180, 'menunggu_konfirmasi', null],
            ['2026-09-30 14:00:00', 3, 0, 450, 'dibatalkan', 'Permintaan jadwal pengiriman mundur melebihi daya simpan segar komoditas.'],

            // ==========================================
            // OKTOBER 2026: 14 PESANAN
            // ==========================================
            ['2026-10-02 09:00:00', 1, 1, 180, 'pesanan_aktif', null],
            ['2026-10-04 11:20:00', 3, 2, 200, 'pesanan_aktif', null],
            ['2026-10-07 14:40:00', 5, 3, 250, 'pesanan_aktif', null],
            ['2026-10-10 10:10:00', 7, 4, 120, 'pesanan_aktif', null],
            ['2026-10-12 13:30:00', 9, 0, 350, 'pesanan_aktif', null],
            ['2026-10-15 08:45:00', 11, 1, 240, 'pesanan_aktif', null],
            ['2026-10-17 15:15:00', 13, 2, 300, 'menunggu_verifikasi_dp', null],
            ['2026-10-19 12:00:00', 15, 3, 120, 'menunggu_verifikasi_dp', null],
            ['2026-10-21 16:40:00', 17, 4, 200, 'menunggu_pembayaran_dp', null],
            ['2026-10-23 10:30:00', 1, 0, 150, 'menunggu_pembayaran_dp', null],
            ['2026-10-24 14:15:00', 3, 1, 180, 'menunggu_konfirmasi', null],
            ['2026-10-25 11:00:00', 5, 2, 220, 'menunggu_konfirmasi', null],
            ['2026-10-26 15:30:00', 7, 3, 140, 'menunggu_konfirmasi', null],
            ['2026-10-27 10:00:00', 9, 4, 300, 'dibatalkan', 'Stok varietas unggul telah terkontrak penuh oleh pesanan sebelumnya.'],
        ];

        foreach ($ordersRaw as $idx => $ord) {
            $createdDt = Carbon::parse($ord[0], 'Asia/Jakarta');
            $batch = $batches[$ord[1]];
            $listing = $batch->commodityListing;
            $farmer = $batch->farmer;
            $buyer = $buyers[$ord[2]];
            $qty = (float) $ord[3];
            $status = $ord[4];
            $reason = $ord[5];

            $orderCode = 'PO-DEMO-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT);
            $price = (float) $listing->price_per_kg;
            $totalAmount = $qty * $price;
            $dpAmount = round($totalAmount * 0.30);
            $escrowAmount = $totalAmount - $dpAmount;

            // Kronologi Waktu Berurutan yang Realistis
            $confirmedAt = null;
            $dpUploadedAt = null;
            $dpConfirmedAt = null;
            $completedAt = null;
            $rejectedAt = null;
            $dpProofPath = null;
            $finalUploadedAt = null;
            $finalConfirmedAt = null;

            if (in_array($status, ['menunggu_pembayaran_dp', 'menunggu_verifikasi_dp', 'pesanan_aktif', 'siap_panen', 'dalam_pengiriman', 'selesai'])) {
                $confirmedAt = $createdDt->copy()->addHours(6);
            }

            if (in_array($status, ['menunggu_verifikasi_dp', 'pesanan_aktif', 'siap_panen', 'dalam_pengiriman', 'selesai'])) {
                $dpUploadedAt = $confirmedAt->copy()->addHours(14);
                $dpProofPath = 'demo/bukti_transfer_sample.png';
            }

            if (in_array($status, ['pesanan_aktif', 'siap_panen', 'dalam_pengiriman', 'selesai'])) {
                $dpConfirmedAt = $dpUploadedAt->copy()->addHours(5);
            }

            if ($status === 'selesai') {
                $completedAt = $createdDt->copy()->addDays(28);
                $finalUploadedAt = $completedAt->copy()->subHours(8);
                $finalConfirmedAt = $completedAt->copy()->subHours(2);
            }

            if ($status === 'dibatalkan') {
                $rejectedAt = $createdDt->copy()->addHours(9);
            }

            $order = PreOrder::create([
                'order_code' => $orderCode,
                'commodity_listing_id' => $listing->id,
                'harvest_batch_id' => $batch->id,
                'farmer_id' => $farmer->id,
                'buyer_id' => $buyer->id,
                'quantity_kg' => $qty,
                'price_per_kg' => $price,
                'need_date' => $batch->estimated_harvest_date,
                'total_amount' => $totalAmount,
                'dp_amount' => $dpAmount,
                'escrow_locked_amount' => $escrowAmount,
                'farmer_bank_name' => $farmer->bank_name,
                'farmer_bank_account' => $farmer->bank_account,
                'farmer_bank_holder' => $farmer->bank_holder,
                'dp_deadline_at' => $confirmedAt ? $confirmedAt->copy()->addDays(2) : null,
                'dp_proof_path' => $dpProofPath,
                'dp_uploaded_at' => $dpUploadedAt,
                'dp_confirmed_at' => $dpConfirmedAt,
                'final_payment_proof_path' => $finalUploadedAt ? 'demo/bukti_transfer_sample.png' : null,
                'final_payment_uploaded_at' => $finalUploadedAt,
                'final_payment_confirmed_at' => $finalConfirmedAt,
                'status' => $status,
                'rejection_reason' => $reason,
                'rejected_at' => $rejectedAt,
                'confirmed_at' => $confirmedAt,
                'completed_at' => $completedAt,
                'current_stage' => $batch->current_phase,
                'current_stage_status' => $batch->current_phase_status,
                'stage_percentage' => $status === 'selesai' ? 100 : ($status === 'siap_panen' ? 90 : ($status === 'dalam_pengiriman' ? 95 : ($status === 'pesanan_aktif' ? 50 : 10))),
                'created_at' => $createdDt,
                'updated_at' => $completedAt ?: ($dpConfirmedAt ?: ($confirmedAt ?: $createdDt)),
            ]);

            // Riwayat Aktivitas Pesanan (OrderActivity Logs)
            OrderActivity::log(
                $order->id,
                'order_created',
                'Pengajuan Pre-Order',
                'Konsumen mengajukan pesanan sejumlah ' . number_format($qty, 0, ',', '.') . ' kg.',
                $buyer->id,
                $createdDt
            );

            if ($confirmedAt) {
                OrderActivity::log(
                    $order->id,
                    'order_confirmed',
                    'Pesanan Dikonfirmasi Petani',
                    'Petani menerima kontrak pre-order dan membuka instruksi pembayaran DP 30%.',
                    $farmer->id,
                    $confirmedAt
                );
            }

            if ($dpUploadedAt) {
                OrderActivity::log(
                    $order->id,
                    'dp_payment_uploaded',
                    'Bukti DP Diunggah',
                    'Konsumen mengunggah bukti transfer DP Rp ' . number_format($dpAmount, 0, ',', '.') . ' ke rekening petani.',
                    $buyer->id,
                    $dpUploadedAt
                );
            }

            if ($dpConfirmedAt) {
                OrderActivity::log(
                    $order->id,
                    'dp_verified',
                    'DP Dikonfirmasi & Masuk Budidaya',
                    'Petani memvalidasi mutasi DP dan memulai alokasi pemeliharaan batch panen.',
                    $farmer->id,
                    $dpConfirmedAt
                );
            }

            if ($status === 'dalam_pengiriman' || $status === 'selesai') {
                $shipDt = $createdDt->copy()->addDays(22);
                DeliveryTracking::create([
                    'pre_order_id' => $order->id,
                    'pickup_address' => 'Lahan Mitra TaniLink, ' . $farmer->location,
                    'destination_address' => $buyer->organization . ', ' . $buyer->location,
                    'status' => $status === 'selesai' ? 'delivered' : 'in_transit',
                    'driver_name' => 'Agus Priyanto',
                    'driver_vehicle' => 'Truk Pendingin Box N 8921 AB',
                    'cargo_crates' => max(1, ceil($qty / 20)),
                    'created_at' => $shipDt,
                    'updated_at' => $completedAt ?: $shipDt,
                ]);

                OrderActivity::log(
                    $order->id,
                    'order_shipped',
                    'Pengiriman Hasil Panen',
                    'Komoditas telah dimuat ke armada logistik berpendingin dan dalam perjalanan menuju konsumen.',
                    $farmer->id,
                    $shipDt
                );
            }

            if ($completedAt) {
                OrderActivity::log(
                    $order->id,
                    'order_completed',
                    'Pesanan Selesai Tuntas',
                    'Konsumen mengonfirmasi penerimaan komoditas dengan kondisi sangat baik dan pelunasan selesai.',
                    $buyer->id,
                    $completedAt
                );
            }

            if ($rejectedAt) {
                OrderActivity::log(
                    $order->id,
                    'order_rejected',
                    'Pesanan Dibatalkan',
                    'Alasan: ' . ($reason ?: 'Pemesanan tidak dapat dilanjutkan.'),
                    $farmer->id,
                    $rejectedAt
                );
            }
        }

        // -----------------------------------------------------------------
        // 8. SINKRONISASI KUOTA BATCH & STOK KOMODITAS SECARA AKURAT
        // -----------------------------------------------------------------
        foreach ($batches as $b) {
            $sum = PreOrder::where('harvest_batch_id', $b->id)
                ->whereNotIn('status', ['dibatalkan', 'ditolak'])
                ->sum('quantity_kg');
            $b->update(['allocated_kg' => (int) $sum]);
        }

        foreach ($listings as $l) {
            $sumAllocated = HarvestBatch::where('commodity_listing_id', $l->id)->sum('allocated_kg');
            $avail = max(0, $l->estimated_harvest_kg - $sumAllocated);
            $l->update([
                'available_kg' => $avail,
                'status' => $avail <= 0 ? 'booked' : 'available',
            ]);
        }
    }
}
