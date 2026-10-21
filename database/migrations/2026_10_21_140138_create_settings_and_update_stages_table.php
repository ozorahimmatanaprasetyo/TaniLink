<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Settings Table
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });

            // Seed default settings
            DB::table('system_settings')->insert([
                ['key' => 'admin_phone', 'value' => '+62 812-3456-7890', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'admin_email', 'value' => 'support@tanilink.id', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'admin_hours', 'value' => 'Senin - Sabtu (08.00 - 17.00 WIB)', 'created_at' => now(), 'updated_at' => now()],
                ['key' => 'admin_office', 'value' => 'Pusat Operasional TaniLink, Malang, Jawa Timur', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. Add current_stage_status to pre_orders
        if (Schema::hasTable('pre_orders') && !Schema::hasColumn('pre_orders', 'current_stage_status')) {
            Schema::table('pre_orders', function (Blueprint $table) {
                $table->string('current_stage_status')->default('berlangsung')->after('current_stage');
            });
        }

        // 3. Add stage_status to planting_progress
        if (Schema::hasTable('planting_progress') && !Schema::hasColumn('planting_progress', 'stage_status')) {
            Schema::table('planting_progress', function (Blueprint $table) {
                $table->string('stage_status')->default('berlangsung')->after('stage_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('planting_progress') && Schema::hasColumn('planting_progress', 'stage_status')) {
            Schema::table('planting_progress', function (Blueprint $table) {
                $table->dropColumn('stage_status');
            });
        }

        if (Schema::hasTable('pre_orders') && Schema::hasColumn('pre_orders', 'current_stage_status')) {
            Schema::table('pre_orders', function (Blueprint $table) {
                $table->dropColumn('current_stage_status');
            });
        }

        Schema::dropIfExists('system_settings');
    }
};
