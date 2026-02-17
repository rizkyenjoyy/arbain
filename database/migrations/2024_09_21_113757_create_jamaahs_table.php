<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJamaahsTable extends Migration
{
    public function up()
    {
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_paspor');
            $table->string('nik'); // Kolom NIK
            $table->string('tempat_lahir'); // Kolom Tempat Lahir
            $table->date('tanggal_lahir'); // Kolom Tanggal Lahir
            $table->enum('jenis_kelamin', ['L', 'P']); // Kolom Jenis Kelamin
            $table->text('riwayat_penyakit')->nullable(); // Kolom Riwayat Penyakit
            $table->string('alamat');
            $table->string('kontak');
            $table->string('status')->default('pending');
            $table->foreignId('referensi_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jamaahs');
    }
}



