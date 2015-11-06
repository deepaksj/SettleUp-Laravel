<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\ExpenseReport;

class ReportRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$reportId = $this->route('id');
		
		if($reportId != null) {
			$report = ExpenseReport::findOrFail($reportId);
			if($report->owner_id != \Auth::user()->id) {
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
			'title' => 'required',
			'startDate' => 'required|date',
			'friends' => 'required'
		];
	}
	
	public function messages() {
		return [
			'title.required' => 'Please enter a Title for the report',
			'startDate.required' => 'Please enter a Start Date for the report',
			'startDate.date' => 'Please enter a valid Start Date for the report',
			'friends.required' => 'Please select at least 1 Friend'
		];
	}

}
