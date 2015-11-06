<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddExpenseRequest;
use App\Http\Requests\DeleteExpenseRequest;

use App\ExpenseReport;
use App\Expense;
use App\ExpenseContribution;
use Illuminate\Contracts\Logging\Log;

class ExpenseController extends Controller {
	
	public function createExpense($reportId) {
		
		$report = ExpenseReport::find($reportId);
		
		return view('expenses.create', compact('report'));
	}
	
	public function editExpense($expenseId) {
		$expense = Expense::findorfail($expenseId);
		$report = ExpenseReport::findorfail($expense->report_id);
		$expenseEditable = true;
		if($report->status || $expense->owner_id != \Auth::user()->id) {
			$expenseEditable = false;
		}
		
		return view('expenses.update', compact('report', 'expense', 'expenseEditable'));
	}
	
	public function updateExpense($expenseId, AddExpenseRequest $request) {
		$expense = Expense::findorfail($expenseId);
		$report = ExpenseReport::findorfail($expense->report_id);
		$this->modifyAndSaveExpense($expense, $report, $request->all());
		session()->flash('message', 'Expense (' . $expense->title . ') updated!');
		
		return redirect('expenseReports/' . $report->id);
	}

	public function storeExpense($reportId, AddExpenseRequest $request) {
		
		$report = ExpenseReport::find($reportId);
		
		$input = $request->all();
		$input['report_id'] = $reportId;
		$input['owner_id'] = \Auth::user()->id;
		$input['amount'] = 0;
		$input['usage_total'] = 0;
		
		$expense = Expense::create($input);
		$this->modifyAndSaveExpense($expense, $report, $input);
		session()->flash('message', 'Expense (' . $expense->title . ') added!');
				
		return redirect('expenseReports/' . $reportId);
	}
	
	private function modifyAndSaveExpense($expense, $report, $input) {
		ExpenseContribution::where('expense_id', '=', $expense->id)->delete();
		
		$ownerContribution['participant_id'] = $report->owner_id;
		$ownerContribution['expense_id'] = $expense->id;
		$ownerContribution['amount'] = $input[$ownerContribution['participant_id']];
		if($ownerContribution['amount'] == null) {
			$ownerContribution['amount'] = 0;
		}
		$expense->amount = $ownerContribution['amount'];
		$ownerContribution['participation_ratio'] = $input[$ownerContribution['participant_id'] . 'p'];
		if($ownerContribution['participation_ratio'] == null) {
			$ownerContribution['participation_ratio'] = 0;
		}
		$expense->usage_total = $ownerContribution['participation_ratio'];
		
		ExpenseContribution::create($ownerContribution);
		
		foreach($report->users as $user) {
				
			$participantContribution['participant_id'] = $user->id;
			$participantContribution['expense_id'] = $expense->id;
			$participantContribution['amount'] = $input[$user->id];
			if($participantContribution['amount'] == null) {
				$participantContribution['amount'] = 0;
			}
			$participantContribution['participation_ratio'] = $input[$user->id . 'p'];
			if($participantContribution['participation_ratio'] == null) {
				$participantContribution['participation_ratio'] = 0;
			}
		
			ExpenseContribution::create($participantContribution);
			$expense->amount += $participantContribution['amount'];
			$expense->usage_total += $participantContribution['participation_ratio'];
		}
		$expense->date = $input['date'];
		$expense->save();
	}
	
	public function deleteExpense(DeleteExpenseRequest $request) {
		$input = $request->all();
		Expense::destroy($input['expense_ids']);
		session()->flash('message', 'Expense(s) deleted');
		return redirect('expenseReports/' . $input['report_id']);
	}
}
