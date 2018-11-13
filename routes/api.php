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
Route::get('/bases/{id}', 'Api\BasesController@verMod');
Route::get('/bases/ver/{id}', 'Api\BasesController@ver');
Route::post('/bases/{id}', 'Api\BasesController@registrar');
Route::put('/bases/{id}', 'Api\BasesController@modificar');

//Routes para modulo de trabajadores
Route::get('/trabajadores/all/{id}', 'Api\TrabajadorController@verTodos');
Route::post('/trabajadores/{id}', 'Api\TrabajadorController@agregar');

// Routes Nomina
Route::post('nominas/generar/{id}', 'Api\NominaController@generar');


// Prueba wsdl

Route::get('/clima', function () {
    $opts = array(
        'ssl' => array('verify_peer'=>false, 'verify_peer_name'=>false, 'allow_self_signed' => true)
    );
    $params = array ('encoding' => 'UTF-8', 'verifypeer' => false, 'verifyhost' => false, 'soap_version' => SOAP_1_2, 'trace' => 1, 'exceptions' => 1, "connection_timeout" => 180, 'stream_context' => stream_context_create($opts) );
    $url = "http://skycol.com.co:8080/ADInterface/services/ModelADService?wsdl";
    try{
        $client = new SoapClient($url,$params);
        dd($client->__getTypes());
    }
    catch(SoapFault $fault) {
        echo '<br>'.$fault;
    }
});