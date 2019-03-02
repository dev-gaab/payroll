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


//Routes para modulo de trabajadores
Route::group(['prefix' => 'trabajadores', 'middleware' => 'auth:api'], function() {
  Route::get('/all/{id}', 'Api\TrabajadorController@verTodos');
  Route::get('/{id}', 'Api\TrabajadorController@ver');
  Route::post('/{id}', 'Api\TrabajadorController@agregar');
  Route::put('/{id}', 'Api\TrabajadorController@modificar');
});


// Routes Nomina
Route::group(['prefix' => 'nominas', 'middleware' => 'auth:api'], function() {
  Route::get('/{id}', 'Api\NominaController@verTodas');
  Route::post('/generar/{id}', 'Api\NominaController@generar');
});
