<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expenses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('report_id')->unsigned();
			$table->integer('owner_id')->unsigned();
			$table->string('title');
			$table->date('date');
			$table->char('currency', 3)->nullable();
			$table->double('amount', 2);
			$table->timestamps();
			
			$table->foreign('owner_id')->references('id')->on('users');
			$table->foreign('report_id')->references('id')->on('expense_reports')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('expenses');
	}

}
