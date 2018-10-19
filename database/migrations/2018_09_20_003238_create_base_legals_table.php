<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseLegalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_legal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresa_id')->unsigned();
            $table->integer('asignaciones_id')->unsigned();
            $table->integer('deducciones_id')->unsigned();
            $table->integer('cesta_ticket_id')->unsigned();
            $table->integer('salario_minimo_id')->unsigned();
            $table->date('desde');
            $table->date('hasta')->nullable();
            $table->string('estatus', 12);

            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresa')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('asignaciones_id')
                ->references('id')
                ->on('asignaciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('deducciones_id')
                ->references('id')
                ->on('deducciones')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('cesta_ticket_id')
                ->references('id')
                ->on('cesta_ticket')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('salario_minimo_id')
                ->references('id')
                ->on('salario_minimo')
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
        Schema::dropIfExists('base_legal');
    }
}
