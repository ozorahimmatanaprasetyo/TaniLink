<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('help_topics', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->enum('target_role', ['semua', 'petani', 'konsumen'])->default('semua');
            $table->integer('order_num')->default(0);
            $table->timestamps();
        });

        // Insert initial topics (tanpa menyebut kurir pengiriman)
        DB::table('help_topics')->insert([
            [
                'question' => 'Bagaimana skema pembayaran DP 30% dan Escrow 70% bekerja?',
                'answer' => 'Saat konsumen menyetujui kontrak pre-order, 30% dari total nilai kontrak langsung diteruskan ke rekening petani sebagai modal benih, pupuk, dan perawatan lahan. Sisa 70% diamankan di rekening escrow bersama TaniLink dan baru dicairkan setelah hasil panen diterima dan diverifikasi kualitasnya.',
                'target_role' => 'semua',
                'order_num' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Apa keuntungan sistem kontrak tetap (fixed-price)?',
                'answer' => 'Harga panen disepakati sebelum masa tanam dimulai. Bagi petani, kepastian harga beli terjamin dan tidak anjlok saat panen raya. Bagi konsumen, anggaran pengadaan bahan baku stabil tanpa terdampak fluktuasi harga pasar mendadak.',
                'target_role' => 'semua',
                'order_num' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bagaimana pemantauan perkembangan hasil tanam dilakukan?',
                'answer' => 'Petani berkewajiban mengunggah foto lapangan dan catatan kondisi tanaman di tiap fase tanam (Penyemaian, Vegetatif, Pembungaan, hingga Panen). Konsumen dapat memantau dokumentasi ini secara langsung di menu Pesanan Saya.',
                'target_role' => 'konsumen',
                'order_num' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bagaimana serah terima hasil panen dilakukan?',
                'answer' => 'Serah terima hasil panen dilakukan melalui pengambilan mandiri langsung di lokasi lahan petani atau kesepakatan pengantaran langsung antara petani dan konsumen saat panen tiba. TaniLink tidak menyediakan kurir pengiriman pihak ketiga.',
                'target_role' => 'semua',
                'order_num' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bagaimana cara menambahkan komoditas baru bagi Petani?',
                'answer' => 'Masuk ke menu Komoditas Saya lalu klik Tambah Komoditas. Masukkan nama tanaman, varietas, luas lahan, estimasi hasil panen, target tanggal panen, serta foto tanaman. Penawaran Anda akan langsung tayang di marketplace.',
                'target_role' => 'petani',
                'order_num' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('help_topics');
    }
};
