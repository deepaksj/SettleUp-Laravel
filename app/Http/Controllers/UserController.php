<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Mailers\AppMailer;
use App\Http\Requests\DeleteFriendRequest;
use App\Http\Requests\AddFriendRequest;
use App\Http\Requests\UpdateAccountRequest;

use Illuminate\Http\Request;

class UserController extends Controller {

	public function quickAddFriend(Request $request, AppMailer $mailer) {  
		
		$this->validate($request, ['email' => 'required|email', 'name' => 'required']);
		
		$input = $request->all();
		$users = User::whereEmail($input['email']);
		$friends = \Auth::user()->friends();
		$userAdded = null;
		$userStatus = 1;//For jquery to take appropriate action
		$message = "";
		
		//If user exists in the system
		if($users->count() > 0) {
			$userAdded = $users->first();
			
			if($friends->find($userAdded['id']) == null) {
				$friends->attach($userAdded);
				$message = $userAdded->name. " (". $userAdded->email .") already exists in the system and has been added as your friend!";
			}
			else {
				$message = $userAdded->name. " (". $userAdded->email .") is already a friend!";
				$userStatus = 0;//For jquery to take appropriate action (do nothing in this case)
			}
		} else {
			$input['password'] = str_random(8);
			$userAdded = User::create($input);
			$mailer->sendEmailInvitation(\Auth::user(), $userAdded);
			$friends->attach($userAdded);
			$message = $userAdded->name. " has been added as your friend and sent an invitation to join.";
			$userStatus=3;
		}
		
		return [$userAdded, $userStatus, $message];
	}
	
	public function addFriend(Request $request, AppMailer $mailer) {

		//If validation fails, then addErrors is set to true to the view knows how to handle it
		session()->flash('addErrors', true);
		$returnArr = $this->quickAddFriend($request, $mailer);
		//If validation is successful, set the flag to false
		session()->flash('addErrors', false);
		session()->flash('message', $returnArr[2]);
		
		return redirect()->back();
	}
	
	public function editAccount() {
		$user = \Auth::user();
		return view('auth.myaccount', compact('user'));
	}
	
	public function updateAccount(UpdateAccountRequest $request, AppMailer $mailer) {
		$input = $request->all();
		$user = \Auth::user();
		$user->name = $input['name'];
		$user->password = $input['password'];
		$user->save();
		
		session()->flash("message", "Account Updated");
		$mailer->sendAccountUpdatedNotification($user);
		
		return redirect("/myAccount");
	}
	
	public function friendsList(Request $request) {
		$sortOrder = $request->get('sortOrder');
		if($sortOrder == null) {
			$sortOrder = 'asc';
		}
		$friends = \Auth::user()->friends()->orderBy('name', $sortOrder)->paginate(5);
		if($sortOrder=='asc') {
			$sortOrder = 'desc';
		} else {
			$sortOrder = 'asc';
		}

		return view('friends.show', compact('friends', 'sortOrder'));
	}
	
	public function deleteFriends(DeleteFriendRequest $request) {
		$input = $request->all();
		\Auth::user()->friends()->detach($input['friend_ids']);
		session()->flash('message', "User deleted from your friend's list");
		
		return redirect('/friends');
	}
}
