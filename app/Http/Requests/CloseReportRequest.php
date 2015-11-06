<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\ExpenseReport;

class CloseReportRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$reportId = $this->route('id');
		$report = ExpenseReport::find($reportId);
		if($report->owner_id == \Auth::user()->id) {
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
		return [
			//
		];
	}

}
