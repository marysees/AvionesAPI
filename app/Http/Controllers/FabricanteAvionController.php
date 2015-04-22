<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Añadimos las tablas que vamos a usar
use App\Avion;
use App\Fabricante;
use Response;

//Activamos el uso de las funciones de caché
use Illuminate\Support\Facades\Caché;
class FabricanteAvionController extends Controller {
//Creamos un constructor
	public function __construct(){
		//esta lines hace que antes de entrar en los metodos indicados se compruebe que el usuario esté identificado
		$this->middleware('auth.basic',['only'=>['store','update','destroy']]);
		
	}
	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($idFabricante)
	{
		//Mostramos todos los aviones de un fabricante
		//Primero comprobamos si ese fabricante existe
		$fabricante=Fabricante::find($idFabricante);
		if(! $fabricante){
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código'])],404);
		}
		//Caché se actualizará con nuevos datos cada 1 minuto
		//cachefabricantes es la clave con la que se almacenarán los
		//registros obtenidos de Fabricante::all()
		$listaAviones=Cache::remenber('cacheaviones',1,function(){
			return $fabricante->aviones()->get();
		});
		return response()->json(['status'=>'ok', 'data'=>$$listaAviones],200);
		
		
		//return response()->json(['status'=>'ok', 'data'=>$fabricante->aviones()->get()],200);
		//otra forma sería
		//	return response()->json(['status'=>'ok', 'data'=>$fabricante->aviones],200);
				
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($idFabricante,Request $request)
	{
		//Método llamado al hacer un POST
		//Damos de alta un avión de un fabricante 
		
		//Comprobamos que recibimos todos los campos
		if(!$request->input('modelo')|| !$request->input('longitud')|| !$request->input('capacidad')|| !$request->input('velocidad')|| !$request->input('alcance')){
			//No estamos recibiendo los campos necesarios. Devolvemos error
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos para procesar el alta.'])],422);
		}
		//comprobamos si ese fabricante existe
		$fabricante=Fabricante::find($idFabricante);
		if(! $fabricante){
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código'])],404);
		}
		
		//Insertamos los datos recibidos en la tabla
		$nuevoAvion=$fabricante->aviones()->create($request->all());
		//Devolvemos la respuesta  Http 201 (Created) + los datos del nuevo fabricante+ 
		//una cabecera de location
		//la ruta que se pone es la ruta del nuevo recurso creado
		$respuesta=Response::make(json_encode(['data'=>$nuevoAvion]),201)->header('Location', 'http://www.dominio.local/aviones/'.$nuevoAvion->serie)->header('Content-Type','application/json');
		return $respuesta;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($idFabricante, $idAvion, Request $request)
	{//tenemos que pasar dos parametros por los id del fabricante y del avion a actualizar  y los parámetros que vamos a tener para actualizar
		//Gestionamos tanto cosas del fabricante como aviones del fabricante
		//Comprobamos si el fabricante existe
		$fabricante=Fabricante::find($idFabricante);
		if(!$fabricante)
		{
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código'])],404);
		}
		//comprobamos si el avion que buscamos es de ese fabricante
		$avion=$fabricante->aviones()->find($idAvion);
			if(!$avion)
		{
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un avion con ese código asociado a este fabricante'])],404);
		}
		//Si llegamos aqui todo esta ok ahora almacenamos los campos
		//Listado de campos recibidos del formulario de actualización
		$modelo=$request->input('modelo');
		$modelo=$request->input('longitud');
		$modelo=$request->input('capacidad');
		$modelo=$request->input('velocidad');
		$modelo=$request->input('alcance');
		
		//comprobamos el método si es PATCH o PUT
		if($request->method()==='PATCH')//Actualizacion parcial
		{
			$bandera=false;
			//comprobamos campo a campo si hemos recibido datos
			if($modelo)
			{
				//Actualizamos este campo en el modelo Avion
				$avion->modelo=$modelo;
				$bandera=true;
			}
			if($longitud )
			{
				//Actualizamos este campo en el modelo Avion
				$avion->longitud=$longitud;
				$bandera=true;
			}
			
			if($capacidad)
			{
				//Actualizamos este campo en el modelo Avion
				$avion->capacidad=$capacidad;
				$bandera=true;
			}
			
			if($velocidad )
			{
				//Actualizamos este campo en el modelo Avion
				$avion->velocidad=$velocidad;
				$bandera=true;
			}
			
			if($alcance)
			{
				//Actualizamos este campo en el modelo Avion
				$avion->alcance=$alcance;
				$bandera=true;
			}
			
			//Comprobamos la bandera
			if($bandera){
				//Almacenamos los cambios del modelo en la tabla
				$avion->save();
				return response()->json(['status'=>'ok','data'=>$avion],200);
			}else{
				//Codigo 2304 no modificado
				return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se ha modificado ningún dato del avión'])],404);
			}
		}
		
		//Método PUT (actualizacion total)
		//Chequeamos que recibimos todos los campos
		if(!$modelo || !$longitud ||!$capacidad ||!$velocidad ||!$alcance )	
			{
			//Código 422 no se puede procesar por falta de datos
			return response()->json(['errors'=>Array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento'])],422);
		}
		//Actualizamos el modelo avión
		$avion->modelo=$modelo;
		$avion->longitud=$longitud;
		$avion->capacidad=$capacidad;
		$avion->velocidad=$velocidad;
		$avion->alcance=$alcance;
		//Grabamos los datos del modelo en la tabla
		$avion->save();
		return response()->json(['status'=>'ok','data'=>$avion],200);
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($idFabricante, $idAvion)
	{
		//Comprobamos que existe el fabricante
		$fabricante=Fabricante::find($idFabricante);
		if(!$fabricante){
			//devolvemos error codigo Http 404
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra el fabricante con ese código'])],404);
		}
		//Comprobamos si existe o no
		$avion=$fabricante->aviones()->find($idAvion);
		if(!$avion){
			//devolvemos error codigo Http 404
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra el avión con ese código asociado a ese fabricante'])],404);
		}
		//Borramos el avión y devolvemos código 204
		//204 significa "No Content"
		//Este código no muestra texto en el body
		//Si quisieramos ver el mensaje devolvemos un código 200
		$avion->delete();
		return response()->json(['code'=>204,'message'=>'Se ha eliminado correctamente el avion'],204);
		
	}

}
