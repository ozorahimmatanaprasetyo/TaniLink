<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CommodityListing;
use App\Models\HarvestBatch;
use App\Models\PreOrder;
use App\Models\PlantingProgress;
use App\Models\DeliveryTracking;
use App\Models\OrderActivity;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    /**
     * Seeder untuk Kondisi DEMO:
     * - 1 Admin
     * - 5 Petani
     * - 5 Konsumen
     * - 12 Komoditas
     * - 15 Batch Panen
     * - Sekitar 25 Pesanan dengan skenario transaksi lengkap
     * - Dokumentasi tanaman per batch & riwayat order activities
     * - Data fiktif aman (@example.test) tersebar di berbagai bulan tahun berjalan
     */
    public function run(): void
    {
        // 1. Jalankan konfigurasi dasar terlebih dahulu
        $this->call(CleanSeeder::class);

        $admin = User::where('role', 'admin')->first();

        // 2. Buat 5 Akun Petani Fiktif (Idempoten via email)
        $farmersData = [
            [
                'name' => 'Pak Joko Widodo Subroto (Demo)',
                'email' => 'petani.joko@example.test',
                'phone' => '081200000001',
                'organization' => 'Kelompok Tani Makmur Bersama (Demo)',
                'location' => 'Batu, Jawa Timur',
                'bank_name' => 'Bank Mandiri (Demo)',
                'bank_account' => '9000011122233',
                'bank_holder' => 'Joko Widodo Subroto',
                'password' => Hash::make('password123'),
                'role' => 'petani',
            ],
            [
                'name' => 'Ibu Siti Aminah Lestari (Demo)',
                'email' => 'petani.siti@example.test',
                'phone' => '081200000002',
                'organization' => 'Gapoktan Sumber Berkah Organik (Demo)',
                'location' => 'Malang, Jawa Timur',
                'bank_name' => 'Bank BCA (Demo)',
                'bank_account' => '8000044455566',
                'bank_holder' => 'Siti Aminah Lestari',
                'password' => Hash::make('password123'),
                'role' => 'petani',
            ],
            [
                'name' => 'Pak Wayan Sudarma (Demo)',
                'email' => 'petani.wayan@example.test',
                'phone' => '081200000003',
                'organization' => 'Subak Tani Subur Alami (Demo)',
                'location' => 'Kediri, Jawa Timur',
                'bank_name' => 'Bank BRI (Demo)',
                'bank_account' => '5000077788899',
                'bank_holder' => 'Wayan Sudarma',
                'password' => Hash::make('password123'),
                'role' => 'petani',
            ],
            [
                'name' => 'Pak Asep Sunandar (Demo)',
                'email' => 'petani.asep@example.test',
                'phone' => '081200000004',
                'organization' => 'Koperasi Tani Parahyangan Asri (Demo)',
                'location' => 'Bandung, Jawa Barat',
                'bank_name' => 'Bank BNI (Demo)',
                'bank_account' => '4000012398765',
                'bank_holder' => 'Asep Sunandar',
                'password' => Hash::make('password123'),
                'role' => 'petani',
            ],
            [
                'name' => 'Pak Budi Hartono Sejati (Demo)',
                'email' => 'petani.budi@example.test',
                'phone' => '081200000005',
                'organization' => 'Tani Lestari Unggul Bersatu (Demo)',
                'location' => 'Magelang, Jawa Tengah',
                'bank_name' => 'Bank Jatim (Demo)',
                'bank_account' => '3000098765432',
                'bank_holder' => 'Budi Hartono Sejati',
                'password' => Hash::make('password123'),
                'role' => 'petani',
            ],
        ];

        $farmers = [];
        foreach ($farmersData as $fd) {
            $farmers[] = User::firstOrCreate(['email' => $fd['email']], $fd);
        }

        // 3. Buat 5 Akun Konsumen Fiktif
        $buyersData = [
            [
                'name' => 'Restoran Selera Nusantara (Demo)',
                'email' => 'horeka.nusantara@example.test',
                'phone' => '082100000001',
                'organization' => 'PT Kuliner Cita Rasa Sejati (Demo)',
                'location' => 'Surabaya, Jawa Timur',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
            ],
            [
                'name' => 'Ibu Ratna Kumalasari (Demo)',
                'email' => 'konsumen.ratna@example.test',
                'phone' => '082100000002',
                'organization' => 'Dapur Sehat Rumahan (Demo)',
                'location' => 'Malang, Jawa Timur',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
            ],
            [
                'name' => 'Supermarket Segar Mart (Demo)',
                'email' => 'retail.segarmart@example.test',
                'phone' => '082100000003',
                'organization' => 'CV Retail Agro Makmur (Demo)',
                'location' => 'Sidoarjo, Jawa Timur',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
            ],
            [
                'name' => 'Katering Berkah Sedap (Demo)',
                'email' => 'katering.berkah@example.test',
                'phone' => '082100000004',
                'organization' => 'Usaha Katering Keluarga Mandiri (Demo)',
                'location' => 'Semarang, Jawa Tengah',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
            ],
            [
                'name' => 'Hotel Grand Harmoni Malang (Demo)',
                'email' => 'hotel.grandharmoni@example.test',
                'phone' => '082100000005',
                'organization' => 'Divisi Pengadaan Bahan Pangan Horeka (Demo)',
                'location' => 'Kota Batu, Jawa Timur',
                'password' => Hash::make('password123'),
                'role' => 'pembeli',
            ],
        ];

        $buyers = [];
        foreach ($buyersData as $bd) {
            $buyers[] = User::firstOrCreate(['email' => $bd['email']], $bd);
        }

        // 4. Buat 12 Komoditas Pertanian Realistis
        $commoditiesData = [
            [
                'farmer_idx' => 0,
                'name' => 'Cabai Rawit Merah Ori 212 (Demo)',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Ori 212 Super Pedas',
                'land_area' => 3500,
                'est_harvest' => 2800,
                'price' => 32000,
                'image_url' => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Cabai rawit merah varietas unggul dengan ketahanan simpan tinggi, cocok untuk kebutuhan industri sambal dan horeka.'
            ],
            [
                'farmer_idx' => 0,
                'name' => 'Cabai Merah Keriting Kastilo (Demo)',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Kastilo F1',
                'land_area' => 4000,
                'est_harvest' => 3500,
                'price' => 28000,
                'image_url' => 'https://images.unsplash.com/photo-1590779033100-9f60a05a013d?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Cabai keriting merah segar panen langsung dari kebun dataran tinggi dengan warna merah merata dan mengkilap.'
            ],
            [
                'farmer_idx' => 1,
                'name' => 'Bawang Merah Bima Brebes (Demo)',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Bima Brebes Super',
                'land_area' => 5000,
                'est_harvest' => 4500,
                'price' => 25000,
                'image_url' => 'https://images.unsplash.com/photo-1618512496248-a07fe83aa8cb?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Bawang merah dengan umbi padat, aroma kuat, dan daya simpan alami hingga 2 bulan pasca panen.'
            ],
            [
                'farmer_idx' => 1,
                'name' => 'Tomat Ceri Hidroponik Ruby (Demo)',
                'category' => 'Sayuran Daun',
                'variety' => 'Ruby Cherry F1',
                'land_area' => 1500,
                'est_harvest' => 1200,
                'price' => 35000,
                'image_url' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Tomat ceri manis segar bernutrisi tinggi hasil budidaya greenhouse hidroponik bebas pestisida kimia sintetis.'
            ],
            [
                'farmer_idx' => 2,
                'name' => 'Kentang Granola Lembang Super (Demo)',
                'category' => 'Palawija',
                'variety' => 'Granola G2',
                'land_area' => 6000,
                'est_harvest' => 8000,
                'price' => 16000,
                'image_url' => 'https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Kentang sayur kualitas premium ukuran seragam, cocok untuk olahan katering, perkedel, dan sayur sop restoran.'
            ],
            [
                'farmer_idx' => 2,
                'name' => 'Wortel Brastagi Manis (Demo)',
                'category' => 'Sayuran Daun',
                'variety' => 'Kuroda Seleksi',
                'land_area' => 3000,
                'est_harvest' => 3200,
                'price' => 14000,
                'image_url' => 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Wortel oranye cerah berkulit mulus dan renyah dengan rasa manis alami kaya vitamin A.'
            ],
            [
                'farmer_idx' => 3,
                'name' => 'Jagung Manis Bonanza F1 (Demo)',
                'category' => 'Palawija',
                'variety' => 'Bonanza 9 F1',
                'land_area' => 7000,
                'est_harvest' => 7500,
                'price' => 9000,
                'image_url' => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Jagung manis bertongkol penuh dengan tingkat kemanisan brix 13-14, sangat diminati katering dan penjual jagung bakar.'
            ],
            [
                'farmer_idx' => 3,
                'name' => 'Kubis Bulat Dataran Tinggi (Demo)',
                'category' => 'Sayuran Daun',
                'variety' => 'Green Nova',
                'land_area' => 3500,
                'est_harvest' => 5000,
                'price' => 8000,
                'image_url' => 'https://images.unsplash.com/photo-1628771065518-0d82f1938462?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Kubis krop padat tanpa cacat ulat, disortir bersih sebelum penimbangan dan pengiriman.'
            ],
            [
                'farmer_idx' => 4,
                'name' => 'Bawang Putih Tunggal Lanang (Demo)',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Lanang Herbal',
                'land_area' => 2000,
                'est_harvest' => 1000,
                'price' => 65000,
                'image_url' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Bawang lanang berkhasiat herbal tinggi, disukai industri minuman kesehatan dan pengolahan jamu.'
            ],
            [
                'farmer_idx' => 4,
                'name' => 'Jahe Merah Gajah Unggulan (Demo)',
                'category' => 'Cabai & Bumbu',
                'variety' => 'Rimpang Merah Tebal',
                'land_area' => 3000,
                'est_harvest' => 2500,
                'price' => 38000,
                'image_url' => 'https://images.unsplash.com/photo-1615485500704-8e990f9900f7?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Rimpang jahe merah segar berumur panen 10 bulan dengan kadar minyak atsiri pekat dan pedas mantap.'
            ],
            [
                'farmer_idx' => 0,
                'name' => 'Brokoli Hijau Dataran Tinggi (Demo)',
                'category' => 'Sayuran Daun',
                'variety' => 'Lucky F1',
                'land_area' => 2500,
                'est_harvest' => 2000,
                'price' => 24000,
                'image_url' => 'https://images.unsplash.com/photo-1459411621453-7b03977f4bfc?w=600&auto=format&fit=crop&q=80',
                'desc' => 'Kuntum bunga brokoli hijau rapat segar, dipetik pagi hari menjelang serah terima kontrak.'
            ],
            [
                'farmer_idx' => 1,
                'name' => 'Labu Madu Butternut Squash (Demo)',
                'category' => 'Buah Segar',
                'variety' => 'Jacqueline F1',
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
            $listings[] = CommodityListing::firstOrCreate(
                ['commodity_name' => $cd['name'], 'user_id' => $farmer->id],
                [
                    'category' => $cd['category'],
                    'variety' => $cd['variety'],
                    'grade' => 'Kualitas A',
                    'land_area_m2' => $cd['land_area'],
                    'estimated_harvest_kg' => $cd['est_harvest'],
                    'available_kg' => $cd['est_harvest'],
                    'price_per_kg' => $cd['price'],
                    'harvest_estimated_date' => Carbon::now()->addDays(45 + ($idx * 5))->toDateString(),
                    'location' => $farmer->location,
                    'image_url' => $cd['image_url'],
                    'description' => $cd['desc'],
                    'status' => 'available',
                ]
            );
        }

        // 5. Buat 15 Batch Panen (Beberapa listing memiliki > 1 batch)
        $batches = [];
        $batchPhases = [
            'Fase Benih & Penyemaian',
            'Fase Vegetatif & Pertunasan',
            'Fase Pembungaan & Bakal Buah',
            'Fase Pematangan & Siap Panen'
        ];

        foreach ($listings as $lIdx => $lst) {
            // Batch Utama
            $bCode = 'BATCH-' . str_pad($lst->id, 4, '0', STR_PAD_LEFT) . '-01';
            $batch1 = HarvestBatch::firstOrCreate(
                ['batch_code' => $bCode],
                [
                    'commodity_listing_id' => $lst->id,
                    'farmer_id' => $lst->user_id,
                    'batch_name' => 'Batch Panen Utama ' . Carbon::now()->format('M Y'),
                    'estimated_harvest_date' => Carbon::now()->addDays(30 + ($lIdx * 3))->toDateString(),
                    'estimated_harvest_kg' => $lst->estimated_harvest_kg * 0.6,
                    'allocated_kg' => 0,
                    'current_phase' => $batchPhases[$lIdx % 4],
                    'current_phase_status' => 'berlangsung',
                    'status' => 'aktif',
                ]
            );
            $batches[] = $batch1;

            // Untuk 3 listing pertama, buat batch kedua (siklus berikutnya)
            if ($lIdx < 3) {
                $bCode2 = 'BATCH-' . str_pad($lst->id, 4, '0', STR_PAD_LEFT) . '-02';
                $batch2 = HarvestBatch::firstOrCreate(
                    ['batch_code' => $bCode2],
                    [
                        'commodity_listing_id' => $lst->id,
                        'farmer_id' => $lst->user_id,
                        'batch_name' => 'Batch Panen Lanjutan ' . Carbon::now()->addMonths(2)->format('M Y'),
                        'estimated_harvest_date' => Carbon::now()->addDays(75 + ($lIdx * 5))->toDateString(),
                        'estimated_harvest_kg' => $lst->estimated_harvest_kg * 0.4,
                        'allocated_kg' => 0,
                        'current_phase' => 'Fase Benih & Penyemaian',
                        'current_phase_status' => 'berlangsung',
                        'status' => 'aktif',
                    ]
                );
                $batches[] = $batch2;
            }
        }

        // Tambahkan dokumentasi perkembangan tanaman untuk batch-batch yang aktif
        foreach ($batches as $b) {
            $existingProgress = PlantingProgress::where('harvest_batch_id', $b->id)->count();
            if ($existingProgress === 0) {
                PlantingProgress::create([
                    'harvest_batch_id' => $b->id,
                    'stage_name' => 'Fase Benih & Penyemaian',
                    'stage_status' => 'selesai',
                    'note' => 'Persemaian bibit varietas unggul telah tumbuh seragam dengan perakaran kuat dan siap pindah tanam ke lahan olahan.',
                    'photo_url' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=600&auto=format&fit=crop&q=80',
                    'location_tag' => 'Lahan Pembibitan Blok A',
                    'reported_at' => Carbon::now()->subDays(25),
                ]);

                if (in_array($b->current_phase, ['Fase Vegetatif & Pertunasan', 'Fase Pembungaan & Bakal Buah', 'Fase Pematangan & Siap Panen'])) {
                    PlantingProgress::create([
                        'harvest_batch_id' => $b->id,
                        'stage_name' => 'Fase Vegetatif & Pertunasan',
                        'stage_status' => 'selesai',
                        'note' => 'Tanaman tumbuh subur dengan percabangan aktif, aplikasi nutrisi organik dan sistem irigasi tetes berjalan lancar.',
                        'photo_url' => 'https://images.unsplash.com/photo-1592417817098-8f3d6eb22521?w=600&auto=format&fit=crop&q=80',
                        'location_tag' => 'Lahan Utama Blok Terbuka',
                        'reported_at' => Carbon::now()->subDays(12),
                    ]);
                }
            }
        }

        // 6. Buat 25 Pesanan Skenario Lengkap
        // Tanggal disebar ke beberapa bulan tahun berjalan (Mei - Oktober) agar grafik & laporan PDF bervariasi
        $ordersScenarios = [
            // 1. Menunggu Persetujuan Petani
            ['bIdx' => 0, 'buyerIdx' => 0, 'qty' => 150, 'status' => 'menunggu_konfirmasi', 'monthsAgo' => 0, 'daysAgo' => 2],
            ['bIdx' => 1, 'buyerIdx' => 1, 'qty' => 50,  'status' => 'menunggu_konfirmasi', 'monthsAgo' => 0, 'daysAgo' => 1],
            ['bIdx' => 2, 'buyerIdx' => 2, 'qty' => 200, 'status' => 'menunggu_konfirmasi', 'monthsAgo' => 0, 'daysAgo' => 3],

            // 2. Menunggu Pembayaran DP oleh Konsumen
            ['bIdx' => 0, 'buyerIdx' => 3, 'qty' => 100, 'status' => 'menunggu_pembayaran_dp', 'monthsAgo' => 0, 'daysAgo' => 5],
            ['bIdx' => 3, 'buyerIdx' => 4, 'qty' => 80,  'status' => 'menunggu_pembayaran_dp', 'monthsAgo' => 1, 'daysAgo' => 6],
            ['bIdx' => 4, 'buyerIdx' => 0, 'qty' => 250, 'status' => 'menunggu_pembayaran_dp', 'monthsAgo' => 1, 'daysAgo' => 8],

            // 3. Bukti DP Telah Diunggah (Menunggu Verifikasi Petani)
            ['bIdx' => 1, 'buyerIdx' => 2, 'qty' => 120, 'status' => 'menunggu_verifikasi_dp', 'monthsAgo' => 0, 'daysAgo' => 4],
            ['bIdx' => 5, 'buyerIdx' => 1, 'qty' => 100, 'status' => 'menunggu_verifikasi_dp', 'monthsAgo' => 2, 'daysAgo' => 10],
            ['bIdx' => 6, 'buyerIdx' => 3, 'qty' => 300, 'status' => 'menunggu_verifikasi_dp', 'monthsAgo' => 2, 'daysAgo' => 12],

            // 4. DP Telah Dikonfirmasi Petani (Pesanan Aktif Budidaya)
            ['bIdx' => 0, 'buyerIdx' => 4, 'qty' => 200, 'status' => 'pesanan_aktif', 'monthsAgo' => 1, 'daysAgo' => 20],
            ['bIdx' => 2, 'buyerIdx' => 0, 'qty' => 180, 'status' => 'pesanan_aktif', 'monthsAgo' => 2, 'daysAgo' => 18],
            ['bIdx' => 7, 'buyerIdx' => 1, 'qty' => 150, 'status' => 'pesanan_aktif', 'monthsAgo' => 3, 'daysAgo' => 15],
            ['bIdx' => 8, 'buyerIdx' => 2, 'qty' => 40,  'status' => 'pesanan_aktif', 'monthsAgo' => 3, 'daysAgo' => 22],

            // 5. Pesanan Siap Panen
            ['bIdx' => 3, 'buyerIdx' => 3, 'qty' => 100, 'status' => 'siap_panen', 'monthsAgo' => 2, 'daysAgo' => 25],
            ['bIdx' => 9, 'buyerIdx' => 4, 'qty' => 90,  'status' => 'siap_panen', 'monthsAgo' => 3, 'daysAgo' => 28],
            ['bIdx' => 10, 'buyerIdx' => 0, 'qty' => 110, 'status' => 'siap_panen', 'monthsAgo' => 4, 'daysAgo' => 14],

            // 6. Dalam Pengiriman
            ['bIdx' => 4, 'buyerIdx' => 1, 'qty' => 200, 'status' => 'dalam_pengiriman', 'monthsAgo' => 1, 'daysAgo' => 7],
            ['bIdx' => 6, 'buyerIdx' => 2, 'qty' => 150, 'status' => 'dalam_pengiriman', 'monthsAgo' => 2, 'daysAgo' => 9],

            // 7. Pesanan Selesai (Sukses & Tuntas)
            ['bIdx' => 2, 'buyerIdx' => 3, 'qty' => 300, 'status' => 'selesai', 'monthsAgo' => 4, 'daysAgo' => 30],
            ['bIdx' => 5, 'buyerIdx' => 4, 'qty' => 150, 'status' => 'selesai', 'monthsAgo' => 5, 'daysAgo' => 20],
            ['bIdx' => 7, 'buyerIdx' => 0, 'qty' => 200, 'status' => 'selesai', 'monthsAgo' => 5, 'daysAgo' => 25],
            ['bIdx' => 11, 'buyerIdx' => 1, 'qty' => 120, 'status' => 'selesai', 'monthsAgo' => 4, 'daysAgo' => 18],

            // 8. Pesanan Ditolak atau Dibatalkan
            ['bIdx' => 1, 'buyerIdx' => 3, 'qty' => 1000, 'status' => 'dibatalkan', 'monthsAgo' => 2, 'daysAgo' => 14, 'reason' => 'Kapasitas sisa kuota batch panen pada tanggal permintaan tidak mencukupi.'],
            ['bIdx' => 8, 'buyerIdx' => 4, 'qty' => 500,  'status' => 'dibatalkan', 'monthsAgo' => 3, 'daysAgo' => 16, 'reason' => 'Lokasi tujuan pengiriman belum terjangkau ekspedisi logistik khusus produk segar.'],
            ['bIdx' => 10, 'buyerIdx' => 2, 'qty' => 800,  'status' => 'dibatalkan', 'monthsAgo' => 5, 'daysAgo' => 12, 'reason' => 'Konsumen membatalkan pemesanan sebelum pembayaran uang muka DP.'],
        ];

        foreach ($ordersScenarios as $i => $sc) {
            $batch = $batches[$sc['bIdx'] % count($batches)];
            $listing = $batch->commodityListing;
            $farmer = $batch->farmer;
            $buyer = $buyers[$sc['buyerIdx']];

            $orderCode = 'PO-DEMO-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $qty = (float) $sc['qty'];
            $price = (float) $listing->price_per_kg;
            $totalAmount = $qty * $price;
            $dpAmount = round($totalAmount * 0.30);
            $sisaAmount = $totalAmount - $dpAmount;

            $createdAt = Carbon::now()->subMonths($sc['monthsAgo'])->subDays($sc['daysAgo']);
            $confirmedAt = null;
            $dpUploadedAt = null;
            $dpConfirmedAt = null;
            $completedAt = null;
            $rejectedAt = null;
            $dpProofPath = null;

            if (in_array($sc['status'], ['menunggu_pembayaran_dp', 'menunggu_verifikasi_dp', 'pesanan_aktif', 'siap_panen', 'dalam_pengiriman', 'selesai'])) {
                $confirmedAt = $createdAt->copy()->addHours(6);
            }

            if (in_array($sc['status'], ['menunggu_verifikasi_dp', 'pesanan_aktif', 'siap_panen', 'dalam_pengiriman', 'selesai'])) {
                $dpUploadedAt = $confirmedAt ? $confirmedAt->copy()->addHours(12) : $createdAt->copy()->addHours(12);
                $dpProofPath = 'demo/bukti_transfer_sample.png';
            }

            if (in_array($sc['status'], ['pesanan_aktif', 'siap_panen', 'dalam_pengiriman', 'selesai'])) {
                $dpConfirmedAt = $dpUploadedAt->copy()->addHours(4);
            }

            if ($sc['status'] === 'selesai') {
                $completedAt = $createdAt->copy()->addDays(20);
            }

            if ($sc['status'] === 'dibatalkan') {
                $rejectedAt = $createdAt->copy()->addHours(10);
            }

            $order = PreOrder::firstOrCreate(
                ['order_code' => $orderCode],
                [
                    'commodity_listing_id' => $listing->id,
                    'harvest_batch_id' => $batch->id,
                    'farmer_id' => $farmer->id,
                    'buyer_id' => $buyer->id,
                    'quantity_kg' => $qty,
                    'price_per_kg' => $price,
                    'need_date' => $batch->estimated_harvest_date,
                    'total_amount' => $totalAmount,
                    'dp_amount' => $dpAmount,
                    'escrow_locked_amount' => $sisaAmount,
                    'farmer_bank_name' => $farmer->bank_name,
                    'farmer_bank_account' => $farmer->bank_account,
                    'farmer_bank_holder' => $farmer->bank_holder,
                    'dp_deadline_at' => $confirmedAt ? $confirmedAt->copy()->addDays(2) : null,
                    'dp_proof_path' => $dpProofPath,
                    'dp_uploaded_at' => $dpUploadedAt,
                    'dp_confirmed_at' => $dpConfirmedAt,
                    'status' => $sc['status'],
                    'rejection_reason' => $sc['reason'] ?? null,
                    'rejected_at' => $rejectedAt,
                    'confirmed_at' => $confirmedAt,
                    'completed_at' => $completedAt,
                    'current_stage' => $batch->current_phase,
                    'current_stage_status' => $batch->current_phase_status,
                    'stage_percentage' => $sc['status'] === 'selesai' ? 100 : ($sc['status'] === 'siap_panen' ? 90 : 50),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addHours(8),
                ]
            );

            // Audit Trail / Order Activities
            $existingActs = OrderActivity::where('pre_order_id', $order->id)->count();
            if ($existingActs === 0) {
                OrderActivity::create([
                    'pre_order_id' => $order->id,
                    'activity_type' => 'order_created',
                    'title' => 'Pengajuan Pre-Order Dibuat',
                    'description' => 'Konsumen ' . $buyer->name . ' mengajukan pesanan sejumlah ' . number_format($qty, 0, ',', '.') . ' kg.',
                    'user_id' => $buyer->id,
                    'occurred_at' => $createdAt,
                ]);

                if ($confirmedAt) {
                    OrderActivity::create([
                        'pre_order_id' => $order->id,
                        'activity_type' => 'order_confirmed',
                        'title' => 'Pesanan Disetujui Petani',
                        'description' => 'Petani menyetujui pesanan dan menerbitkan instruksi transfer uang muka DP 30%.',
                        'user_id' => $farmer->id,
                        'occurred_at' => $confirmedAt,
                    ]);
                }

                if ($dpUploadedAt) {
                    OrderActivity::create([
                        'pre_order_id' => $order->id,
                        'activity_type' => 'dp_uploaded',
                        'title' => 'Bukti Transfer DP Diunggah',
                        'description' => 'Konsumen mengunggah bukti pembayaran DP sebesar Rp ' . number_format($dpAmount, 0, ',', '.') . '.',
                        'user_id' => $buyer->id,
                        'occurred_at' => $dpUploadedAt,
                    ]);
                }

                if ($dpConfirmedAt) {
                    OrderActivity::create([
                        'pre_order_id' => $order->id,
                        'activity_type' => 'dp_confirmed',
                        'title' => 'Pembayaran DP Dikonfirmasi Petani',
                        'description' => 'Uang muka telah diterima pada rekening petani. Status pesanan resmi menjadi Aktif Berjalan.',
                        'user_id' => $farmer->id,
                        'occurred_at' => $dpConfirmedAt,
                    ]);
                }

                if ($completedAt) {
                    OrderActivity::create([
                        'pre_order_id' => $order->id,
                        'activity_type' => 'order_completed',
                        'title' => 'Pesanan Selesai Diterima Konsumen',
                        'description' => 'Hasil panen telah diserahterimakan dan dikonfirmasi tuntas oleh konsumen.',
                        'user_id' => $buyer->id,
                        'occurred_at' => $completedAt,
                    ]);
                }

                if ($rejectedAt) {
                    OrderActivity::create([
                        'pre_order_id' => $order->id,
                        'activity_type' => 'order_rejected',
                        'title' => 'Pesanan Dibatalkan / Ditolak',
                        'description' => 'Catatan: ' . ($sc['reason'] ?? 'Pesanan tidak dapat dilanjutkan.'),
                        'user_id' => $farmer->id,
                        'occurred_at' => $rejectedAt,
                    ]);
                }
            }

            // Delivery Tracking untuk pesanan dalam pengiriman atau selesai
            if (in_array($sc['status'], ['dalam_pengiriman', 'selesai'])) {
                DeliveryTracking::firstOrCreate(
                    ['pre_order_id' => $order->id],
                    [
                        'pickup_address' => $farmer->location . ' — Kebun Pertanian Mitra',
                        'destination_address' => $buyer->location . ' — Gudang Penerimaan Bahan Pangan',
                        'distance_km' => 24.5,
                        'estimated_time' => '1 Jam 15 Menit',
                        'fuel_saving_percentage' => 30,
                        'status' => $sc['status'] === 'selesai' ? 'delivered' : 'in_transit',
                        'driver_name' => 'Agus Priyono (Demo)',
                        'driver_vehicle' => 'Armada Pickup TaniLink (N 4125 AB)',
                        'cargo_crates' => ceil($qty / 15),
                        'pickup_time' => $createdAt->copy()->addDays(15),
                    ]
                );
            }
        }
    }
}
