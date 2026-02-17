<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessedByToJamaahsTable extends Migration
{
    public function up()
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            // Menambahkan kolom processed_by sebagai foreign key ke tabel users
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
        });
    }

    public function down()
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            // Menghapus kolom processed_by jika rollback
            $table->dropForeign(['processed_by']);
            $table->dropColumn('processed_by');
        });
    }
}
