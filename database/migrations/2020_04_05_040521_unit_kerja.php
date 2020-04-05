<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UnitKerja extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_unitkerja', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unit_kode',5);
            $table->string('unit_nama',254);
            $table->string('unit_parent',5)->nullable();
            $table->boolean('unit_jenis')->unsigned();
            $table->boolean('unit_eselon')->unsigned();
            $table->boolean('unit_flag')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_unitkerja');
    }
}
