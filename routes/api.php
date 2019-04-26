<?php

use Illuminate\Http\Request;

/**
 * TODO:
 *
 * ![] Realizar las rutas para usuario
 * ![] Probar las rutas con POSTMAN antes de con la API.
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function () {
  Route::get('/', 'Api\UserController@all');
  Route::get('/{id}', 'Api\UserController@find');
  Route::put('/{id}', 'Api\UserController@update');
  Route::put('/enable/{id}', 'Api\UserController@enable');
  Route::put('/disable/{id}', 'Api\UserController@disable');
});

// Routes para modulo de empresas

Route::group(['prefix' => 'empresas', 'middleware' => 'auth:api'], function() {
    Route::get('/', 'Api\EmpresaController@verTodas');
    Route::get('/{id}', 'Api\EmpresaController@ver');
    Route::post('/', 'Api\EmpresaController@agregar')->middleware('check.rol');
    Route::put('/{id}', 'Api\EmpresaController@modificar');
    Route::put('/disable/{id}', 'Api\EmpresaController@deshabilitar');
});


//Routes para modulo de trabajadores
Route::group(['prefix' => 'trabajadores', 'middleware' => 'auth:api'], function() {
  Route::get('/all/{id}', 'Api\TrabajadorController@verTodos');
  Route::get('/{id}', 'Api\TrabajadorController@ver');
  Route::post('/{id}', 'Api\TrabajadorController@agregar');
  Route::put('/{id}', 'Api\TrabajadorController@modificar');
  Route::put('/disable/{id}', 'Api\TrabajadorController@inhabilitar');
});


// Routes Nomina
Route::group(['prefix' => 'nominas', 'middleware' => 'auth:api'], function() {
  Route::get('/{id}', 'Api\NominaController@verTodas');
  Route::post('/generar/{id}', 'Api\NominaController@generar');
  Route::get('/validar/trabajadores/{empresa_id}', 'Api\NominaController@validarTrabajadores');
  // Nomina detalle
  Route::get('/detalle/{id}', 'Api\NominaController@allNominaDetalle');
  Route::get('/detalle/find/{id}', 'Api\NominaController@verNominaDetalle');
  Route::put('/detalle/{nomina_id}/{trabajador_id}', 'Api\NominaController@modificarNominaDetalle');
});

// TODO: Agregar las rutas para vacaciones utilidades y prestaciones
Route::get('/vacaciones/validar/{trabajador_id}/{fecha_vacaciones}', 'Api\VacacionesController@validarDisponibilidadVacaciones');
