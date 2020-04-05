<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JenisUnitkerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_jenis', function (Blueprint $table) {
            $table->increments('jenis_id');
            $table->tinyInteger('jenis_kode')->unsigned();
            $table->string('jenis_nama',100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_jenis');
    }
}
