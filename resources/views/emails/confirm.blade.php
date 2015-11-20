@extends('emails.emailTemplate')

@section('content')
	<div>Thanks for signing up! Please confirm your email by clicking <a href="{{url('/register/confirm/' . $user['token'])}}">here!</a></div>
@stop