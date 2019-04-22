<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacaciones extends Model
{
    //tabla
    protected $table = 'vacaciones';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable = [
    	'trabajador_id', 'anios_servicio', 'desde', 'hasta', 'dias_disfrute', 'bono_vacacional', 'dias_feriados', 'cesta_ticket'
    ];
}
