<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRatingProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rating_produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_pemesanan_id')->constrained();
            $table->foreignId('produk_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->foreignId('user_id')->constrained();
            $table->unsignedTinyInteger('rating');
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
        Schema::dropIfExists('user_rating_produks');
    }
}
