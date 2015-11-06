<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseContribution extends Model {

	protected $fillable = [
			'participant_id',
			'expense_id',
			'amount',
			'participation_ratio'
	];
	
	public function expense() {
		
		return $this->belongsTo('App\Expense', 'expense_id');
	}

	public function participant() {
		
		return $this->belongsTo('App\User', 'participant_id');
	}
}
