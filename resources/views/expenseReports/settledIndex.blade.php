@extends('app')

@section('content')

	<h3>Your Archived Reports</h3>
	<hr>
	<div id="reportsList">
		<table class="table table-striped">
			<tr class="info expensesTable">
				<th class="col-sm-0.5"></th>
				<th class="col-sm-3"><a href="/settledExpenseReports?sortBy=title&sortOrder={{$sortOrder}}">Title</a></th>
				<th class="col-sm-1.5"><a href="/settledExpenseReports?sortBy=closeDate&sortOrder={{$sortOrder}}">Closed Date</a></th>
				<th class="col-sm-1.5">Report Total</th>
				<th class="col-sm-1.5">Your Total</th>
				<th class="col-sm-1">Owner</th>
				<th class="col-sm-3">Participants</th>
			</tr>
			@forelse($reports as $report)
				@include('expenseReports._reportIndexTableRow', ['report' => $report])
				@empty
					<tr><td colspan=7>You have no Archived Reports!</td></tr>
			@endforelse
			<tr>
				<td colspan=7>
					<div class="col-sm-6"></div>
					<div class="col-sm-6"><?php echo $reports->appends(['sortBy' => $sortBy, 'sortOrder' => ($sortOrder=='asc'?'desc':'asc')])->render(); ?></div>
				</td>
			</tr>
		</table>
	</div>
@stop
