<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Avion extends Model {
	//Definir la tabla MySQL que usara este modelo
	protected $table="aviones";
	//clave primaria de la tabal aviones que es serie (no id) por lo tanto hay que indicarlo
	//Si no se indica por defecto es un campo llamado id
	protected $primaryKey='serie'; 
	//Atributos de la tabla que se pueden rellenar de forma masiva
	protected $fillable=array('modelo', 'longitud', 'capacidad', 'velocidad', 'alcance');
	//ocultamos los campos de timestamps en las consultas
	protected $hidden=['create_at', 'updated_at'];
	
	//indicamos la relacion entre tablas .Relacion de aviones con fabricantes
		public function fabricante(){
		//la relacion es de 1 a 1: 1 avion pertenece a un fabricante
		return $this->belongsTo('App\Fabricante');
	}

}
