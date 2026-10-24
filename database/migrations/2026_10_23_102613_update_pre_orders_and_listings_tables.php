<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commodity_listings', function (Blueprint $table) {
            $table->string('grade')->default('Grade A')->after('variety');
        });

        Schema::table('pre_orders', function (Blueprint $table) {
            $table->date('need_date')->nullable()->after('price_per_kg');
            $table->text('notes')->nullable()->after('need_date');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('rejection_reason');
            $table->timestamp('completed_at')->nullable()->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('commodity_listings', function (Blueprint $table) {
            $table->dropColumn('grade');
        });

        Schema::table('pre_orders', function (Blueprint $table) {
            $table->dropColumn(['need_date', 'notes', 'rejection_reason', 'confirmed_at', 'completed_at']);
        });
    }
};
