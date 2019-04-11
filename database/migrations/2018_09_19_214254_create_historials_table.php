<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_procesos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sesion_id')->unsigned();
            $table->json('data');

            $table->foreign('sesion_id')
                ->references('id')
                ->on('sesiones')
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
        Schema::dropIfExists('historial_procesos');
    }
}
