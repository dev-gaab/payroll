<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignaciones extends Model
{
    //tabla
    protected $table = 'asignaciones';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable =
    	'he_diurnas', 'he_nocturnas', 'domingo_feriados', 'estatus'
    ];
}
