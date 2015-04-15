<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

//Cargamos fabricante porque lo usamos mas abajo
use App\Fabricante;


class FabricanteController extends Controller {

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
		//para que me salga un código de respuesta usamos el metodo response que devuelve un json con dos parametros el satatus y el objeto json, por ultimo se pone el código de estado de respuesta(el 200 es una respuesta para ok, para create un 201 etc ver manual)
		return response()->json(['status'=>'ok', 'data'=>Fabricante::all()],200);
		
		
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
	public function store()
	{
		//
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
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un fabricante con ese código'])],400);
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
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
