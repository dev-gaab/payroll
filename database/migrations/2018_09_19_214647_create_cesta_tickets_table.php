<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCestaTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cesta_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresa_id')->unsigned();
            $table->float('cantidad');
            $table->float('unidad_tributaria');
            $table->date('desde');
            $table->date('hasta')->nullable();
            $table->string('estatus', 12);

            
            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresa')
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
        Schema::dropIfExists('cesta_ticket');
    }
}
