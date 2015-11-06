	<div class="form-group row">
		<div class="col-sm-1">
			{!! Form::label('date', 'Date: ') !!}
		</div>
		<div class="col-sm-2">
			{!! Form::input('date', 'date', $editForm?null:date('Y-m-d'), ['class' => 'form-control', 'required', $expenseEditable?'':'disabled']) !!}
		</div>
		<div class="col-sm-2">
			{!! Form::label('owner', 'Expense Owner: ') !!}
		</div>
		<div>
			{{ ($expense==null || $expenseEditable)?'You':$expense->owner->name }}
		</div>
	</div>
	<div class="row">
		<div class="form-group col-sm-1">
			{!! Form::label('title', 'Title:') !!}
		</div>
		<div class="form-group col-sm-5">
			{!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Lunch, Cab ride, Air tickets....', 'required', $expenseEditable?'':'disabled']) !!}
		</div>
	</div>
	<div class="table-responsive row">
		<div class="col-sm-6">
			<table class="table table-striped col-sm-6">
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
				<tr>
					<td colspan=3>
						<div class="form-group col-sm-6">{!! Form::submit($editForm?'Update Expense':'Add Expense', ['class' => 'btn btn-primary form-control', $expenseEditable?'':'disabled']) !!}</div>
						<div class="col-sm-6"><a href="/expenseReports/{{ $report->id }}" class="btn btn-primary col-sm-12" role="button">Cancel</a></div>
					</td>
				</tr>
			</table>
		</div>
		<div class="col-sm-6">@include('errors.list')</div>
	</div>
