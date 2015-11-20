@extends('emails.emailTemplate')

@section('content')
	<div>Hey {{$user['name']}},</div>
	<br>
	<div>{{$report['owner']['name']}} has deleted the report: {{$report['title']}}.
	</div>
@stop