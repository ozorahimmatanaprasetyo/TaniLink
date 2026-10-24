<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make pre_order_id nullable so batch-based progress works without requiring a pre_order
        // First drop the existing foreign key, then modify the column, then re-add FK
        Schema::table('planting_progress', function (Blueprint $table) {
            // Drop existing foreign key constraint
            try {
                $table->dropForeign(['pre_order_id']);
            } catch (\Exception $e) {
                // FK may not exist if migration ran partially before
            }
        });

        // Modify column to be nullable
        DB::statement('ALTER TABLE planting_progress MODIFY pre_order_id BIGINT UNSIGNED NULL DEFAULT NULL');

        // Re-add foreign key with nullable support
        Schema::table('planting_progress', function (Blueprint $table) {
            $table->foreign('pre_order_id')
                  ->references('id')
                  ->on('pre_orders')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('planting_progress', function (Blueprint $table) {
            try {
                $table->dropForeign(['pre_order_id']);
            } catch (\Exception $e) {
                // ignore
            }
        });

        // Set null values to 0 first (or remove rows) before making NOT NULL
        DB::table('planting_progress')->whereNull('pre_order_id')->delete();

        DB::statement('ALTER TABLE planting_progress MODIFY pre_order_id BIGINT UNSIGNED NOT NULL');

        Schema::table('planting_progress', function (Blueprint $table) {
            $table->foreign('pre_order_id')
                  ->references('id')
                  ->on('pre_orders')
                  ->cascadeOnDelete();
        });
    }
};

