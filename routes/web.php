<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//stuff
// struktur : $router->method('/path', 'NamaController@namaFunction');
// route diurutkan berdasarkan path yg tdk dinamis lalu yg dinamis, diurutkan dgn garis miringnya dari terkecil


// STATIS
//stuff
$router->get('/stuffs', 'StuffController@index');
$router->post('/stuffs/store', 'StuffController@store');
$router->get('/stuffs/trash', 'StuffController@trash');
//user
$router->get('/users', 'UserController@index');
$router->post('/users/store', 'UserController@store');
$router->get('/users/trash', 'UserController@trash');
// inbound
$router->get('/inbound/data', 'InboundStuffController@index');
$router->post('/inbound/store', 'InboundStuffController@store');
$router->get('/inbound/trash', 'InboundStuffController@trash');


// DINAMIS

//stuff
$router->get('/stuffs/{id}', 'StuffController@show');
$router->patch('/stuffs/update/{id}', 'StuffController@update');
$router->delete('/stuffs/delete/{id}', 'StuffController@destroy');
// softDeletes : trash, restore, undo
$router->get('/stuffs/trash/restore/{id}', 'StuffController@restore');
$router->get('/stuffs/trash/permanent-delete/{id}', 'StuffController@permanentDelete');

//user
$router->get('/users/{id}', 'UserController@show');
$router->patch('/users/update/{id}', 'UserController@update');
$router->delete('/users/delete/{id}', 'UserController@destroy');
$router->get('/users/trash/restore/{id}', 'UserController@restore');
$router->get('/users/trash/permanent-delete/{id}', 'UserController@permanentDelete');

//inbound
$router->get('/inbound/{id}', 'InboundStuffController@show');
// $router->patch('/inbound/update/{id}', 'InboundStuffController@update');
$router->delete('/inbound/delete/{id}', 'InboundStuffController@destroy');
// $router->get('/inbound/trash/restore/{id}', 'InboundStuffController@restore');
// $router->get('/inbound/trash/permanent-delete/{id}', 'InboundStuffController@permanentDelete');