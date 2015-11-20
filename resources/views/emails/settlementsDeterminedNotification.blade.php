@extends('emails.emailTemplate')

@section('content')
	<div>Hey {{$user['name']}},</div>
	<br>
	<div>{{$report['owner']['name']}} has determined the following settlements for report: 
			<a href="{{url('/expenseReports/' . $report['id'])}}">{{$report['title']}}</a>.
	</div>
	<div>
		@foreach($settlementMessages as $message)
			<li>{{$message}}</li>
		@endforeach
	</div>
@stop