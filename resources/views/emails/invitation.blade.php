@extends('emails.emailTemplate')

@section('content')
	<div>Hey {{$toUser['name']}},</div>
	<br>
	<div>Your friend {{$fromUser['name']}} has invited you to sign up for App-portion!, a cool tool to help you split expenses 
	with your friends and keep track of them till they are settled. Come check us out, you can confirm your registration by 
	clicking on this <a href="{{url('/register/confirm/' . $toUser['token'])}}">link!</a>. Its free to use and really easy to 
	sign up and get started. Please read the <a href="{{url('/terms.html')}}">Terms & Conditions</a> governing the use of the site.
	</div>
@stop