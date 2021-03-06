<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Avion;
//Activamos el uso de las funciones de caché
use Illuminate\Support\Facades\Caché;

class AvionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//return response()->json(['status'=>'ok', 'data'=>Avion::all()],200);
		//con cache
		
		//Caché se actualizará con nuevos datos cada 5 minutos
		//cachefabricantes es la clave con la que se almacenarán los
		//registros obtenidos de Fabricante::all()
		$avion=Cache::remenber('cacheavion',5,function(){
			return Avion::all(); 
		});
		//Devolvemos el json usando cache
		return response()->json(['status'=>'ok', 'data'=>$avion],200);
	}
	

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	
	public function show($id)
	{
		$avion=Avion::find($id);
		//chequeamos si encontro o no el avion
		if(!$avion){
			//Se devuelve un array con los errores detectados y código 404
			return response()->json(['errors'=>Array(['code'=>404,'message'=>'No se encuentra un avion con ese código'])],404);
		}else{
		return response()->json(['status'=>'ok', 'data'=>$avion],200);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	

}
