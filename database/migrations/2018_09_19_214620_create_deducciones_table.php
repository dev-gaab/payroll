<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeduccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deducciones', function (Blueprint $table) {
            $table->increments('id');
            $table->float('ivss');
            $table->float('faov')->nullable();
            $table->float('paro_forzoso')->nullable();
            $table->date('desde');
            $table->date('hasta')->nullable();
            $table->string('estatus', 12);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deducciones');
    }
}
