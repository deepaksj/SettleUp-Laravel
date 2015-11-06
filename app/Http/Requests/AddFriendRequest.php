<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddFriendRequest extends Request {

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
			'name' => 'required',
			'email' => 'required|email'
		];
	}

	public function messages() {
		return [
			'name.required' => 'Name is required',
			'email.required' => 'Email is required',
			'email.email' => 'Invalid email'	
		];
	}
}
