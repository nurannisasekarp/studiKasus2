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

$router->post('/login', 'AuthController@login'); // untuk authtentifikasi
$router->get('/logout', 'AuthController@logout');
$router->get('/profile', 'AuthController@me'); // memunculkan data  Ini biasanya digunakan untuk mengambil data profil pengguna yang sedang masuk atau mengambil informasi terkait autentikasi

//stuff
// struktur : $router->method('/path', 'NamaController@namaFunction');
// route diurutkan berdasarkan path yg tdk dinamis lalu yg dinamis, diurutkan dgn garis miringnya dari terkecil


// STATIS
//stuff
$router->get('/stuffs', 'StuffController@index'); // menampilkan semua data barang
$router->post('/stuffs/store', 'StuffController@store');// untuk menyimpan data barang baru ke dalam database
$router->get('/stuffs/trash', 'StuffController@trash');//  untuk menampilkan daftar barang (stuffs) yang telah dihapus atau dimasukkan ke dalam 'trash'
//user
$router->get('/users', 'UserController@index');// menampilkan data user
$router->post('/users/store', 'UserController@store');// menyimpan data user
$router->get('/users/trash', 'UserController@trash');// menampilkan data user yang telah di hapus
// inbound (data pemasukan)
$router->get('/inbound/data', 'InboundStuffController@index');
$router->post('/inbound/store', 'InboundStuffController@store');
$router->get('/inbound/trash', 'InboundStuffController@trash');
//lending
$router->post('/lendings/store', 'LendingController@store');
$router->get('/lendings', 'LendingController@index');


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
$router->patch('/inbound/update/{id}', 'InboundStuffController@update');
$router->delete('/inbound/delete/{id}', 'InboundStuffController@destroy');
$router->get('/inbound/trash/restore/{id}', 'InboundStuffController@restore');
$router->get('/inbound/trash/permanent-delete/{id}', 'InboundStuffController@permanentDelete');

//lending
$router->delete('/lendings/delete/{id}', 'LendingController@destroy');
$router->get('/lendings/{id}', 'LendingController@show');

//restoration
//buat data restoration
$router->post('/restorations/{lending_id}', 'RestorationController@store');