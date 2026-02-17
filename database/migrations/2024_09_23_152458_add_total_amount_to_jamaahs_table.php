<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalAmountToJamaahsTable extends Migration
{
    public function up()
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->default(0)->after('status'); // Kolom total pembayaran
        });
    }

    public function down()
    {
        Schema::table('jamaahs', function (Blueprint $table) {
            $table->dropColumn('total_amount');
        });
    }
}


