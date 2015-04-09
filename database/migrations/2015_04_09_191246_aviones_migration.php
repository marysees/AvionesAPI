<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AvionesMigration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('aviones', function(Blueprint $table)
		{
			$table->increments('serie');
			$table->string('modelo');
			$table->float('longitud');
			$table->integer('capacidad');
			$table->integer('velocidad');
			$table->integer('alcance');
			//Nos falta por definir el campo del foreing key
			$table->integer('fabricante_id')->unsigned();
			//Automaticamente añadira created_at y update_at al activar la opcion timestamps()
			$table->timestamps();
			//por ultimo definimos la clave foranea
			$table->foreign('fabricante_id')->references('id')->on ('fabricantes');
			
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aviones');
	}

}
