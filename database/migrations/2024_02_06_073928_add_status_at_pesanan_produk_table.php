<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pesanan_produk', function (Blueprint $table) {
            $table->string('status')->after('jumlah_produk')->default('Pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pesanan_produk', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
