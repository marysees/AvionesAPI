<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Programamos las nuevas rutas que tendrán en cuenta los controlesrs programados en Controllers
//se crea un recurso con dos parametros el primero es la tabla y el segundo el controloer que lo gestiona. Se añade una excepcion con los metodos que no queremos que genere automaticamente ['except'=>['create']](ver nota en FabricanteController en metodo create)
Route::resource('fabricantes', 'FabricanteController', ['except'=>['create']]);

Route::resource('aviones', 'AvionController');

//creamos la ruta por defecto
Route::get('/', function()
{
	return "Bienvenido API RESTfull de aviones";
});

/*Rutas por defecto
Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/