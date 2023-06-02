<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_produk_id')->constrained();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('jumlah_terjual')->default(0);
            $table->unsignedBigInteger('harga');
            $table->string('warna')->nullable();
            $table->foreignId('parent_produk_id')
                ->nullable()
                ->references('id')
                ->on('produks')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->unsignedBigInteger('jumlah_stok');
            $table->json('ukuran');
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
        Schema::dropIfExists('produks');
    }
}
