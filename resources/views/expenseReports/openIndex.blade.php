@extends('app')

@section('content')

	<h3>Your Active Reports</h3>
	<hr>
	<div id="reportsList">
		<div class="row">
			@include('utilities.flashmessage')
			@include('errors.list')
		</div>
		<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	        <li class="{{(($reportStatus==0)?'active':'')}}"><a href="/expenseReports?reportStatus=0" >Open Reports</a></li>
	        <li class="{{(($reportStatus==1)?'active':'')}}"><a href="/expenseReports?reportStatus=1">Closed Reports</a></li>
	    </ul>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="openReports">
				{!! Form::open(['url' => 'expenseReports/delete?sortBy=' . $sortBy . '&sortOrder=' . $sortOrder]) !!}
					<table class="table table-striped">
						<tr class="info expensesTable">
							<th class="col-sm-0.5"></th>
							<th class="col-sm-3"><a href="/expenseReports?sortBy=title&sortOrder={{$sortOrder}}">Title</a></th>
							<th class="col-sm-1.5"><a href="/expenseReports?sortBy={{(($reportStatus==0)?'startDate':'endDate')}}&sortOrder={{$sortOrder}}">{{(($reportStatus==0)?'Start':'Closed')}} Date</a></th>
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
										<a class="btn btn-primary col-sm-12" role="button" data-toggle="modal" data-target="#deleteReports">Delete Expense Reports</a>
									</div>
									<div class="col-sm-3"><a href="/expenseReports/create" class="btn btn-primary col-sm-12" role="button" >Create New Report</a></div>
								@else
									<div class="col-sm-6"></div>
								@endif
								<div class="col-sm-6"><?php echo $reports->appends(['sortBy' => $sortBy, 'sortOrder' => ($sortOrder=='asc'?'desc':'asc')])->render(); ?></div>
							</td>
						</tr>
					</table>
					<div id="deleteReports" class="modal" role="dialog">
						<div class="modal-dialog modal-sm">
					    	<div class="modal-content">
					      		<div class="modal-header">
					        		<button type="button" class="close" data-dismiss="modal">&times;</button>
					        		<h4 class="modal-title">Confirm Deletion</h4>
					      		</div>
					      		<div class="modal-body">
					      			All expenses associated with the report will be deleted. Are you sure?
					      		</div>
					      		<div class="modal-footer">
					      			<input class="btn btn-default" id="submitBtn" type="submit" value="Yes">
				        			<button type="button" class="btn btn-default" data-dismiss="modal" id="modalCloseBtn">No</button>
				      			</div>
							</div>
						</div>
					</div>
				{!! Form::close() !!}
			</div>
		</div>
	</div>
@stop
