<?php

namespace App\Mailers;

use Illuminate\Contracts\Mail\Mailer;
use App\User;
use App\Settlement;

class AppMailer {
	
	protected $mailer;
	protected $from = "admin@settleup.com";
	protected $to;
	protected $view;
	protected $data = [];
	
	public function __construct(Mailer $mailer) {
		$this->mailer = $mailer;
	}
	
	public function deliver() {
		$this->mailer->send($this->view, $this->data, function($message) {
			$message->from($this->from, "Administrator")
					->to($this->to);
		});
	}
	
	public function sendEmailConfirmationTo(User $user) {
		$this->to = $user->email;
		$this->view = "emails.confirm";
		$this->data = compact("user");
		
		$this->deliver();
	}
	
	public function sendEmailInvitation($fromUser, $toUser) {
		$this->from = $fromUser->email;
		$this->to = $toUser->email;
		$this->view = "emails.invitation";
		$this->data = compact("toUser", "fromUser");
		$this->deliver();
	}
	
	public function sendSettlementConfirmation($fromUser, $settlement) {
		$counterparty = $settlement->oweeUser;
		$counterpartyOwes = true; //A flag to check how owes whom for the view;
		if($settlement->owee_id==$fromUser->id) {
			$counterparty = $settlement->owedUser;
			$counterpartyOwes = false;
		}
		
		$this->from = $fromUser->email;
		$this->to = $counterparty->email;
		$this->view = "emails.settlementConfirmation";
		$this->data = compact("fromUser", "settlement", "counterparty", "counterpartyOwes");
		$this->deliver();
	}
	
	public function sendAddedToReportNotification($report) {

		$this->from = $report->owner->email;
		$this->view = "emails.addedToReportNotification";
		foreach($report->users as $user) {
			$this->to = $user->email;
			$this->data = compact("report", "user");
			$this->deliver();
		}
	}
	
	public function sendReportClosedNotification($report) {
		
		$this->from = $report->owner->email;
		$this->view = "emails.closedReportNotification";
		foreach($report->users as $user) {
			$this->to = $user->email;
			$this->data = compact("report", "user");
			$this->deliver();
		}
	}
	
	public function sendSettlementsDeterminedNotification($report) {
		$settlementMessages = [];
		foreach($report->settlements as $settlement) {
			array_push($settlementMessages, $settlement->oweeUser->name . " owes " . $settlement->owedUser->name . " $" . $settlement->amount);
		}
		
		$this->from = $report->owner->email;
		$this->view = "emails.settlementsDeterminedNotification";
		foreach($report->users as $user) {
			$this->to = $user->email;
			$this->data = compact("report", "user", "settlementMessages");
			$this->deliver();
		}
	}
	
	public function sendReportDeletedNotification($report) {
		$this->from = $report->owner->email;
		$this->view = "emails.reportDeletedNotification";

		foreach($report->users as $user) {
			$this->to = $user->email;
			$this->data = compact("report", "user");
			$this->deliver();
		}
	}
	
	public function sendAccountUpdatedNotification($user) {
		$this->to = $user->email;
		$this->view = "emails.accountUpdatedNotification";
		$this->data = compact("user");
		
		$this->deliver();
	}
}