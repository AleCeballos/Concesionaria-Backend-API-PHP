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





    Route::group(['middleware' => ['cors']], function () {
        //Rutas a las que se permitirá acceso

     //rutas para el controlador de usuarios
         Route::post('/api/users','UserController@register');
         Route::post('/api/sesions','UserController@login');
         Route::resource('/api/cars','CarController');
         

         Route::options('/api/users','UserController@options');
         Route::options('/api/sesions','UserController@options');
         Route::options('/api/cars','UserController@options');
       
    });

