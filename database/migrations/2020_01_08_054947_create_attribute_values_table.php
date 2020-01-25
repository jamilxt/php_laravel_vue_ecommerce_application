<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeValuesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attribute_values', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('attribute_id'); // need data type same as foreign key's table
			$table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
			$table->text('value');
			// without place parameter, it's causing - 500 Server Error + Numeric value out of range: 1264 in mysql
			// price needs to be in verchar type maybe... jamilxt
			$table->decimal('price', 4)->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('attribute_values');
	}
}
