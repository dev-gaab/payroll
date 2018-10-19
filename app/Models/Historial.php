<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    //tabla
    protected $table = 'historial_procesos';

    // campos create_at update_at
    public $timestamps = false;

	// campos que se pueden ingresar y modificar
    protected $fillable =
    	'sesion_id', 'data'
    ];

}
