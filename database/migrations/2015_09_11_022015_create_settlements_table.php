<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settlements', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('owee_id')->unsigned();
			$table->integer('owed_id')->unsigned();
			$table->integer('report_id')->unsigned();
			$table->double('amount', 2);
			$table->char('currency', 3)->nullable();
			$table->boolean('completed')->default(0);
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
		Schema::drop('settlements');
	}

}
