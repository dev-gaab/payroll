<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', function () {
    return factory('App\User', 10)->make();
});

// Routes para modulo de empresas
Route::get('/empresas', 'Api\EmpresaController@verTodas');
Route::get('/empresas/{id}', 'Api\EmpresaController@ver');
Route::put('/empresas/{id}', 'Api\EmpresaController@modificar');
Route::post('/empresas', 'Api\EmpresaController@agregar');

// Routes para modulo de bases legales
Route::get('/bases/all/{id}', 'Api\BasesController@verTodas');

//Routes para modulo de trabajadores
Route::get('/trabajadores/all/{id}', 'Api\TrabajadorController@verTodos');
Route::post('/trabajadores/{id}', 'Api\TrabajadorController@agregar');

// Routes Nomina
Route::post('nominas/generar/{id}', 'Api\NominaController@generar');