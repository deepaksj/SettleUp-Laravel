<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\RegistrationRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\Mailers\AppMailer;
use Illuminate\Http\Request;
use Socialize;

class RegistrationController extends Controller {

	public function register() {
		return view('auth.register');
	}
	
	public function postRegister(RegistrationRequest $request, AppMailer $mailer) {
		
		$user = User::create($request->all());
		
		$mailer->sendEmailConfirmationTo($user);
		
		session()->flash('message', 'Please confirm your email address.');
		
		return redirect()->back();
	}
	
	public function confirmEmail($token) {
		
		$user = User::whereToken($token)->firstOrFail();
		$user->verified = true;
		$user->token = null;
		$user->save();
		
		return redirect('login');
	}
	
	public function redirectToFacebook()
	{
		return Socialize::with('facebook')->redirect();
	}
	
	public function handleFacebookCallback()
	{
		//Since its the same callback function for registration & login. Check prev url and handle appropriately
		$prevUrl = session()->previousUrl();
		$facebookUser = Socialize::with('facebook')->user();
		$appUser = User::whereEmail($facebookUser->getEmail())->get();
		
		if(strpos($prevUrl, "register")) {
			//If user is not already registered, register and login
			if($appUser->count() == 0) {
				$user = User::create([
					'name' => $facebookUser->getName(),
					'email' => $facebookUser->getEmail(),
					'password' => str_random(8)
				]);
				$user->verified = true;
				$user->token = null;
				$user->save();
			} else {
				return redirect()->back()->withErrors('User already exists');
			} 
		} else {
			if($appUser->count() == 0) {
				session()->flash('message', 'User not found.');
				return redirect()->back();
			}
			$user = $appUser[0];
		}
		
		\Auth::loginUsingId($user->id);
		return redirect()->intended();
	}
}
