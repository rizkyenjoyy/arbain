<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade'); // Relasi ke jamaah
            $table->decimal('amount', 10, 2); // Jumlah pembayaran
            $table->string('payment_proof')->nullable(); // Bukti pembayaran (link atau path)
            $table->enum('payment_method', ['transfer', 'cash']); // Metode pembayaran
            $table->string('status')->default('pending'); // Status pembayaran
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
