@extends('emails.emailTemplate')

@section('content')
	<div>Hey {{$user['name']}},</div>
	<br>
	<div>Your account has been updated. If you did not initiate this update, please login to your account and change your password.
	</div>
@stop