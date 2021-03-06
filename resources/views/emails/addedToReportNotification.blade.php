@extends('emails.emailTemplate')

@section('content')
	<div>Hey {{$user['name']}},</div>
	<br>
	<div>{{$report['owner']['name']}} has added you to the Expense Report <b>{{$report['title']}}</b>. Click 
		<a href="{{url('/expenseReports/'. $report['id'])}}">here</a> to add and track expenses.
	</div>
@stop