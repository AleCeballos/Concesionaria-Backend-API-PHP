<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



//rutas para el controlador de usuarios
Route::post('/api/users','UserController@register');
Route::post('/api/sesions','UserController@login');

//rutas para el controlador de autos
// controlador restful
Route::resource('/api/cars','CarController');