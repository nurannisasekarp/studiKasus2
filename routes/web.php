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

//index
$router->get('products', 'ProductsController@index');
$router->get('transactions', 'TransactionsController@index');

//create
$router->post('/products', 'ProductsController@store');
<<<<<<< HEAD
$router->post('/transactions/store', 'TransactionsController@store');
=======
$router->post('/transactions', 'TransactionsController@store');
>>>>>>> 992939bb552566f752b511f818005c0230888fc3

//show=view product/transaction
$router->get('/products/{id}', 'ProductsController@show');
$router->get('/transactions/{id}', 'TransactionsController@show');


//delete
$router->delete('/products/delete/{id}', 'ProductsController@destroy');
$router->delete('/transactions/delete/{id}', 'TransactionsController@destroy');


//update
$router->patch('/products/update/{id}', 'ProductsController@update');
$router->patch('/transactions/update/{id}', 'TransactionsController@update');