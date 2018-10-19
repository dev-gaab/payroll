<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rif', 11);
            $table->string('razon_social');
            $table->text('direccion');
            $table->string('riesgo_ivss');
            $table->string('num_afiliacion_ivss');
            $table->string('num_afiliacion_faov')->nullable();
            $table->string('num_afiliacion_inces')->nullable();
            $table->date('fecha_inscripcion_ivss');
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
        Schema::dropIfExists('empresa');
    }
}
