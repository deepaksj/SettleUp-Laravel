<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class DeleteReportRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		
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
			'report_ids' => 'required'
		];
	}
	
	public function messages() {
		return [
			'report_ids.required' => 'No expense reports selected'
		];
	}

}
