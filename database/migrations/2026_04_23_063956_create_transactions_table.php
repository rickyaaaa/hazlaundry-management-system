<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code', 20)->unique();
            $table->string('customer_name');
            $table->string('phone_number', 20);
            $table->foreignId('service_id')->constrained('laundry_services');
            $table->decimal('weight', 8, 2); // kg
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('Diproses');
            // Status: Diproses, Dicuci, Dikeringkan, Disetrika, Selesai, Diambil
            $table->string('payment_status')->default('belum_bayar');
            // payment_status: lunas, belum_bayar
            $table->text('notes')->nullable();
            $table->timestamp('estimated_completion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
