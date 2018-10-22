<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deducciones extends Model
{
    //tabla
    protected $table = 'deducciones';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable = [
    	'ivss', 'faov', 'paro_forzoso', 'estatus'
    ];
}
