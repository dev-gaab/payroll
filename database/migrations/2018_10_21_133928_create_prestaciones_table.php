<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trabajador_id')->unsigned();
            $table->integer('a_servicio');
            $table->date('fecha');
            $table->json('montos');
            $table->string('tipo');

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
        Schema::dropIfExists('prestaciones');
    }
}
