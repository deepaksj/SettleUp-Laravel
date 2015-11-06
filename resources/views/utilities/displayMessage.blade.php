@extends('app')

@section('content')

	@include('utilities.quickLinks', ['links' => [['/expenseReports', 'Your Reports'], ['/expenseReports/'. $report->id, $report->title]]])
	<h3>{{$messageHeader}}</h3>
	<hr>
	<div>{{$messageBody}}</div>
@stop
@stop