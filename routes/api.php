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
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

// Routes para modulo de empresas

Route::group(['prefix' => 'empresas', 'middleware' => 'auth:api'], function() {
    Route::get('/', 'Api\EmpresaController@verTodas');
    Route::get('/{id}', 'Api\EmpresaController@ver');
    Route::post('/', 'Api\EmpresaController@agregar')->middleware('check.rol');
    Route::put('/{id}', 'Api\EmpresaController@modificar');
});


// Routes para modulo de bases legales
Route::get('/bases/all/{id}', 'Api\BasesController@verTodas');
Route::get('/bases/{id}', 'Api\BasesController@verMod');
Route::get('/bases/ver/{id}', 'Api\BasesController@ver');
Route::post('/bases/{id}', 'Api\BasesController@registrar');
Route::put('/bases/{id}', 'Api\BasesController@modificar');

//Routes para modulo de trabajadores
Route::get('/trabajadores/all/{id}', 'Api\TrabajadorController@verTodos');
Route::get('/trabajadores/{id}', 'Api\TrabajadorController@ver');
Route::post('/trabajadores/{id}', 'Api\TrabajadorController@agregar');
Route::put('/trabajadores/{id}', 'Api\TrabajadorController@modificar');

// Routes Nomina
Route::post('nominas/generar/{id}', 'Api\NominaController@generar');
