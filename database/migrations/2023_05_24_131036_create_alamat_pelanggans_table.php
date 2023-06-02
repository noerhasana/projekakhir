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
        Schema::create('alamat_pelanggans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('nama');
            $table->text('alamat');
            $table->string('wilayah_provinsi_id', 2);
            $table->string('wilayah_kabupaten_id', 4);
            $table->string('wilayah_kecamatan_id', 7);
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
        Schema::dropIfExists('alamat_pelanggans');
    }
};
