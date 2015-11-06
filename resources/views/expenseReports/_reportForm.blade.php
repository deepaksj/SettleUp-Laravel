<div class="form-group row">
	<div class="col-sm-3">{!! Form::label('title', 'Title:') !!}</div>
	<div class="col-sm-9">{!! Form::text('title', null, ['class' => 'form-control']) !!}</div>
</div>
<div class="form-group row">
	<div class="col-sm-3">{!! Form::label('description', 'Description:') !!}</div>
	<div class="col-sm-9">{!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) !!}</div>
</div>
<div class="form-group row">
	<div class="col-sm-3">{!! Form::label('friends', 'Friends:') !!}</div>
	<div class="col-sm-5">
		<select name="friends[]" multiple class="form-control" size="5" id="friendsList">
			@foreach($friends as $friend)
				@if($users != null && $users->contains($friend['id']))
					<option value="{{ $friend['id'] }}" selected title="{{ $friend['email'] }}">{{ $friend['name'] }}</option>
				@else
					<option value="{{ $friend['id'] }}" title="{{ $friend['email'] }}">{{ $friend['name'] }}</option>
				@endif
			@endforeach
		</select>
	</div>
	<div class="col-sm-4">
		<a class="btn btn-primary" role="button" data-toggle="modal" data-target="#addFriend">+</a> Add New Friend
	</div>
</div>
<div class="form-group row">
	<div class="col-sm-3">{!! Form::label('startDate', 'Start Date:') !!}</div>
	<div class="col-sm-5">{!! Form::input('date', 'startDate', $users==null?date('Y-m-d'):null, ['class' => 'form-control']) !!}</div>
</div>
<div class="form-group row">
	<div class="col-sm-4 col-sm-offset-3">{!! Form::submit($submitButtonText, ['class' => 'btn btn-primary form-control']) !!}</div>
	<div class="col-sm-4"><a href="/expenseReports/{{ $cancelButtonURL }}" class="btn btn-primary col-sm-12" role="button">Cancel</a></div>
</div>
