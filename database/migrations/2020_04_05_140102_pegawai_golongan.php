<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PegawaiGolongan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_gol', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('gol_id')->unsigned();
            $table->string('gol_nama');
            $table->string('gol_pangkat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_gol');
    }
}
