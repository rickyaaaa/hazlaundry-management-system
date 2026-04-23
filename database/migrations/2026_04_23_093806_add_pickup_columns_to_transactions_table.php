<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('delivery_type', ['drop_off', 'pickup_delivery'])->default('drop_off')->after('tracking_code');
            $table->text('address')->nullable()->after('delivery_type');
            $table->dateTime('pickup_time')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['delivery_type', 'address', 'pickup_time']);
        });
    }
};
