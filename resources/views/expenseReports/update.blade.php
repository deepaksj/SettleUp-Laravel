@extends('app')

@section('content')
	@include('utilities.quickLinks', ['links' => [['/expenseReports', 'Active Reports'], ['/expenseReports/'. $report->id, $report->title]]])
	<h3>Update Report: <span class="reportTitle">{{ $report->title }}</span></h3>
	<hr>
	
	{!! Form::model($report, ['url' => 'expenseReports/update/' . $report->id, 'method' => 'PATCH']) !!}
		<div class="col-sm-6">@include('expenseReports._reportForm', ['submitButtonText' => 'Update Report', 'cancelButtonURL' => $report->id, 'users' => $report->users])</div>
	{!! Form::close() !!}
		<div class="col-sm-6">@include('errors.list')</div>
		
	@include('friends._addFriendModal')

@stop
@section('footer')
<script src="/js/select2.min.js"></script>
<script src="/js/settleUp-ExpenseReport.js"></script>
@stop