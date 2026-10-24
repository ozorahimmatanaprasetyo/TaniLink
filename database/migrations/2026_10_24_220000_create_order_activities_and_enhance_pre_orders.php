<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add missing timestamp columns to pre_orders if needed
        Schema::table('pre_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_orders', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejection_reason');
            }
            if (!Schema::hasColumn('pre_orders', 'final_payment_proof_path')) {
                $table->string('final_payment_proof_path')->nullable()->after('dp_rejection_note');
                $table->timestamp('final_payment_uploaded_at')->nullable()->after('final_payment_proof_path');
                $table->timestamp('final_payment_confirmed_at')->nullable()->after('final_payment_uploaded_at');
            }
        });

        // 2. Create order_activities table for permanent timeline logging
        if (!Schema::hasTable('order_activities')) {
            Schema::create('order_activities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pre_order_id')->constrained('pre_orders')->cascadeOnDelete();
                $table->string('activity_type'); // e.g. order_created, order_confirmed, order_rejected, dp_proof_uploaded, dp_confirmed, dp_rejected, etc.
                $table->string('title');
                $table->text('description')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('occurred_at');
                $table->timestamps();
            });
        }

        // 3. Backfill order_activities for existing pre_orders based on their actual timestamps
        $orders = DB::table('pre_orders')->get();
        foreach ($orders as $order) {
            // Check if already seeded
            $exists = DB::table('order_activities')->where('pre_order_id', $order->id)->exists();
            if ($exists) continue;

            // 1. Pesanan diajukan
            if ($order->created_at) {
                DB::table('order_activities')->insert([
                    'pre_order_id' => $order->id,
                    'activity_type' => 'order_created',
                    'title' => 'Pengajuan Pre-Order',
                    'description' => 'Konsumen mengajukan pesanan sejumlah ' . number_format($order->quantity_kg, 0, ',', '.') . ' kg.',
                    'user_id' => $order->buyer_id,
                    'occurred_at' => $order->created_at,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->created_at,
                ]);
            }

            // 2. Disetujui atau ditolak petani
            if ($order->confirmed_at) {
                DB::table('order_activities')->insert([
                    'pre_order_id' => $order->id,
                    'activity_type' => 'order_confirmed',
                    'title' => 'Pesanan Disetujui Petani',
                    'description' => 'Petani menyetujui jadwal panen dan menerbitkan instruksi pembayaran DP.',
                    'user_id' => $order->farmer_id,
                    'occurred_at' => $order->confirmed_at,
                    'created_at' => $order->confirmed_at,
                    'updated_at' => $order->confirmed_at,
                ]);
            }

            if ($order->status === 'dibatalkan' && $order->rejection_reason) {
                $rejTime = $order->updated_at ?? $order->created_at;
                DB::table('order_activities')->insert([
                    'pre_order_id' => $order->id,
                    'activity_type' => 'order_rejected',
                    'title' => 'Pesanan Ditolak Petani',
                    'description' => 'Alasan: ' . $order->rejection_reason,
                    'user_id' => $order->farmer_id,
                    'occurred_at' => $rejTime,
                    'created_at' => $rejTime,
                    'updated_at' => $rejTime,
                ]);
            }

            // 3. Bukti DP diunggah
            if ($order->dp_uploaded_at) {
                DB::table('order_activities')->insert([
                    'pre_order_id' => $order->id,
                    'activity_type' => 'dp_uploaded',
                    'title' => 'Bukti Transfer DP Diunggah',
                    'description' => 'Konsumen telah mentransfer DP sebesar Rp ' . number_format($order->dp_amount, 0, ',', '.') . ' dan mengunggah bukti pembayaran.',
                    'user_id' => $order->buyer_id,
                    'occurred_at' => $order->dp_uploaded_at,
                    'created_at' => $order->dp_uploaded_at,
                    'updated_at' => $order->dp_uploaded_at,
                ]);
            }

            // 4. DP dikonfirmasi
            if ($order->dp_confirmed_at) {
                DB::table('order_activities')->insert([
                    'pre_order_id' => $order->id,
                    'activity_type' => 'dp_confirmed',
                    'title' => 'Pembayaran DP Dikonfirmasi',
                    'description' => 'Petani telah memeriksa mutasi dan mengonfirmasi penerimaan DP. Kontrak pesanan aktif.',
                    'user_id' => $order->farmer_id,
                    'occurred_at' => $order->dp_confirmed_at,
                    'created_at' => $order->dp_confirmed_at,
                    'updated_at' => $order->dp_confirmed_at,
                ]);
            }

            // 5. Pesanan selesai
            if ($order->completed_at) {
                DB::table('order_activities')->insert([
                    'pre_order_id' => $order->id,
                    'activity_type' => 'order_completed',
                    'title' => 'Pesanan Selesai & Diterima Konsumen',
                    'description' => 'Konsumen telah menerima hasil panen komoditas dengan baik. Transaksi selesai.',
                    'user_id' => $order->buyer_id,
                    'occurred_at' => $order->completed_at,
                    'created_at' => $order->completed_at,
                    'updated_at' => $order->completed_at,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_activities');

        Schema::table('pre_orders', function (Blueprint $table) {
            if (Schema::hasColumn('pre_orders', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            if (Schema::hasColumn('pre_orders', 'final_payment_proof_path')) {
                $table->dropColumn(['final_payment_proof_path', 'final_payment_uploaded_at', 'final_payment_confirmed_at']);
            }
        });
    }
};

