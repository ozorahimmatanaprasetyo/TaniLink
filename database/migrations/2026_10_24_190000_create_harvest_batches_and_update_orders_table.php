<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create harvest_batches table
        if (!Schema::hasTable('harvest_batches')) {
            Schema::create('harvest_batches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('commodity_listing_id')->constrained('commodity_listings')->onDelete('cascade');
                $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
                $table->string('batch_code')->unique();
                $table->string('batch_name');
                $table->date('estimated_harvest_date');
                $table->integer('estimated_harvest_kg');
                $table->integer('allocated_kg')->default(0);
                $table->string('current_phase')->default('Persiapan & Penanaman');
                $table->string('current_phase_status')->default('berlangsung'); // 'berlangsung', 'selesai'
                $table->string('status')->default('aktif'); // 'aktif', 'siap_panen', 'selesai'
                $table->timestamp('phase1_completed_at')->nullable();
                $table->timestamp('phase2_completed_at')->nullable();
                $table->timestamp('phase3_completed_at')->nullable();
                $table->timestamp('phase4_completed_at')->nullable();
                $table->timestamps();
            });
        }

        // 2. Add batch and DP payment fields to pre_orders
        Schema::table('pre_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_orders', 'harvest_batch_id')) {
                $table->foreignId('harvest_batch_id')->nullable()->after('commodity_listing_id')->constrained('harvest_batches')->nullOnDelete();
            }
            if (!Schema::hasColumn('pre_orders', 'farmer_bank_name')) {
                $table->string('farmer_bank_name')->nullable()->after('escrow_locked_amount');
                $table->string('farmer_bank_account')->nullable()->after('farmer_bank_name');
                $table->string('farmer_bank_holder')->nullable()->after('farmer_bank_account');
                $table->timestamp('dp_deadline_at')->nullable()->after('farmer_bank_holder');
                $table->string('dp_proof_path')->nullable()->after('dp_deadline_at');
                $table->timestamp('dp_uploaded_at')->nullable()->after('dp_proof_path');
                $table->timestamp('dp_confirmed_at')->nullable()->after('dp_uploaded_at');
                $table->text('dp_rejection_note')->nullable()->after('dp_confirmed_at');
            }
        });

        // 3. Add harvest_batch_id to planting_progress and make pre_order_id nullable
        Schema::table('planting_progress', function (Blueprint $table) {
            if (!Schema::hasColumn('planting_progress', 'harvest_batch_id')) {
                $table->foreignId('harvest_batch_id')->nullable()->after('id')->constrained('harvest_batches')->cascadeOnDelete();
            }
        });

        // 4. Data Migration for existing records
        $listings = DB::table('commodity_listings')->get();
        foreach ($listings as $listing) {
            // Check if batch already exists for this listing
            $existingBatch = DB::table('harvest_batches')->where('commodity_listing_id', $listing->id)->first();
            if (!$existingBatch) {
                $harvestDate = $listing->harvest_estimated_date ?: now()->addDays(60)->toDateString();
                $monthYear = date('M Y', strtotime($harvestDate));
                $batchId = DB::table('harvest_batches')->insertGetId([
                    'commodity_listing_id' => $listing->id,
                    'farmer_id' => $listing->user_id,
                    'batch_code' => 'BATCH-' . str_pad($listing->id, 4, '0', STR_PAD_LEFT) . '-01',
                    'batch_name' => 'Batch Panen ' . $monthYear,
                    'estimated_harvest_date' => $harvestDate,
                    'estimated_harvest_kg' => $listing->estimated_harvest_kg ?: 100,
                    'allocated_kg' => max(0, $listing->estimated_harvest_kg - $listing->available_kg),
                    'current_phase' => 'Persiapan & Penanaman',
                    'current_phase_status' => 'berlangsung',
                    'status' => 'aktif',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Link pre_orders for this listing
                $orders = DB::table('pre_orders')->where('commodity_listing_id', $listing->id)->get();
                foreach ($orders as $order) {
                    $farmer = DB::table('users')->where('id', $order->farmer_id)->first();
                    DB::table('pre_orders')->where('id', $order->id)->update([
                        'harvest_batch_id' => $batchId,
                        'farmer_bank_name' => $farmer->bank_name ?? 'Bank BRI',
                        'farmer_bank_account' => $farmer->bank_account ?? '0341-01-002341-50-8',
                        'farmer_bank_holder' => $farmer->name ?? 'Kelompok Tani',
                    ]);

                    // Link planting progress
                    DB::table('planting_progress')->where('pre_order_id', $order->id)->update([
                        'harvest_batch_id' => $batchId,
                    ]);
                }
            }
        }

        // Map stage names in planting_progress
        $stageMapping = [
            'Fase Benih & Penyemaian' => 'Persiapan & Penanaman',
            'Fase Vegetatif & Pertunasan' => 'Pertumbuhan Tanaman',
            'Fase Pembungaan & Bakal Buah' => 'Menjelang Panen',
            'Fase Pematangan & Siap Panen' => 'Siap Panen',
        ];
        foreach ($stageMapping as $oldStage => $newStage) {
            DB::table('planting_progress')->where('stage_name', $oldStage)->update([
                'stage_name' => $newStage,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('planting_progress', 'harvest_batch_id')) {
            Schema::table('planting_progress', function (Blueprint $table) {
                $table->dropForeign(['harvest_batch_id']);
                $table->dropColumn('harvest_batch_id');
            });
        }

        Schema::table('pre_orders', function (Blueprint $table) {
            if (Schema::hasColumn('pre_orders', 'harvest_batch_id')) {
                $table->dropForeign(['harvest_batch_id']);
                $table->dropColumn([
                    'harvest_batch_id',
                    'farmer_bank_name',
                    'farmer_bank_account',
                    'farmer_bank_holder',
                    'dp_deadline_at',
                    'dp_proof_path',
                    'dp_uploaded_at',
                    'dp_confirmed_at',
                    'dp_rejection_note',
                ]);
            }
        });

        Schema::dropIfExists('harvest_batches');
    }
};

