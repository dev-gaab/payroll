<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepresentanteLegal extends Model
{
    //tabla
    protected $table = 'representante_legal';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable =
    	'empresa_id', 'nombre', 'apellido', 'cedula', 'estatus', 'desde', 'hasta'
    ];
}
