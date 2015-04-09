<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Fabricante extends Model {

	//Definir la tabla MySQL que usara este modelo
	protected $table="fabricantes";
	//clave primaria de la tabla fabricantes que es id por lo tanto no hay que indicarlo
	//Si no se indica por defecto es un campo llamado id
	//Atributos de la tabla que se pueden rellenar de forma masiva
	protected $fillable=array('nombre', 'direccion', 'telefono');
	//ocultamos los campos de timestamps en las consultas
	protected $hidden=['create_at', 'updated_at'];
	//indicamos la relacion entre tablas .Relacion de fabricantes con aviones
	public function aviones(){
		//la relacion es de 1 a muchos: 1 fabricante tiene muchos aviones
		return $this->hasMany('App\Avion');
	}
	
	
	
}
