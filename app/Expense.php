<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model {

	protected $fillable = [
			'title',
			'date',
			'currency',
			'amount',
			'owner_id',
			'report_id',
			'usage_total'
	];
	
	private $participantContributions;
	
	public function expenseReport() {
		
		return $this->belongsTo('App\ExpenseReport', 'report_id');
	}
	
	public function owner() {
		
		return $this->belongsTo('App\User', 'owner_id');
	}

	public function expenseContributions() {
		
		return $this->hasMany('App\ExpenseContribution');
	}
	
	public function participantContribution($participantId) {
		
		$this->participantContributionAndParticipation();
		if($this->participantContributions != null && array_key_exists($participantId, $this->participantContributions)) {

			return $this->participantContributions[$participantId];
		} 
		
		return 0;
	}

	public function participantUsage($participantId) {
	
		$this->participantContributionAndParticipation();
		
		if($this->participantContributions != null && array_key_exists($participantId, $this->participantContributions) && $this->usage_total > 0) {
	
			return $this->amount*$this->participantContributions[$participantId . 'participationRatio']/$this->usage_total;
		}
	
		return 0;
	}
	
	public function participantOwes($participantId) {
		return $this->participantContribution($participantId) - $this->participantUsage($participantId);
	}
	
	public function participationRatio($participantId) {
		
		$this->participantContributionAndParticipation();
		if($this->participantContributions != null && array_key_exists($participantId, $this->participantContributions)) {

			return $this->participantContributions[$participantId . 'participationRatio'];
		} 
		
		return 0;
	}
	
	private function participantContributionAndParticipation() {

		if($this->participantContributions == null) {
			
			$this->usage_total = 0;
			$this->amount = 0;
				
			foreach($this->expenseContributions as $contri) {
				$this->participantContributions[$contri->participant_id] = $contri->amount;
				$this->amount += $contri->amount;
				$this->participantContributions[$contri->participant_id . 'participationRatio'] = $contri->participation_ratio;
				$this->usage_total += $contri->participation_ratio;
			}
		}
	}
	
	public function removeUser($userId) {

		$this->usage_total = 0;
		$this->amount = 0;
	
		foreach($this->expenseContributions as $key => $userContri) {
			//Remove the user's contribution
			if($userContri->participant_id == $userId) {
				$this->expenseContributions->forget($key);
				$userContri->delete();
			}
			else {
				$this->amount += $userContri->amount;
				$this->usage_total += $userContri->participation_ratio;
			}
		}
		
		//If the user to be removed is the sole contributor or user delete the expense
		if($this->amount == 0 || $this->usage_total ==0) {
			$this->delete();
			return;
		}
		
		$this->save();
	}
}
