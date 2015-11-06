<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class ExpenseReport extends Model {

	protected $fillable = [
			'title',
			'description',
			'startDate',
			'closed',
			'owner_id',
			'defaultCurrency'
	];

	private $reportTotal;
	private $participantTotalsArr;
	private $participantContributionTotalArr;
	private $participantConsumptionTotalArr;
	private $settlementsNecessary = false; //If all users have paid exactly what they consumed, no settlements are necessary
	
	public function owner() {
		
		return $this->belongsTo('App\User', 'owner_id');
	}
	
	public function users() {
		return $this->belongsToMany('App\User')->withTimestamps();
	}
	
	public function expenses() {
		return $this->hasMany('App\Expense', 'report_id');
	}

	public function settlements() {
		return $this->hasMany('App\Settlement', 'report_id');
	}
	
	public function expenseContributions() {
		return $this->hasmanyThrough('App\ExpenseContribution', 'App\Expense', 'report_id', 'expense_id');
	}
	
	public function reportTotal() {
		
		if($this->reportTotal == null) {
				
			$this->reportTotal = 0;
			foreach($this->expenses as $expense) {
				$this->reportTotal += $expense->amount;
			}
		}
				
		return $this->reportTotal;
	}
	
	public function participantTotal($participantId) {
		
		if($this->participantTotalsArr == null || !array_key_exists($participantId, $this->participantTotalsArr)) {
			$this->participantTotalsArr[$participantId] = 0;
			$this->participantContributionTotalArr[$participantId] = 0;
			$this->participantConsumptionTotalArr[$participantId] = 0;
			foreach($this->expenses as $expense) {
				if($expense->usage_total != 0) {
					$this->participantContributionTotalArr[$participantId] += $expense->participantContribution($participantId);
					$this->participantConsumptionTotalArr[$participantId] += $expense->participantUsage($participantId);
					$this->participantTotalsArr[$participantId] += $expense->participantOwes($participantId);
				}	
			}
		}

		return  $this->participantTotalsArr[$participantId];
	}
	
	public function syncUsers($userIds) {
		
		foreach($this->users as $user) {
			//if user has been removed from the report
			if(!in_array($user->id, $userIds)) {
				$expenseContrisByRemovedUser = $this->expenseContributions->where('participant_id', $user->id);
				foreach($expenseContrisByRemovedUser as $expenseContri)	{
					$expenseContri->expense->removeUser($user->id);
				}
			}
		}
		$this->users()->sync($userIds);
	}
	
	public function ownerTotal() {
		return $this->participantTotal($this->owner_id);
	}
	
	public function participantConsumptionTotal($participantId) {
		$this->participantTotal($participantId);
		return $this->participantConsumptionTotalArr[$participantId];
	}

	public function participantContributionTotal($participantId) {
		$this->participantTotal($participantId);
		return $this->participantContributionTotalArr[$participantId];
	}
	
	/*public function close() {
		$this->closed = true;
		$this->endDate = Carbon\Carbon::now();
		
		$this->save();
	}*/
	
	public function updateStatus($status) {
		//0 - Open; 1- Closed; 2-Setlements determined; 3-Settlements completed
		$this->status = $status;
		if($status==1) {
			$this->closeDate = Carbon\Carbon::now();
		}
		$this->save();
	}
	
	public function oweesAndOwed() {
		$owees = array();
		$owed = array();
		foreach($this->users as $user) {
			if(($pTotal = $this->participantTotal($user->id)) < 0) {
				$owees[$user->id] = [$pTotal, $user];
				$this->settlementsNecessary = true;
			}
			else if($pTotal > 0) {
				$owed[$user->id] = [$pTotal, $user];
				$this->settlementsNecessary = true; // If all amounts are 0, it never gets set to true.
			}
		}
		
		if(($pTotal = $this->ownerTotal()) < 0) {
			$owees[$this->owner_id] = [$pTotal, User::find($this->owner_id)]; 
		}
		else if($pTotal >0) {
			$owed[$this->owner_id] = [$pTotal, User::find($this->owner_id)];
		}
		asort($owees);
		asort($owed);
		
		return compact(['owees', 'owed']);
	}
	
	public function areSettlementsNecessary() {
		//Make sure to call it after oweeAndOwed(). Typically it is and so not doing it again here.
		return $this->settlementsNecessary;
	}
	
	public function updateStatusIfSettlementsAreComplete() {
		if($this->status == 2) {
			$settlements = $this->settlements()->where('completed', 0);
			if($settlements->count() == 0) {
				$this->updateStatus(3);
				return true;
			}
		}
		return false;
	}
}
