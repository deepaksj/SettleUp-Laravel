<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ExpenseReport;
use App\User;
use App\Http\Requests\DeleteReportRequest;
use App\Http\Requests\ReportRequest;
use App\Http\Requests\ShowReportRequest;
use App\Http\Requests\CloseReportRequest;
use Request;
use Illuminate\Support\Facades\DB;
use App\Mailers\AppMailer;

class ExpenseReportController extends Controller {

	public function reportList() {
		$user = \Auth::user();
		$sortBy = Request::get("sortBy");
		$sortOrder = Request::get("sortOrder");
		$reportStatus = Request::get("reportStatus");
		if($reportStatus == null) {
			if(session()->has('reportStatus')) {
				$reportStatus = session('reportStatus');
			} else {
				$reportStatus = 0;	
			}
		}
		if($sortBy == null) {
			if($reportStatus == 1) {
				$sortBy = "endDate";
				$sortOrder = "desc";
			}
			else {
				$sortBy = "id";
				$sortOrder = "asc";
			}
				
		}
		session(['reportStatus' => $reportStatus]);
		
		$reports = [];
		if($reportStatus) {
			$reports = $user->closedButUnsettledReports()->orderBy($sortBy, $sortOrder)->paginate(5);
		} else {
			$reports = $user->openReports()->orderBy($sortBy, $sortOrder)->paginate(5);
		}
		if($sortOrder == "asc") {
			$sortOrder = "desc";
		}
		else {
			$sortOrder = "asc";
		}
		
		return view('expenseReports.openIndex', compact('reports', 'sortBy', 'sortOrder', 'reportStatus'));
	}
	
	public function settledReportList() {
		$user = \Auth::user();
		$sortBy = Request::get('sortBy');
		$sortOrder = Request::get('sortOrder');
		
		if($sortBy == null) {
			$sortBy = 'closeDate';
		}
		if($sortOrder == null) {
			$sortOrder = 'desc';
		}
		
		$reports = $user->settledReports()->orderBy($sortBy, $sortOrder)->paginate(5);
		  
		if($sortOrder == 'desc') {
			$sortOrder = 'asc';
		} else {
			$sortOrder = 'desc';
		}
		
		return view('expenseReports.settledIndex', compact('reports', 'sortBy', 'sortOrder'));
	}
	
	public function showReport(ShowReportRequest $request, $id) {
		
		$sortBy = $request->get("sortBy");
		$sortOrder = $request->get("sortOrder");
		$isReportOwner = false;
		if($sortBy == null) {
			$sortBy = "id";
			$sortOrder = "asc";
		}
		$report = ExpenseReport::find($id);
		if($report->owner_id == \Auth::user()->id) {
			$isReportOwner = true;
		}
		$expenses = $report->expenses()->orderBy($sortBy, $sortOrder)->paginate(5);
		if($sortOrder == "asc") {
			$sortOrder = "desc";
		} else {
			$sortOrder = "asc";
		}
		return view('expenseReports.show', compact('report', 'expenses', 'sortBy', 'sortOrder', 'isReportOwner'));
	}
	
	public function createReport() {
		
		$friends = \Auth::user()->friends()->orderBy('name')->get();

		return view('expenseReports.create', compact('friends'));
	}
	
	public function editReport(ShowReportRequest $request, $id) {
		$report = ExpenseReport::find($id);
		$friends = \Auth::user()->friends()->orderBy('name')->get();
		
		return view('expenseReports.update', compact(['report', 'friends']));
	}
	
	public function updateReport(ReportRequest $request, $id) {
		$report = ExpenseReport::findorFail($id);
		$input = $request->all();
		$report->update($input);
		$report->syncUsers($input['friends']);
		
		return redirect('expenseReports/' . $id);
	}
	
	public function storeReport(ReportRequest $request, AppMailer $mailer) {
		$input = $request->all();
		$input['closed'] = false;
		$input['owner_id'] = \Auth::user()->id;
		
		$users = $input['friends'];
		$report = ExpenseReport::create($input);
		$report->users()->attach($users);
		$mailer->sendAddedToReportNotification($report);
		
		return redirect('expenseReports/' . $report->id);
	}
	
	public function deleteReports(DeleteReportRequest $request, AppMailer $mailer) {
		$input = $request->all();
		$reports = ExpenseReport::find($input['report_ids']);
		$reports->load('users');
		foreach($reports as $report) {
			$report->delete();
			$mailer->sendReportDeletedNotification($report);
		}
		//ExpenseReport::destroy($input['report_ids']);
		session()->flash('message', count($input['report_ids']) . " Report(s) deleted");
		
		return redirect('/expenseReports?sortBy=' . $input['sortBy'] . '&sortOrder=' . $input['sortOrder']);
	}
	
	public function closeReport(CloseReportRequest $request, $id, AppMailer $mailer) {
		$report = ExpenseReport::findorFail($id);
		if($report->expenses()->count() == 0) {
			return redirect()->back()->withErrors('Reports with no expenses cannot be closed. Consider deleting it.');
		}
		$report->updateStatus(1);
		$mailer->sendReportClosedNotification($report);
		$oweesAndOwed = $report->oweesAndOwed();
		if(!$report->areSettlementsNecessary()) {
			$messageHeader = 'Settlements for: ' . $report->title;
			$messageBody = 'No settlements are necessary! All users have paid the right amount towards expenses.';
			return view('utilities.displayMessage', compact(['messageHeader', 'messageBody', 'report']));
		}
		$owees = $oweesAndOwed['owees'];
		$oweds = $oweesAndOwed['owed'];
		$settlements = [];
		//If there is only 1 owee or 1 owed, then the settlements are pre-determined
		if(count($owees) == 1 || count($oweds) == 1) {
			foreach ($owees as $owee) {
				foreach($oweds as $owed) {
					$settlements['settlementowee' . $owee[1]->id . 'owed' . $owed[1]->id] = count($oweds)==1?$owee[0]*(-1):$owed[0];
				}
			}
			
			return redirect('/settlements/' . $id . '/add')->with('settlements', $settlements);
		}
		
		return view('expenseReports.close', compact(['report', 'oweesAndOwed']));
	}
}
