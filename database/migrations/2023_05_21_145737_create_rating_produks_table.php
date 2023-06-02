<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingProduksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->unsignedTinyInteger('rating');
            $table->unsignedBigInteger('jumlah')->default(0);
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
        Schema::dropIfExists('rating_produks');
    }
}
