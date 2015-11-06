<tr>
	@if($report->status==0 && $report->owner_id == \Auth::user()->id)
		<td>{!! Form::checkbox('report_ids[]', $report->id) !!}</td>
	@else
		<td></td>
	@endif
	<td class="setWidth concat"><a href="{{ url('expenseReports', $report->id) }}">{{ $report->title }}</a></td>
	<td>{{ date('m/d/Y', strtotime($report->status?$report->endDate:$report->startDate)) }} </td>
	<td>@include('utilities.currency', ['amountToBeFormatted' => $report->reportTotal()])</td>
	<td>@include('utilities.currency', ['amountToBeFormatted' => $report->owner_id == \Auth::user()->id?$report->ownerTotal():$report->participantTotal(\Auth::user()->id)])</td>
	@if($report->owner_id == \Auth::user()->id)
		<td>You</td>
	@else
		<td>{{ \App\User::find($report->owner_id)->name }}</td>
	@endif
	<td class="setWidth concat">
		<div>
			@foreach($report->users()->orderBy('name')->get() as $user)
				@if($user->id == \Auth::user()->id)
					{{"You, "}}
				@else
					{{ $user->name . ", "}}
				@endif
			@endforeach
		</div>
	</td>
</tr>
