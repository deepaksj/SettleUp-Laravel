@extends('app')
@section('content')
	@include('utilities.quickLinks', ['links' => [['/expenseReports', 'Active Reports']]])
	<h3>{{ $report->title }}</h3><span style="color: red;">{{$report->status?" (Report has been closed)":""}}</span>
	<hr>
	<div class="row">
		<div class="col-sm-2 reportHeader" >Start Date: </div><div class="col-sm-2"> {{ date('d M Y', strtotime($report->startDate)) }}</div>
		<div class="col-sm-2 reportHeader" >Close Date: </div><div class="col-sm-2">
			@if($report->closeDate == null)
				{{ '-' }}
			@else {{ date('d M Y', strtotime($report->closeDate)) }}
			@endif
		</div>
		<div class="col-sm-2"></div>
		<div class="col-sm-2">
			@if(!$report->status && $isReportOwner)
				<a href="/expenseReports/update/{{ $report->id }}" class="btn btn-primary col-sm-12" role="button">Edit Report</a>
			@endif
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2 reportHeader" >Report Owner: </div>
		<div class="col-sm-2" >{{$isReportOwner?'You':$report->owner->name}}</div>
		<div class="col-sm-2 reportHeader" >Report Users: </div>
		<div class="col-sm-6">
			@foreach($report->users as $user)
				{{ ($user->id==\Auth::user()->id?'You':$user->name) . ","}}
			@endforeach
		</div>
	</div>
	<hr>
	@include('errors.list')
	@include('utilities.flashmessage')
	<div id="reportDetails">
	    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	        <li class="active"><a href="#summary" data-toggle="tab">Summary</a></li>
	        <li><a href="#details" data-toggle="tab">Details</a></li>
	    </ul>
	    {!! Form::open(['url' => '/expenses/delete', 'type' => 'post']) !!}
			{!! Form::input('hidden', 'report_id', $report->id) !!}
		    <div id="my-tab-content" class="tab-content">
		        <div class="tab-pane active" id="summary">
					<table class="table table-striped expensesTable">
						<tr class="info expensesTable">
							<th class="col-sm-1"></th>
							<th class="col-sm-1 expensesTable"><a href="/expenseReports/{{$report->id}}?sortBy=date&sortOrder={{$sortOrder}}">Date</a></th>
							<th class="col-sm-2 expensesTable"><a href="/expenseReports/{{$report->id}}?sortBy=title&sortOrder={{$sortOrder}}">Title</a></th>
							<th class="col-sm-2 expensesTable">Expense Total</th>
							<th class="col-sm-2 expensesTable">You Spent (A)</th>
							<th class="col-sm-2 expensesTable">You Used (B)</th>
							<th class="col-sm-2 expensesTable">Owe/Owed (A-B)</th>
						</tr>
						@forelse($expenses as $expense)
							@if($expense->participationRatio($userId = \Auth::user()->id) > 0 || $expense->participantContribution($userId) > 0)
								<tr>
									<td>{!! Form::checkbox('expense_ids[]', $expense->id, false, [(!$report->status && $expense->owner_id==$userId?'':'disabled')]) !!}</td>
									<td>{{ date('m/d/Y', strtotime($expense->date)) }} </td>
									<td><a href="/expenses/edit/{{ $expense->id }}">{{ $expense->title }}</a></td>
									<td>@include('utilities.currency', ['amountToBeFormatted' => $expense->amount])</td>
									<td>@include('utilities.currency', ['amountToBeFormatted' => $expense->participantContribution($userId)])</td>
									<td>@include('utilities.currency', ['amountToBeFormatted' => $expense->participantUsage($userId)])</td>
									<td colspan="2" class="expensesTableBorder">@include('utilities.currency', ['amountToBeFormatted' => $expense->participantOwes($userId)])</td>
								</tr>
							@endif
							@empty
								<tr><td colspan=7>You have not entered any expenses!</td></tr>
						@endforelse
						<tr class="reportTotals">
							<td colspan="3">Report Totals</td>
							<td>@include('utilities.currency', ['amountToBeFormatted' => $report->reportTotal()])</td>
							<td>@include('utilities.currency', ['amountToBeFormatted' => $report->participantContributionTotal($userId = \Auth::user()->id)])</td>
							<td>@include('utilities.currency', ['amountToBeFormatted' => $report->participantConsumptionTotal($userId)])</td>
							<td class="expensesTableBorder">@include('utilities.currency', ['amountToBeFormatted' => $report->participantTotal($userId)])</td>
						</tr>
					</table>
		        </div>
		        <div class="tab-pane" id="details">
				    <table class="table table-striped expensesTable">
						<tr  class="info expensesTable">
							<th class="col-sm-1" rowspan="2"></th>
							<th class="col-sm-1 expensesTable" rowspan="2">Date</th>
							<th class="col-sm-2 expensesTable" rowspan="2">Title</th>
							<th class="col-sm-1 expensesTable" rowspan="2">Total</th>
							<th class="col-sm-1 expensesTableHeader" colspan="2">{{ $report->owner->name }}</th>
							@foreach($report->users as $user)
								<th class="col-sm-1 expensesTableHeader" colspan="2">{{ $user->name }}</th>
							@endforeach
						</tr>
						<tr  class="info expensesTableHeader2Row">
							<td class="col-sm-1">Spent</td>
							<td class="col-sm-1">Used</td>
							@foreach($report->users as $user)
								<td class="col-sm-1 expensesTableBorder">Spent</td>
								<td class="col-sm-1">Used</td>
							@endforeach
						</tr>
						
						@foreach($expenses as $expense)
							<tr>
								<td>{!! Form::checkbox('expense_ids[]', $expense->id, false, [(!$report->status && $expense->owner_id==$userId?'':'disabled')]) !!}</td>
								<td>{{ date('m/d/Y', strtotime($expense->date)) }} </td>
								<td><a href="/expenses/edit/{{ $expense->id }}">{{ $expense->title }}</a></td>
								<td>@include('utilities.currency', ['amountToBeFormatted' => $expense->amount])</td>
								<td class="expensesTableBorder">@include('utilities.currency', ['amountToBeFormatted' => $expense->participantContribution($expense->owner_id)])</td>
								<td>@include('utilities.currency', ['amountToBeFormatted' => $expense->participantUsage($expense->owner_id)])</td>
								@foreach($report->users as $user)
									<td class="expensesTableBorder">@include('utilities.currency', ['amountToBeFormatted' => $expense->participantContribution($user->id)])</td>
									<td>@include('utilities.currency', ['amountToBeFormatted' => $expense->participantUsage($user->id)])</td>
								@endforeach
							</tr>
						@endforeach
						<tr class="reportSubTotals">
							<td></td>
							<td colspan="2">Totals</td>
							<td>@include('utilities.currency', ['amountToBeFormatted' => $report->reportTotal()])</td>
							<td class="expensesTableBorder">@include('utilities.currency', ['amountToBeFormatted' => $report->participantContributionTotal($report->owner_id)])</td>
							<td>@include('utilities.currency', ['amountToBeFormatted' => $report->participantConsumptionTotal($report->owner_id)])</td>
							@foreach($report->users as $user)
								<td class="expensesTableBorder">@include('utilities.currency', ['amountToBeFormatted' => $report->participantContributionTotal($user->id)])</td>
								<td>@include('utilities.currency', ['amountToBeFormatted' => $report->participantConsumptionTotal($user->id)])</td>
							@endforeach
						</tr>
						<tr class="reportTotals">
							<td></td>
							<td colspan="2">Owes/Owed</td>
							<td>-</td>
							<td colspan="2" class="expensesTableBorder">@include('utilities.currency', ['amountToBeFormatted' => $report->ownerTotal()])</td>
							@foreach($report->users as $user)
								<td colspan="2" class="expensesTableBorder">@include('utilities.currency', ['amountToBeFormatted' => $report->participantTotal($user->id)])</td>
							@endforeach
						</tr>
					</table>
		        </div>
	    	</div>
	    	<div class="row">
	    		@if(!$report->status)
		    		<div class="form-group col-sm-2">{!! Form::submit('Delete Expenses', ['class' => 'btn btn-primary form-control']) !!}</div>
					<div class="col-sm-2"><a href="/expenses/add/{{ $report->id }}" class="btn btn-primary col-sm-12" role="button">Add Expense</a></div>
					@if($isReportOwner)
						<div class="col-sm-2"><a class="btn btn-primary col-sm-12" role="button" data-toggle="modal" data-target="#closeReport">Close Report</a></div>
					@endif
				@else
					<div class="col-sm-2"><a href="/settlements/{{ $report->id }}" class="btn btn-primary col-sm-12" role="button">Settlements</a></div>					
				@endif
				<?php echo $expenses->appends(['sortBy' => $sortBy, 'sortOrder' => ($sortOrder=='asc'?'desc':'asc')])->render(); ?>
			</div>
		{!! Form::close() !!}
	</div>
	@if($isReportOwner)
		<div id="closeReport" class="modal" role="dialog">
			<div class="modal-dialog modal-sm">
		    	<div class="modal-content">
		      		<div class="modal-header">
		        		<button type="button" class="close" data-dismiss="modal">&times;</button>
		        		<h4 class="modal-title">Confirm Report Closure</h4>
		      		</div>
		      		<div class="modal-body">
		      			Expenses cannot be added or modified once a report is closed. Are you sure you want to close it?
		      		</div>
		      		<div class="modal-footer">
		      			<a href="/expenseReports/{{ $report->id }}/close" class="btn btn-default" role="button" >Yes</a>
	        			<button type="button" class="btn btn-default" data-dismiss="modal" id="modalCloseBtn">No</button>
	      			</div>
				</div>
			</div>
		</div>
	@endif
@stop
@section('footer')
@stop