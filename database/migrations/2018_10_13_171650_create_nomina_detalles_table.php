<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNominaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomina_detalle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trabajador_id')->unsigned();
            $table->integer('nomina_id')->unsigned();
            $table->float('dias_trabajados');
            $table->integer('he_diurnas')->nullable();
            $table->integer('he_nocturnas')->nullable();
            $table->integer('feriados')->nullable();
            $table->boolean('ivss');
            $table->boolean('faov');
            $table->boolean('paro_forzoso');
            $table->boolean('vac_colectivas');
            $table->montos('json');

            $table->foreign('trabajador_id')
                ->references('id')
                ->on('trabajador')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('nomina_id')
                ->references('id')
                ->on('nomina')
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
        Schema::dropIfExists('nomina_detalle');
    }
}
