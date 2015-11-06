<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model {

	protected $fillable = [
		'owee_id',
		'owed_id',
		'report_id',
		'amount'	
	];

	public function report() {
	
		return $this->belongsTo('App\ExpenseReport', 'report_id');
	}
	
	public function oweeUser() {
		return $this->belongsTo('App\User', 'owee_id');
	}

	public function owedUser() {
		return $this->belongsTo('App\User', 'owed_id');
	}
	
	public function setAsComplete() {
		$this->completed = true;
		$this->save();
		$this->report->updateStatusIfSettlementsAreComplete();
	}
}