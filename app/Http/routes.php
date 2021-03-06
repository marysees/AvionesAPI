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
 /*Versionado de la Api
 * las rutas quedarán algo como /api/v1.0/rutas existentes....
 */
Route::group(array('prefix'=>'api/v1.0'),function()
{

//Programamos las nuevas rutas que tendrán en cuenta los controlesrs programados en Controllers
//se crea un recurso con dos parametros el primero es la tabla y el segundo el controloer que lo gestiona. Se añade una excepcion con los metodos que no queremos que genere automaticamente ['except'=>['create']](ver nota en FabricanteController en metodo create)
//Ruta /fabricantes/...
Route::resource('fabricantes', 'FabricanteController', ['except'=>['edit','create']]);

//Recurso anidado /fabricantes/xx/aviones. Gestiona todos los métodos menos los que aparecen en except
Route::resource('fabricantes.aviones','FabricanteAvionController',['except'=>['show','edit','create']]);

//Ruta /aviones/....El resto de métodos los gestiona FabricanteAvion
Route::resource('aviones', 'AvionController', ['only'=>['index','show']]);

//creamos la ruta por defecto
Route::get('/', function()
{
	return "Bienvenido API RESTfull de aviones";
});
});//Fin de versionado

//Ruta por defecto
Route::get('/', function()
{
	return "<a href='http://www.dominio.local/api/V1.0'>Por favor acceda a la versión 1.0 de la API.</a>";
});


/*Rutas por defecto
Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/
