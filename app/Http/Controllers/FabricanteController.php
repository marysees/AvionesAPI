<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

//Cargamos fabricante porque lo usamos mas abajo
use App\Fabricante;
use Response;
//Activamos el uso de las funciones de caché
use Illuminate\Support\Facades\Caché;


class FabricanteController extends Controller {

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
	public function index()
	{
		//return "En el index de fabricante.";
		//Devolvemos un JSON con todos los fabricantes
		//return Fabricante::all();
		
		//Para devolver un JSOn con código de respuesta HTTP
		//para que me salga un código de respuesta usamos el metodo response que devuelve un json con dos parametros el status y el objeto json, por ultimo se pone el código de estado de respuesta(el 200 es una respuesta para ok, para create un 201 etc ver manual)
		//return response()->json(['status'=>'ok', 'data'=>Fabricante::all()],200);
		
		
		//Para añadir la cache (con remenber se pone el nombre de la clave que se  y el tiempo 
		//Caché se actualizará con nuevos datos cada 15 segundos
		//cachefabricantes es la clave con la que se almacenarán los
		//registros obtenidos de Fabricante::all()
		$fabricante=Cache::remenber('cachefabricantes',15/60,function(){
			return Fabricante::all(); 
		});
		//Devolvemos el json usando cache
		return response()->json(['status'=>'ok', 'data'=>$fabricante],200);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	//No se utiliza este método por que se usaria para mostrar un formulario de
	//creación de fabricantes. Y una API REST no hace eso.
	//Para que no salga en la lista tenemos que indicarlo en el fichero route y decirle que esta ruta de creacion de formularuio no aparezca
	/*
	public function create()
	{
		//
	}
	*/
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//Método llamado al hacer un POST
		//Comprobamos que recibimos todos los campos
		if(!$request->input('nombre')|| !$request->input('direccion')|| !$request->input('telefono')){
			//No estamos recibiendo los campos necesarios. Devolvemos error
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos para procesar el alta.'])],422);
		}
		//Insertamos los datos recibidos en la tabla
		$nuevofabricante=Fabricante::create($request->all());
		//Devolvemos la respuesta  Http 201 (Created) + los datos del nuevo fabricante+ 
		//una cabecera de location
		$respuesta=Response::make(json_encode(['data'=>$nuevofabricante]),201)->header('Location', 'http://www.dominio.local/fabricantes/'.$nuevofabricante->id)->header('Content-Type','application/json');
		return $respuesta;
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	//Este metodo se usa para llamar a un fabricante por un id. Nos muestra la informacion de un fabricante con un id concreto
	//corresponde con la ruta /fabricantes/{fabricante}
	public function show($id)
	{		
		$fabricante=Fabricante::find($id);
		//chequeamos si encontro o no el fabricante
		if(!$fabricante){
			//Se devuelve un array con los errores detectados y código 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código'])],404);
		}else{
		return response()->json(['status'=>'ok', 'data'=>$fabricante],200);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//Para ver el formulario
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,Request $request)
	{
		// Vamos a actualizar un fabricante.
		// Comprobamos si el fabricante existe. En otro caso devolvemos error.
		$fabricante=Fabricante::find($id);

		// Si no existe mostramos error.
		if (! $fabricante)
		{
			// Devolvemos error 404.
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código.'])],404);
		}

		// Almacenamos en variables para facilitar el uso, los campos recibidos.
		$nombre=$request->input('nombre');
		$direccion=$request->input('direccion');
		$telefono=$request->input('telefono');

		// Comprobamos si recibimos petición PATCH(parcial) o PUT (Total)
		
		if ($request->method()=='PATCH')
		{
			$bandera=false;

			// Actualización parcial de datos.
			if ($nombre)
			{
				$fabricante->nombre=$nombre;
				$bandera=true;
			}

			// Actualización parcial de datos.
			if ($direccion )
			{
				$fabricante->direccion=$direccion;
				$bandera=true;
			}

			// Actualización parcial de datos.
			if ($telefono )
			{
				$fabricante->telefono=$telefono;
				$bandera=true;
			}

			if ($bandera)
			{
				// Grabamos el fabricante.
				$fabricante->save();

				// Devolvemos un código 200.
				return response()->json(['status'=>'ok','data'=>$fabricante],200);
			}
			else
			{
				// Devolvemos un código 304 Not Modified.
				return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato del fabricante.'])],304);
			}
		}
		
		//Método PUT actualizamos todos los campos
		//Comprobamos que recibmos todos
		
		if(!$nombre || !$direccion || !$telefono) {
			//se devuelve el código 422 Unprocessable Entity
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el proceso.'])],422);
		}
		//Actualizamos los 3 campos
		$fabricante->nombre=$nombre; 
		$fabricante->direccion=$direccion;
		$fabricante->telefono=$telefono;
		//Grabamos el fabricante
		$fabricante->save();
return response()->json(['status'=>'ok','data'=>$fabricante],200);

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//Metodo delete
		//Si no existe se manda un 404
		//Si existe se elimina y se manda un codigo 204 (sin contenido)
		//Borrado de un fabricante
		//Ejemplo: /fabricante/89 por DELETE
		//Comprobamos si existe o no
		$fabricante=Fabricante::find($id);
		if(!$fabricante){
			//devolvemos error codigo Http 404
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra el fabricante con ese código'])],404);
		}
		//Borramos el fabricante y devolvemos código 204
		//204 significa "No Content"
		//Este código no muestra texto en el body
		//Si quisieramos ver el mensaje devolvemos un código 200
		$aviones=$fabricante->aviones;
		//	$aviones=$fabricante->aviones()->get();
		if (sizeof($aviones)>0)
		{
			//Si quisieramos borrar todos los aviones del fabricante sería
			//$fabricante->aviones->delete();
			//Devolvemos un código de conflicto 409
			return response()->json(['errors'=>array(['code'=>409,'message'=>'Este fabricante posees avines y no puede ser eliminado'])],409);
		}		
		//Eliminamos el fabricante si no tiene aviones
		$fabricante->delete();
		return response()->json(['code'=>204,'message'=>'Se ha eliminado correctamente el fabricante'],204);
		
		
		
	}

}
