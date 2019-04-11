<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    //tabla
    protected $table = 'trabajador';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable = [
    	'empresa_id', 'cedula', 'cedula', 'nombres', 'apellidos', 'cargo', 'fecha_nacimiento', 'sexo', 'direccion', 'telefono_fijo', 'telefono_celular', 'fecha_ingreso', 'fecha_egreso', 'estatus'
    ];
}
