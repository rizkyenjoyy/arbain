<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Menggunakan UUID untuk ID
            $table->string('type'); // Tipe notifikasi
            $table->morphs('notifiable'); // Morfisme (notifiable_type dan notifiable_id)
            $table->text('data'); // JSON data
            $table->timestamp('read_at')->nullable(); // Kolom untuk waktu saat notifikasi dibaca
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
