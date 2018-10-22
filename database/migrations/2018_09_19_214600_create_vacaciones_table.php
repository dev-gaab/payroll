<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trabajador_id')->unsigned();
            $table->integer('a_servicio');
            $table->integer('dias_disfrute');
            $table->integer('bono_vacacional');
            $table->integer('dias_feriados');
            $table->float('cesta_ticket');
            $table->json('montos');
            $table->date('fecha_inicial');
            $table->date('fecha_final');

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
        Schema::dropIfExists('vacaciones');
    }
}
