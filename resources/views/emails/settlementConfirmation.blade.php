@extends('emails.emailTemplate')

@section('content')
	<div>Hey {{$counterparty['name']}},</div>
	<br>
	<div>{{$fromUser['name']}} has confirmed that you have {{$counterpartyOwes?"":"been "}}paid a sum of <b>${{$settlement['amount']}}</b> 
		towards settlement of the Expense Report <a href="{{url('/expenseReports/' . $settlement['report']['id'])}}">{{$settlement['report']['title']}}</a>
	</div>
@stop