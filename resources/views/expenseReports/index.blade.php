@extends('app')

@section('content')

	<h3>Your Expense Reports</h3>
	<hr>
	<div id="reportsList">
		<div class="row">
			@include('utilities.flashmessage')
			@include('errors.list')
		</div>
		<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	        <li class="{{(($reportStatus==0)?'active':'')}}"><a href="/expenseReports?status=0" >Open Reports</a></li>
	        <li class="{{(($reportStatus==1)?'active':'')}}"><a href="/expenseReports?status=1">Closed Reports</a></li>
	    </ul>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="openReports">
				{!! Form::open(['url' => 'expenseReports/delete?sortBy=' . $sortBy . '&sortOrder=' . $sortOrder]) !!}
					<table class="table table-striped">
						<tr class="info expensesTable">
							<th class="col-sm-0.5"></th>
							<th class="col-sm-3"><a href="/expenseReports?status={{$reportStatus}}&sortBy=title&sortOrder={{$sortOrder}}">Title</a></th>
							<th class="col-sm-1.5"><a href="/expenseReports?status={{$reportStatus}}&sortBy={{(($reportStatus==0)?'startDate':'endDate')}}&sortOrder={{$sortOrder}}">{{(($reportStatus==0)?'Start':'Closed')}} Date</a></th>
							<th class="col-sm-1.5">Report Total</th>
							<th class="col-sm-1.5">Your Total</th>
							<th class="col-sm-1">Owner</th>
							<th class="col-sm-3">Participants</th>
						</tr>
						@forelse($reports as $report)
							@include('expenseReports._reportIndexTableRow', ['report' => $report])
							@empty
								<tr><td colspan=7>You have no {{$reportStatus?'closed':'open'}} reports!</td></tr>
						@endforelse
						<tr>
							<td colspan=7>
								@if($reportStatus==0)
									<div class="col-sm-3">
										{!! Form::submit('Delete Expense Reports', ['class' => 'btn btn-primary col-sm-12']) !!}
									</div>
									<div class="col-sm-3"><a href="/expenseReports/create" class="btn btn-primary col-sm-12" role="button" >Create New Report</a></div>
								@else
									<div class="col-sm-6"></div>
								@endif
								<div class="col-sm-6"><?php echo $reports->appends(['status' => $reportStatus, 'sortBy' => $sortBy, 'sortOrder' => ($sortOrder=='asc'?'desc':'asc')])->render(); ?></div>
							</td>
						</tr>
					</table>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@stop
