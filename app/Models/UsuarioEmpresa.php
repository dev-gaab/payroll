<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioEmpresa extends Model
{
    //tabla
    protected $table = 'usuario_empresa';

    // campos create_at update_at
    public $timestamps = false;

    // campos que se pueden ingresar y modificar
    protected $fillable = [
    	'usuario_id', 'empresa_id', 'estatus'
    ];
}
