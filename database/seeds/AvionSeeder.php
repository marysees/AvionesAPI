<?php

use Illuminate\Database\Seeder;
//Hace uso del modelo avion
use App\Avion;
//Hace uso del modelo fabricante para saber cuantos fabricantes hay
use App\Fabricante;

//Usamos el Faker que instalamos antes
use Faker\Factory as Faker;

class AvionSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//creamos una instancia de faker
		$faker=Faker::create();
		//Necesitamos saber cuantos fabricantes tenemos
		//Tenemos que llamar al modelo de fabricante 
		$cuantos=Fabricante::all()->count(); // me carga todos los datos de la base de datos de fabricantes  en formato json
		
		//Vamos a crear 20 aviones
		for($i=0;$i<19;$i++){
			//Cuando llamaons al metodo create del Modelo fabricante se está creando una nueva fila en la tabla del fabricante
			//Ver informacion de Active Record - Eloquent ORM
			Avion::create(
					[
						'modelo'=>$faker->word(),
						'longitud'=>$faker->randomFloat(),
						'capacidad'=>$faker->randomNumber(),
						'velocidad'=>$faker->randomNumber(),
						'alcance'=>$faker->randomNumber(),
						'fabricante_id'=>$faker->numberBetween(1,$cuantos),
					]
					
					);
		}
	}

}

