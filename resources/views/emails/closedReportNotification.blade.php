@extends('emails.emailTemplate')

@section('content')
	<div>Hey {{$user['name']}},</div>
	<br>
	<div>{{$report['owner']['name']}} has closed Report: <a href="{{url('/expenseReports/' . $report['id'])}}">{{$report['title']}}</a>.
	 You can no longer add or modify your expenses to this report.
	</div>
@stop