<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionsTable extends Migration
{
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_id')->constrained('jamaahs')->onDelete('cascade'); // Relasi ke jamaah
            $table->foreignId('agen_id')->constrained('users')->onDelete('cascade'); // Relasi ke agen (referensi_id dari users)
            $table->decimal('amount', 10, 2); // Jumlah komisi yang diinput manual oleh admin
            $table->timestamps(); // Timestamps untuk mencatat waktu dibuat dan diperbarui
        });
    }

    public function down()
    {
        Schema::dropIfExists('commissions');
    }
}
