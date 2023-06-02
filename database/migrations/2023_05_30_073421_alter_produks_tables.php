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
        Schema::table('produks', function (Blueprint $table)
        {
            $table->unsignedInteger('panjang')->default(30);
            $table->unsignedInteger('lebar')->default(30);
            $table->unsignedInteger('tinggi')->default(10);
            $table->unsignedDouble('berat')->default(0.3);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
