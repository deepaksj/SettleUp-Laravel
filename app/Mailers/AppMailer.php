<?php

namespace App\Mailers;

use Illuminate\Contracts\Mail\Mailer;
use App\User;
use App\Settlement;

class AppMailer {
	
	protected $mailer;
	protected $from;
	protected $to;
	protected $subject;
	protected $view;
	protected $data = [];
	
	public function __construct(Mailer $mailer) {
		$this->mailer = $mailer;
	}
	
	public function deliver() {
		$to = $this->to;
		$subject = $this->subject;
		
		$this->mailer->queue($this->view, $this->data, function($message) use ($to, $subject) {
			$message->from(env('MAIL_GLOBAL_FROM_EMAIL'), env('MAIL_GLOBAL_FROM_NAME'))
					->to($to)
					->subject($subject);
		});
	}
	
	public function sendEmailConfirmationTo(User $user) {
		$this->to = $user->email;
		$this->view = "emails.confirm";
		$this->data = compact("user");
		$this->subject = "Please confirm your email address";
		$this->deliver();
	}
	
	public function sendEmailInvitation($fromUser, $toUser) {
		//$this->from = $fromUser->email;
		$this->to = $toUser->email;
		$this->view = "emails.invitation";
		$this->data = compact("toUser", "fromUser");
		$this->subject = $fromUser->name . " has invited you to join App-portion";
		$this->deliver();
	}
	
	public function sendSettlementConfirmation($fromUser, $settlement) {
		$counterparty = $settlement->oweeUser;
		$counterpartyOwes = true; //A flag to check who owes whom for the view;
		if($settlement->owee_id==$fromUser->id) {
			$counterparty = $settlement->owedUser;
			$counterpartyOwes = false;
		}
		
		//$this->from = $fromUser->email;
		$this->to = $counterparty->email;
		$this->view = "emails.settlementConfirmation";
		$this->data = compact("fromUser", "settlement", "counterparty", "counterpartyOwes");
		$this->subject = "Confirmation of a settlement for the report: " . $settlement->report->title;
		$this->deliver();
	}
	
	public function sendAddedToReportNotification($report) {

		//$this->from = $report->owner->email;
		$this->view = "emails.addedToReportNotification";
		$this->subject = $report->owner->name . " has added you to the report: " . $report->title;
		foreach($report->users as $user) {
			$this->to = $user->email;
			$this->data = compact("report", "user");
			$this->deliver();
		}
	}
	
	public function sendReportClosedNotification($report) {
		
		//$this->from = $report->owner->email;
		$this->view = "emails.closedReportNotification";
		$this->subject = $report->title . " has been closed";
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
		
		//$this->from = $report->owner->email;
		$this->view = "emails.settlementsDeterminedNotification";
		$this->subject = "Settlements for report: " . $report->title;
		foreach($report->users as $user) {
			$this->to = $user->email;
			$this->data = compact("report", "user", "settlementMessages");
			$this->deliver();
		}
	}
	
	public function sendReportDeletedNotification($report) {
		//$this->from = $report->owner->email;
		$this->view = "emails.reportDeletedNotification";
		$this->subject = "Report: " . $report->title . " has been deleted";

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
		$this->subject = "Account updated";
		$this->deliver();
	}
}