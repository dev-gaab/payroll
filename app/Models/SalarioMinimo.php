<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarioMinimo extends Model
{
    //tabla
    protected $table = 'salario_minimo';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable = [
    	'monto', 'tipo', 'desde', 'hasta', 'estatus'
    ];
}
