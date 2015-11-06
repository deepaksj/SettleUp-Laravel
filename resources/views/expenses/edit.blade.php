@extends('app')

@section('content')

	<h3>Add a new expense to <span class="reportTitle">{{ $report->title }}</span></h3>
	<hr>
	
	{!! Form::model($report, ['url' => 'expenses/add/'. $report->id]) !!}
		<div class="form-group row">
			<div class="col-sm-1">
				{!! Form::label('date', 'Date: ') !!}
			</div>
			<div class="col-sm-2">
				{!! Form::input('date', 'date', date('Y-m-d'), ['class' => 'form-control', 'required']) !!}
			</div>
			<div class="col-sm-1">
				{!! Form::label('currency', 'Currency: ') !!}
			</div>
			<div class="col-sm-2">
				{!! Form::text('currency', 'USD', ['class' => 'form-control', 'disabled']) !!}
			</div>
		</div>
		<div class="row">
			<div class="form-group col-sm-1">
				{!! Form::label('title', 'Title:') !!}
			</div>
			<div class="form-group col-sm-5">
				{!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Lunch, Cab ride, Air tickets....', 'required']) !!}
			</div>
		</div>
		<div class="table-responsive row">
			<table class="table-striped col-sm-6">
				<tr class="row">
					<th class="col-sm-6">User</th>
					<th class="col-sm-3">Contribution ($)</th>
					<th class="col-sm-3">Participation Ratio</th>
				</tr>
				<tr class="form-group row">
					<td class="col-sm-6">{{ $report->owner->name }}</td>
					<td class="col-sm-3">{!! Form::text($report->owner->id, 0, ['class' => 'form-control']) !!}</td>
					<td class="col-sm-3">{!! Form::text($report->owner->id . 'p', 1, ['class' => 'form-control']) !!}</td>
				</tr>
				@foreach($report->users as $user)
					<tr class="form-group row">
						<td class="col-sm-6">{{ $user->name }}</td>
						<td class="col-sm-3">{!! Form::text($user->id, 0, ['class' => 'form-control']) !!}</td>
						<td class="col-sm-3">{!! Form::text($user->id . 'p', 1, ['class' => 'form-control']) !!}</td>
					</tr>
				@endforeach
			</table>
			@if($errors->any())
				<div class="col-sm-6">
					<ul class="alert alert-danger">
						@foreach($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
		</div>
		<div class="row">&nbsp</div>
		<div class="row">
				<div class="form-group col-sm-3">{!! Form::submit('Add Expense', ['class' => 'btn btn-primary form-control']) !!}</div>
				<div class="col-sm-3"><a href="/expenseReports/{{ $report->id }}" class="btn btn-primary col-sm-12" role="button">Cancel</a></div>
		</div>
	
	{!! Form::close() !!}
	
@stop
