<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    public function verTodas()
    {
        $empresas = Empresa::all();

        return response()->json(["empresas" => $empresas]);
    }

    public function ver($id)
    {
        $empresa =  Empresa::find($id);

        return response()->json(["empresa" => $empresa]);
    }
}
