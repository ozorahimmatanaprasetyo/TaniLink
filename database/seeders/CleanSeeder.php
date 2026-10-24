<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\SystemSetting;
use App\Models\HelpTopic;
use App\Models\CategoryProposal;

class CleanSeeder extends Seeder
{
    /**
     * Seeder untuk Kondisi CLEAN:
     * - 1 Akun Admin
     * - 0 Akun Petani
     * - 0 Akun Konsumen
     * - 0 Komoditas & Batch
     * - 0 Pesanan & Pembayaran
     * - Konfigurasi esensial sistem (Kontak admin, kategori dasar, FAQ umum)
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
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

        // 2. Konfigurasi Sistem
        SystemSetting::set('admin_phone', '+62 812-3456-7890');
        SystemSetting::set('admin_email', 'support@tanilink.id');
        SystemSetting::set('admin_hours', 'Senin - Sabtu (08.00 - 17.00 WIB)');

        // 3. Kategori Standar (Berasal dari usulan yang disetujui jika diperlukan)
        $standardCategories = ['Cabai & Bumbu', 'Sayuran Daun', 'Palawija', 'Buah Segar', 'Biji-bijian'];
        $admin = User::where('role', 'admin')->first();
        foreach ($standardCategories as $catName) {
            CategoryProposal::firstOrCreate(
                ['name' => $catName],
                [
                    'user_id' => $admin ? $admin->id : 1,
                    'status' => 'disetujui',
                    'admin_note' => 'Kategori standar platform TaniLink'
                ]
            );
        }

        // 4. FAQ / Topik Bantuan Standar
        $faqs = [
            [
                'target_role' => 'semua',
                'question' => 'Apa itu platform TaniLink?',
                'answer' => 'TaniLink adalah platform penghubung antara petani dan konsumen untuk sistem pre-order komoditas pertanian dengan pembayaran bertahap (DP 30% dan pelunasan saat panen/pengiriman).'
            ],
            [
                'target_role' => 'petani',
                'question' => 'Bagaimana cara menambahkan batch panen komoditas?',
                'answer' => 'Masuk ke menu Komoditas Saya, klik Tambah Komoditas atau pilih komoditas yang sudah ada, lalu buka Kelola Batch untuk menambahkan jadwal panen baru.'
            ],
            [
                'target_role' => 'konsumen',
                'question' => 'Bagaimana mekanisme pembayaran DP pre-order?',
                'answer' => 'Setelah petani menyetujui pesanan Anda, status pesanan berubah menjadi Menunggu DP. Anda dapat mentransfer DP 30% langsung ke rekening petani dan mengunggah bukti transfer.'
            ]
        ];

        foreach ($faqs as $faq) {
            HelpTopic::firstOrCreate(
                ['question' => $faq['question']],
                [
                    'target_role' => $faq['target_role'],
                    'answer' => $faq['answer']
                ]
            );
        }
    }
}
