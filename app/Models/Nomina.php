<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    //tabla
    protected $table = 'nomina';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable =
    	'trabajador_id', 'dias_trabajados', 'he_diurnas', 'he_nocturnas', 'feriados', 'ivss', 'faov', 'paro_forzoso', 'desde', 'hasta', 'tipo', 'estatus', 'vac_colectivas', 'montos'
    ];
}
