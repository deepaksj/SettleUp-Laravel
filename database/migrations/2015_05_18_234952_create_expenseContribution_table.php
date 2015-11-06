<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseContributionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expense_contributions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('participant_id')->unsigned()->index();
			$table->foreign('participant_id')->references('id')->on('users');
			$table->integer('expense_id')->unsigned()->index();
			$table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
			$table->double('amount', 2)->default(0);
			$table->double('participation_ratio')->unsigned()->default(1);
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
		Schema::drop('expense_contributions');
	}

}
