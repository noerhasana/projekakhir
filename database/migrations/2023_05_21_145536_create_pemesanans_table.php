<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesanansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('alamat_pelanggan_id')->constrained();
            $table->unsignedBigInteger('jumlah_harga_barang');
            $table->unsignedBigInteger('jumlah_ongkir');
            $table->json('pilihan_paket_pengiriman');
            $table->json('detail_pengiriman')->nullable();
            $table->string('kode_status');
            $table->foreign('kode_status')
                ->references('kode')
                ->on('status_pesanans');
            $table->timestamp('pay_at')->nullable();
            $table->timestamp('pickup_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemesanans');
    }
}
