<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseReportsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expense_reports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('owner_id')->unsigned();
			$table->string('title');
			$table->text('description')->nullable();
			$table->date('startDate');
			$table->date('endDate')->nullable();
			$table->boolean('closed'); //Status of the report - open or close
			$table->date('closeDate')->nullable();
			$table->char('defaultCurrency', 3);
			$table->timestamps();
			
			$table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('expense_reports');
	}

}
