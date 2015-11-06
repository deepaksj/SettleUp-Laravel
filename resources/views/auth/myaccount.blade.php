@extends('app')
	
@section('content')
	<h3>Update My Account</h3>
	<hr>
	@include('utilities.flashmessage')
	{!! Form::model($user, ['url' => 'myAccount', 'method' => 'PATCH']) !!}
		<div class="col-sm-7">
			<div class="form-group row">
				<div class="col-sm-3">{!! Form::label('name', 'Name:') !!}</div>
				<div class="col-sm-4">{!! Form::text('name', null) !!}</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-3">{!! Form::label('password', 'Password:') !!}</div>
				<div class="col-sm-4">{!! Form::email('email', null, ['disabled']) !!}</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-3">{!! Form::label('password', 'Password:') !!}</div>
				<div class="col-sm-4">{!! Form::password('password', null) !!}</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-3">{!! Form::label('password', 'Confirm Password:') !!}</div>
				<div class="col-sm-4">{!! Form::password('password_confirmation', null) !!}</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-4 col-sm-offset-3">{!! Form::submit("Update Account", ['class' => 'btn btn-primary form-control']) !!}</div>
			</div>
			
		</div>
	{!! Form::close() !!}
	<div class="col-sm-5" id="infoAndErrorsDiv">@include('errors.list')</div>
@stop
