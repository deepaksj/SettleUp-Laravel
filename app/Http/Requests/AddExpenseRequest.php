<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\ExpenseReport;
use App\Expense;
use Carbon;

class AddExpenseRequest extends Request {
	
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$reportId = $this->route('reportId');
		if($reportId==null) {
			$expenseId = $this->route('expenseId');
			$reportId = Expense::find($expenseId)->report_id;
		}
		$report = ExpenseReport::find($reportId);

		if($report->status) {
			return false;
		}
		if($report->users()->get(['id'])->contains(\Auth::user()->id) || $report->owner_id == \Auth::user()->id) {
			return true;
		}
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$reportId = $this->route('reportId');
		if($reportId==null) {
			$expenseId = $this->route('expenseId');
			$reportId = Expense::find($expenseId)->report_id;
		}
		$report = ExpenseReport::find($reportId);
		$amount = 0;
		$participationRatios = 0;
		$input = $this->all();

		$validationRules = [
				'date' => 'required|date|after:' . date('Y-m-d', strtotime($report->startDate . "-1 day")),
				'title' => 'required',
				$report->owner_id => 'numeric|min:0',
				$report->owner_id . 'p' => 'numeric|min:0'
		];
		
		foreach($report->users as $user) {
			$amount += $input[$user->id];
			$participationRatios += $input[$user->id . 'p'];
			$validationRules[$user->id] = 'numeric|min:0';
			$validationRules[$user->id . 'p'] = 'numeric|min:0';
		}
		
		if($amount == 0) {
			$validationRules[$report->owner_id] = 'required|numeric|min:1';
		}
		if($participationRatios == 0) {
			$validationRules[$report->owner_id . 'p'] = 'required|numeric|min:1';
		}
		
		return $validationRules;		
	}
	
	public function messages() {
		$reportId = $this->route('reportId');
		if($reportId==null) {
			$expenseId = $this->route('expenseId');
			$reportId = Expense::find($expenseId)->report_id;
		}
		
		$report = ExpenseReport::find($reportId);
		
		$messages = [
			'title.required' => "Title is required",
			'date.after' => "Expense date should be on or after report start date (" . date('d M Y', strtotime($report->startDate)) . ")",
			$report->owner_id . '.numeric' => 'Contributions should be numeric' ,
			$report->owner_id . '.min' => 'Contributions cannot be negative',
			$report->owner_id . 'p.numeric' => 'Participation Ratios should be numeric' ,
			$report->owner_id . 'p.min' => 'Participation Ratios cannot be negative',
		];

		$amount = 0;
		$participationRatios = 0;
		$input = $this->all();
		
		foreach($report->users as $user) {
			$amount += $input[$user->id];
			$participationRatios += $input[$user->id . 'p'];
			$messages[$user->id . '.numeric'] = 'Contributions should be numeric';
			$messages[$user->id . '.min'] = 'Contributions cannot be negative';
			$messages[$user->id . 'p.numeric'] = 'Participation Ratios should be numeric';
			$messages[$user->id . 'p.min'] = 'Participation Ratios cannot be negative';
		}
		
		if($amount == 0) {
			$messages[$report->owner_id . '.min'] = 'Sum of Contributions cannot be 0';
			$messages[$report->owner_id . '.required'] = 'Sum of Contributions cannot be 0';
		}
		if($participationRatios == 0) {
			$messages[$report->owner_id . 'p.min'] = 'Sum of Participation Ratios cannot be 0';
			$messages[$report->owner_id . 'p.required'] = 'Sum of Participation Ratios cannot be 0';
		}
		
		return $messages;		
	}

}
