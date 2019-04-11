<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    //tabla
    protected $table = 'sesiones';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable = [
    	'usuario_id', 'fecha', 'hora_inicio', 'hora_final'
    ];

}
