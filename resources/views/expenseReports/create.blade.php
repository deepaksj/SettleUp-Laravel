@extends('app')
	
@section('content')
	@include('utilities.quickLinks', ['links' => [['/expenseReports', 'Active Reports']]])
	<h3>Create a new Expense Report</h3>
	<hr>
	
	{!! Form::open(['url' => 'expenseReports']) !!}
		<div class="col-sm-7">@include('expenseReports._reportForm', ['submitButtonText' => 'Create Report', 'cancelButtonURL' => '', 'users' => null])</div>
	{!! Form::close() !!}
		<div class="col-sm-5" id="infoAndErrorsDiv">@include('errors.list')</div>
		
	 @include('friends._addFriendModal')
	
@stop
@section('footer')
	<script src="/js/select2.min.js"></script>
	<script src="/js/settleUp-ExpenseReport.js"></script>
@stop