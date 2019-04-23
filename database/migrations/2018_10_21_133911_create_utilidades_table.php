<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUtilidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utilidades', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trabajador_id')->unsigned();
            $table->date('fecha');
            $table->date('dias');
            $table->integer('meses_calculados')->nullable();
            $table->string('tipo');
            $table->float('monto');
            $table->float('sd_actual');

            $table->foreign('trabajador_id')
                ->references('id')
                ->on('trabajador')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilidades');
    }
}
