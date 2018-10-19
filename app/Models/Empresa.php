<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    //tabla
    protected $table = 'empresa';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable =
    	'rif', 'razon_social', 'direccion', 'riesgo_ivss', 'num_afiliacion_ivss', 'num_afiliacion_inces', 'num_afiliacion_faov', 'fecha_inscripcion_ivss', 'estatus'
    ];
}
