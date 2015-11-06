<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CompleteSettlementRequest;
use App\ExpenseReport;
use App\Settlement;
use App\Mailers\AppMailer;
use Illuminate\Pagination\LengthAwarePaginator;

class SettlementController extends Controller {

	public function store($reportId, AppMailer $mailer)
	{
		$report = ExpenseReport::find($reportId);
		$input = Request::all();
		if(session()->has('settlements')) {
			$input = session('settlements');
		}
		
		$oweesAndOwed = $report->oweesAndOwed();
		$owees = $oweesAndOwed["owees"];
		foreach($owees as $oweeKey => $owee) {
			$owed = $oweesAndOwed["owed"];
			foreach($owed as $owedKey => $owed) {
				if(array_key_exists("settlementowee" . $oweeKey . "owed" . $owedKey, $input)) {
					$settlementAmount = $input["settlementowee" . $oweeKey . "owed" . $owedKey];
					Settlement::create([
						'owee_id' => $oweeKey, 
						'owed_id' => $owedKey,
						'report_id' => $report->id,
						'amount' => $settlementAmount
					]);
				}
			}
		}
		$report->updateStatus(2);
		$mailer->sendSettlementsDeterminedNotification($report);
		
		return redirect('settlements/' . $report->id);
	}

	public function show($reportId)
	{
		$authenticatedUser = \Auth::user();
		$selectStmtAuthUser = "select s.id, s.completed, s.amount, s.owee_id, s.owed_id, owee.name as oweeName, owed.name as owedName from settlements s, users owee, users owed where s.owee_id=owee.id and s.owed_id=owed.id and s.report_id = ? and (s.owee_id = ? or s.owed_id = ?) order by owee.name, owed.name";
		$selectStmtOtherUsers = "select s.amount, s.owee_id, s.owed_id, owee.name as oweeName, owed.name as owedName from settlements s, users owee, users owed where s.owee_id=owee.id and s.owed_id=owed.id and s.report_id = ? and (s.owee_id != ? and s.owed_id != ?) order by owee.name, owed.name";
		
		$authUserSettlements = DB::select($selectStmtAuthUser, [$reportId, $authenticatedUser->id, $authenticatedUser->id]);
		$otherUserSettlements = DB::select($selectStmtOtherUsers, [$reportId, $authenticatedUser->id, $authenticatedUser->id]);
		
		//If report has only been closed and the settlements have not been determined
		$report = ExpenseReport::find($reportId);
		if($report->status ==1) {
			if(\Auth::user()->id != $report->owner_id) {
				$messageHeader = 'Settlements for: ' . $report->title;
				$messageBody = 'Settlements for this report have not been determined yet.';
				return view('utilities.displayMessage', compact(['messageHeader', 'messageBody', 'report']));
			}
			
			return redirect('expenseReports/' . $reportId . '/close');
		}
		
		$report = ExpenseReport::find($reportId);
		
		return view('settlements.reportSettlements', compact('authUserSettlements', 'otherUserSettlements', 'report', 'authenticatedUser'));
	}
	
	public function showAll() {
		$authUser = \Auth::user();

		$sortBy = Request::get("sortBy", "counterpartyName");
		$sortOrder = Request::get("sortOrder", "asc");
		$statusTab = Request::get("statusTab");
		$currentPage = Request::get("page", 1);
		$maxPerPage = 6;
		if($statusTab==null) { 
			if(session()->has('statusTab')) {
				$statusTab = session('statusTab');
			} else {
				$statusTab = 0;
			}
		}
		session(['statusTab' => $statusTab]);
		
		$userSettlements = null;
		
		if($statusTab) {
			//To be changed to getting the subset from the db instead of getting everything  and slicing it here
			$allCompletedUserSettlements = $authUser->completedSettlements($sortBy, $sortOrder);
			$completedUserSettlements = array_slice($allCompletedUserSettlements, ($currentPage-1)*$maxPerPage, $maxPerPage);
			$userSettlements = new LengthAwarePaginator($completedUserSettlements, count($allCompletedUserSettlements), $maxPerPage, $currentPage, ['path' => Request::url()]);
				
		} else {
			$userSettlements = $authUser->openSettlements($sortOrder);
		}

		$sortOrder = ($sortOrder=='asc')?'desc':'asc';
		return view('settlements.allSettlements', compact('userSettlements', 'sortBy', 'sortOrder', 'statusTab'));
	}
	
	public function completeReportSettlements($reportId, CompleteSettlementRequest $request, AppMailer $mailer) {
		$input = $request->all();
		$this->completeSettlements($input['settlement_ids'], $mailer);
		
		return redirect('settlements/' . $reportId);
	}
	
	public function completeUserSettlements(CompleteSettlementRequest $request, AppMailer $mailer) {
		$input = $request->all();
		$this->completeSettlements($input['settlement_ids'], $mailer);
		
		return redirect('settlements');
	}

	private function completeSettlements($settlementIds, AppMailer $mailer) {

		//Load reports because its status will be changed in setAsCompplete
		$settlements = Settlement::find($settlementIds)->load('report', 'oweeUser', 'owedUser');
		foreach($settlements as $settlement) {
			$settlement->setAsComplete();
			$mailer->sendSettlementConfirmation(\Auth::user(), $settlement);
		}
		session()->flash("message", "Settlement(s) completed");
	}
}
