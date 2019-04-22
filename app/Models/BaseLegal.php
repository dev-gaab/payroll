<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseLegal extends Model
{
    //tabla
    protected $table = 'base_legal';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable = [
    	'empresa_id', 'asignaciones_id', 'deducciones_id', 'cesta_ticket_id', 'salario_minimo_id', 'desde', 'hasta', 'estatus'
    ];
}
