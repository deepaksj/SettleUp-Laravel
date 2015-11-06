<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Http\RedirectResponse;

class SessionsController extends Controller {

	public function login() {
		
		return view('auth.login');
	}
	
	public function postLogin(Request $request) {
		
		$this->validate($request, ['email' => 'required|email', 'password' => 'required']);
		
		if(Auth::attempt($this->getCredentials($request))) {
			return redirect()->intended('/expenseReports');
		}
		
		return redirect()->back()->withInput()->withErrors('User not found or incorrect credentials');
		
	}
	
	public function logout() {
		
		Auth::logout();
		session()->flash('message', 'You are now logged out.');
		
		return redirect('login');
	}
	
	public function getCredentials(Request $request) {
		
		return [
			'email' => $request->input('email'),
			'password' => $request->input('password'),
			'verified' => true
		];
	}

}
