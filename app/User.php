<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
	
	public function expenseReportsOwned() {
		
		return $this->hasMany('App\ExpenseReport', 'owner_id');
	}
	
	public function expenseReports() {
		
		return $this->belongsToMany('App\ExpenseReport');
	}
	
	public function unSettledReports() {
		
		//Get all reports for the user - owned + associated
		$expenseReports = ExpenseReport::where('owner_id', '=', $this->id)->orWhereHas('users', function ($query) {
			$query->where('user_id', '=', $this->id);
		});
		//Get open reports + closed but unsettled
		$expenseReports->where('status', '<', 2)->orWhereHas('settlements', function ($query) {
			$query->where('completed', '=', 0);
		});
		
		return $expenseReports;
	}
	
	public function openReports() {
		
		//Get all reports for the user - owned + associated; where status=0
		$expenseReports = ExpenseReport::where('status', 0)
										->where(function ($query) {
											$query->where('owner_id', '=', $this->id)
													->orWhereHas('users', function ($query){
														$query->where('user_id', '=', $this->id);
													});
										});
										
		return $expenseReports;
	}

	public function closedButUnsettledReports() {
		//Get all reports for the user - owned + associated
		$expenseReports = ExpenseReport::where(function ($query) {
											$query->where('owner_id', '=', $this->id)
													->orWhereHas('users', function ($query){
														$query->where('user_id', '=', $this->id);
													});
										//And check for settlement conditions
										})->where(function ($query) {
											$query->where('status', '=', 1)
													->orWhere(function ($query) {
														$query->where('status', '=', 2)
																->whereHas('settlements', function ($query){
																	$query->where('completed', '=', 0)
																			->where(function ($query){
																				$query->where('owee_id', $this->id)
																						->orWhere('owed_id', $this->id);
																			});
																});
													});
										});
		return $expenseReports;
	}
	
	public function settledReports() {
		//Get all reports for the user - owned + associated
		$expenseReports = ExpenseReport::where(function ($query) {
											$query->where('owner_id', '=', $this->id)
													->orWhereHas('users', function ($query){
														$query->where('user_id', '=', $this->id);
													});
										//And check for settlement conditions
										})->where(function ($query) {
											//Report is completely settled
											$query->where('status', '=', 3)
													//Or if status=2, there are no incomplete settlements for this user
													->orWhere(function ($query) {
														$query->where('status', '=', 2)
																->whereDoesntHave('settlements', function ($query) {
																	$query->where('completed', '=', 0)
																			->where(function ($query) {
																				$query->where('owee_id', '=', $this->id)
																						->orWhere('owed_id', '=', $this->id);
																			});
																});
													});
										});
		return $expenseReports;
	}
	
	public function settlementsAsOwee() {
		
		return $this->hasMany('App\Settlement', 'owee_id');
	}
	
	public function settlementsAsOwed() {
		
		return $this->hasMany('App\Settlement', 'owed_id');
	}
	
	/*public function openSettlements() {
		$settlements = Settlement::where('completed', 0)
									->where(function($query) {
										$query->where('owee_id', $this->id)
												->orWhere('owed_id', $this->id);
									});
		return $settlements;
	}*/

	public function openSettlements($sortOrder) {
		return $this->getSettlements(null, $sortOrder);
	}
	
	public function completedSettlements($sortBy=null, $sortOrder=null) {
		return $this->getSettlements($sortBy, $sortOrder, 1);
	}
	
	private function getSettlements($sortBy=null, $sortOrder=null, $settlementStatus=0) {
		if($sortBy==null || $sortBy != 'reportTitle') {
			$sortBy = 'counterpartyName';
		}
		if($sortOrder==null || $sortOrder!='desc') {
			$sortOrder = 'asc';
		}
		$selectStmt = "(select settlements.id, users.name as counterpartyName, users.id as counterpartyId, expense_reports.closeDate, 
						expense_reports.title as reportTitle, settlements.amount, owee_id, owed_id from settlements 
						inner join users on users.id=owee_id
						inner join expense_reports on report_id=expense_reports.id 
						where owed_id=? and completed=?) union all
				 
						(select settlements.id, users.name as counterpartyName, users.id as counterpartyId, expense_reports.closeDate, 
						expense_reports.title as counterparty, settlements.amount, owee_id, owed_id from settlements 
						inner join users on users.id=owed_id
						inner join expense_reports on expense_reports.id=report_id 
						where owee_id=? and completed=?) order by " . $sortBy . " " . $sortOrder . " LIMIT 1000";
		
		$settlements = DB::select($selectStmt, [$this->id, $settlementStatus, $this->id, $settlementStatus]);
		
		return $settlements;
	}
	
	public function friends() {
		
		return $this->belongsToMany('App\User', 'friend_relationships', 'user_id', 'friend_id');
	}
	
	//Model events for creating verification token
	public static function boot() {
		parent::boot();
		
		static::creating(function($user) {
			$user->token = str_random(30);
		});
	}
	
	public function setPasswordAttribute($password) {
		$this->attributes['password'] = bcrypt($password);
	}
}
