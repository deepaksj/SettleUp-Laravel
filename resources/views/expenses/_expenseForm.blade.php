<div class="form-group row">
	<div class="col-sm-2">
		{!! Form::label('date', 'Date: ') !!}
	</div>
	<div class="col-sm-3">
		{!! Form::input('date', 'date', $editForm?null:date('Y-m-d'), ['class' => 'form-control', 'required', $expenseEditable?'':'disabled']) !!}
	</div>
	<div class="col-sm-3">
		{!! Form::label('owner', 'Expense Owner: ') !!}
	</div>
	<div class="col-sm-4">
		{{ ($expense==null || $expenseEditable)?'You':$expense->owner->name }}
	</div>
</div>
<div class="form-group row">
	<div class="col-sm-2">
		{!! Form::label('title', 'Title:') !!}
	</div>
	<div class="col-sm-10">
		{!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Lunch, Cab ride, Air tickets....', 'required', $expenseEditable?'':'disabled']) !!}
	</div>
</div>
<div class="form-group row">
	<div class="table-responsive">
		<table class="table table-striped">
			<tr class="info">
				<th class="col-sm-5">User</th>
				<th class="col-sm-3">Contribution ($)</th>
				<th class="col-sm-4"><a href="#" onclick="togglePRatios()" title="Modify Participation Ratios">Participation Ratio <span class="glyphicon glyphicon-pencil"></span></a></th>
			</tr>
			<tr>
				<td>{{ \Auth::user()->id==$report->owner_id?'You':$report->owner->name }}</td>
				<td>{!! Form::text($report->owner->id, $editForm?$expense->participantContribution($report->owner->id):0, ['class' => 'form-control', $expenseEditable?'':'disabled']) !!}</td>
				<td><span class="col-sm-2"></span><span class="col-sm-8">{!! Form::text($report->owner->id . 'p', $editForm?$expense->participationRatio($report->owner->id):1, ['class' => 'form-control pRatio col-sm-8', 'readonly', $expenseEditable?'':'disabled']) !!}</span><span class="col-sm-2"></span></td>
			</tr>
			@foreach($report->users as $user)
				<tr>
					<td>{{ \Auth::user()->id==$user->id?'You':$user->name }}</td>
					<td>{!! Form::text($user->id, $editForm?$expense->participantContribution($user->id):0, ['class' => 'form-control', $expenseEditable?'':'disabled']) !!}</td>
					<td><span class="col-sm-2"></span><span class="col-sm-8">{!! Form::text($user->id . 'p', $editForm?$expense->participationRatio($user->id):1, ['class' => 'form-control pRatio', 'readonly', $expenseEditable?'':'disabled']) !!}</span><span class="col-sm-2"></span></td>
				</tr>
			@endforeach
		</table>
		<div class="row form-group">
			<div class="form-group col-sm-6">{!! Form::submit($editForm?'Update Expense':'Add Expense', ['class' => 'btn btn-primary form-control', $expenseEditable?'':'disabled']) !!}</div>
			<div class="form-group col-sm-6"><a href="/expenseReports/{{ $report->id }}" class="btn btn-primary form-control" role="button">Cancel</a></div>
		</div>
	</div>
</div>
