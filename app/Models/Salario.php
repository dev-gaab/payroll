<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salario extends Model
{
    //tabla
    protected $table = 'salario';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable = [
    	'trabajador_id', 'salario', 'tipo', 'desde', 'hasta', 'estatus'
    ];
}
