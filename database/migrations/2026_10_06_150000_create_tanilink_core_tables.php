<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Commodity Listings (Input Awal Petani - Marketplace)
        Schema::create('commodity_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('commodity_name');
            $table->string('category')->default('Sayuran'); // 'Cabai & Bumbu', 'Sayuran', 'Palawija', 'Buah'
            $table->string('variety')->nullable();
            $table->integer('land_area_m2');
            $table->integer('estimated_harvest_kg');
            $table->integer('available_kg');
            $table->decimal('price_per_kg', 12, 2);
            $table->date('harvest_estimated_date');
            $table->string('location');
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('available'); // 'draft', 'available', 'booked', 'completed'
            $table->timestamps();
        });

        // 2. Pre-Orders (Kontrak Pembelian Fixed-Price & Escrow)
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('commodity_listing_id')->constrained('commodity_listings')->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->integer('quantity_kg');
            $table->decimal('price_per_kg', 12, 2);
            $table->decimal('total_amount', 14, 2);
            $table->decimal('dp_amount', 14, 2); // 30%
            $table->decimal('escrow_locked_amount', 14, 2); // 70%
            $table->string('status')->default('dp_cair'); // 'menunggu_dp', 'dp_cair', 'tanam_berjalan', 'siap_panen', 'dalam_pengiriman', 'selesai'
            $table->string('current_stage')->default('Fase Pembungaan (60%)');
            $table->integer('stage_percentage')->default(60);
            $table->timestamps();
        });

        // 3. Planting Progress (Dokumentasi Lapangan & Milestone Rencana Tanam Petani)
        Schema::create('planting_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_order_id')->constrained('pre_orders')->onDelete('cascade');
            $table->string('stage_name');
            $table->text('note')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('location_tag')->nullable();
            $table->timestamp('reported_at')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });

        // 4. Delivery Trackings (Status Penjemputan & Rute Petani ke Pembeli)
        Schema::create('delivery_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_order_id')->constrained('pre_orders')->onDelete('cascade');
            $table->string('pickup_address');
            $table->string('destination_address');
            $table->decimal('distance_km', 6, 2)->default(18.0);
            $table->string('estimated_time')->default('45 Menit');
            $table->integer('fuel_saving_percentage')->default(28);
            $table->string('status')->default('scheduled'); // 'scheduled', 'pickup_in_progress', 'cargo_loaded', 'in_transit', 'delivered'
            $table->dateTime('pickup_time')->nullable();
            $table->string('driver_name')->default('Budi Santoso');
            $table->string('driver_vehicle')->default('Pickup L300 (N 8241 AB)');
            $table->integer('cargo_crates')->default(25);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_trackings');
        Schema::dropIfExists('planting_progress');
        Schema::dropIfExists('pre_orders');
        Schema::dropIfExists('commodity_listings');
    }
};

