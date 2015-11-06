<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Expense;
use App\ExpenseReport;

class DeleteExpenseRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$expenses = $this->input('expense_ids');
		if($expenses != null && count($expenses) > 0) {
			$reportId = Expense::find($expenses[0])->report_id;
			$report = ExpenseReport::find($reportId);
			if($report->status) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'expense_ids' => 'required'
		];
	}
	
	public function messages() {
		return [
			'expense_ids.required' => 'No expenses selected'
		];
	}
}
