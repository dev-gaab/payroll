<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepresentanteLegalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('representante_legal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresa_id')->unsigned();
            $table->string('nombre', 25);
            $table->string('apellido', 25);
            $table->string('cedula', 10);
            $table->date('desde');
            $table->date('hasta')->nullable();
            $table->string('estatus', 12);

            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresa')
                ->onUpdate('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('representante_legal');
    }
}
