<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguracionesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('configuraciones', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('empresa_id')->unsigned();
      $table->integer('dias_utilidades');
      $table->boolean('ivss');
      $table->boolean('faov');
      $table->boolean('paro_forsozo');

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
    Schema::dropIfExists('configuraciones');
  }
}
