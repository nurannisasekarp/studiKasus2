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

$router->group(['middleware' => 'cors'], function ($router) {
    // Products routes
    $router->get('/products', 'ProductController@index'); // menampilkan
    $router->post('/products/create', 'ProductController@store'); // Create produk baru
    $router->get('/products/{id}', 'ProductController@show'); // Cari product dari id nya
    $router->patch('/products/{id}', 'ProductController@update'); // Update/edit product
    $router->delete('/products/{id}', 'ProductController@destroy'); // Delete produc

    // Transactions routes
    $router->get('/transactions', 'TransactionController@index'); // menampilkan
    $router->get('/transactions/{id}', 'TransactionController@show'); // mepnampilih transaction dari id
    $router->patch('/transactions/{id}', 'TransactionController@update'); // Update transaction dari id
    $router->delete('/transactions/{id}', 'TransactionController@destroy'); // Delete transaction
});
