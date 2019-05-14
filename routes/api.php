<?php

use Illuminate\Http\Request;

/**
 * TODO:
 *
 * ![x] Realizar las rutas para usuario
 * ![x] Probar las rutas con POSTMAN antes de con la API.
 */
Route::group(['prefix' => 'auth'], function () {
  Route::post('login', 'AuthController@login');
  Route::post('signup', 'AuthController@signup');

  Route::group(['middleware' => 'auth:api'], function () {
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

Route::group(['prefix' => 'empresas', 'middleware' => 'auth:api'], function () {
  Route::get('/', 'Api\EmpresaController@verTodas');
  Route::get('/{id}', 'Api\EmpresaController@ver');
  Route::post('/', 'Api\EmpresaController@agregar')->middleware('check.rol');
  Route::put('/{id}', 'Api\EmpresaController@modificar');
  Route::put('/disable/{id}', 'Api\EmpresaController@deshabilitar');
});


//Routes para modulo de trabajadores
Route::group(['prefix' => 'trabajadores', 'middleware' => 'auth:api'], function () {
  Route::get('/all/{id}', 'Api\TrabajadorController@verTodos');
  Route::get('/{id}', 'Api\TrabajadorController@ver');
  Route::post('/{id}', 'Api\TrabajadorController@agregar');
  Route::put('/{id}', 'Api\TrabajadorController@modificar');
  Route::put('/disable/{id}', 'Api\TrabajadorController@inhabilitar');
});


// Routes Nomina
Route::group(['prefix' => 'nominas', 'middleware' => 'auth:api'], function () {
  Route::get('/{id}', 'Api\NominaController@verTodas');
  Route::post('/generar/{id}', 'Api\NominaController@generar');
  Route::get('/validar/trabajadores/{empresa_id}', 'Api\NominaController@validarTrabajadores');
  // Nomina detalle
  Route::get('/detalle/{id}', 'Api\NominaController@allNominaDetalle');
  Route::get('/detalle/find/{id}', 'Api\NominaController@verNominaDetalle');
  Route::put('/detalle/{nomina_id}/{trabajador_id}', 'Api\NominaController@modificarNominaDetalle');
});

Route::group(['prefix' => 'vacaciones', 'middleware' => 'auth:api'], function () {
  Route::get('/disponibles/{empresa_id}', 'Api\VacacionesController@trabajadoresDisponibles');
  Route::get('/all/{empresa_id}', 'Api\VacacionesController@index');
  Route::get('/ver/{id}', 'Api\VacacionesController@find');
  Route::post('/calcular', 'Api\VacacionesController@agregar');
  Route::put('/{id}', 'Api\VacacionesController@modificar');
  Route::delete('/{id}', 'Api\VacacionesController@delete');
  // Vacaciones Fraccionadas
  Route::get('/fraccionadas/disponibles/{empresa_id}', 'Api\VacacionesController@vacacionesFracDisponibles');
});

// Route::group(['prefix' => 'prestaciones', 'middleware' => 'auth:api'], function () {
//   Route::get('/{empresa_id}', 'Api\PrestacionesController@todas');
//   Route::get('/disponibles/{empresa_id}', 'Api\PrestacionesController@disponibles');
//   Route::post('/calcular', 'Api\PrestacionesController@calcularPrestaciones');
//   Route::delete('/{id}', 'Api\PrestacionesController@delete');
// });

Route::group(['prefix' => 'utilidades', 'middleware' => 'auth:api'], function () {
  Route::get('/{empresa_id}', 'Api\UtilidadesController@todas');
  Route::get('/disponibles/{empresa_id}', 'Api\UtilidadesController@disponibles');
  Route::post('/calcular/{empresa_id}', 'Api\UtilidadesController@addUtilidades');
  Route::get('/ver/{id}', 'Api\UtilidadesController@addUtilidades');
  Route::delete('/{id}', 'Api\UtilidadesController@delete');
});

Route::group(['prefix' => 'reportes', 'middleware' => 'auth:api'], function () {
  Route::get('/empresas/activas', 'Api\ReportesController@empresasActivas');
  Route::get('/empresas/inactivas', 'Api\ReportesController@empresasInactivas');
  Route::get('/nomina/detalle/{id}', 'Api\ReportesController@nominaUno');
  Route::get('/nominas/all/{id}', 'Api\ReportesController@allNominas');
  Route::get('/vacaciones/{id}', 'Api\ReportesController@reciboVacaciones');
});


Route::group(['prefix' => 'bases', 'middleware' => 'auth:api'], function () {
  Route::get('/', 'Api\BasesController@verActual');
  Route::get('/cesta', 'Api\BasesController@histoCesta');
  Route::get('/salarios', 'Api\BasesController@histoSalario');
  Route::post('/salarios', 'Api\BasesController@updSalario');
  Route::post('/cesta', 'Api\BasesController@updCesta');
});

Route::group(['prefix' => 'historial', 'middleware' => 'auth:api'], function() {
  Route::get('/{id}', 'Api\UserController@verHistorial');
  Route::post('/', 'Api\UserController@guardarProceso');
});
