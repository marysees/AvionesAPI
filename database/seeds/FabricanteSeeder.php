<?php

use Illuminate\Database\Seeder;
//Hace uso del modelo fabricante
use App\Fabricante;

//Usamos el Faker que instalamos antes
use Faker\Factory as Faker;

class FabricanteSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//creamos una instancia de faker
		$faker=Faker::create();
		//Vamos a crear 5 fabricantes
		for($i=0;$i<5;$i++){
			//Cuando llamaons al metodo create del Modelo fabricante se está creando una nueva fila en la tabla del fabricante
			//Ver informacion de Active Record - Eloquent ORM
			Fabricante::create(
					[
						'nombre'=>$faker->word(),
						'direccion'=>$faker->word(),
						'telefono'=>$faker->randomNumber()
						]
					
					);
		}
	}

}

