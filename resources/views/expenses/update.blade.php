@extends('app')

@section('content')

	@include('utilities.quickLinks', ['links' => [[$isArchived?'/settledExpenseReports':'/expenseReports', $isArchived?'Archived Reports':'Active Reports'], ['/expenseReports/'. $report->id, $report->title]]])
	<h3><span class="reportTitle">{{ $expense->title }}</span></h3>
	<hr>
	<div class="col-sm-6">
		{!! Form::model($expense, ['url' => 'expenses/edit/'. $expense->id, 'method' => 'PATCH']) !!}
			@include('expenses._expenseForm', ['editForm' => 1])
		{!! Form::close() !!}
	</div>
	<div class="col-sm-6">@include('errors.list')</div>
	
@stop
@section('footer')
<script type="text/javascript">
	function togglePRatios() {
		currState = $('.pRatio').attr('readonly');
		if(currState == 'readonly') {
			$('.pRatio').prop('readonly', false);
		} else { 
			$('.pRatio').prop('readonly', true);
		}
	}
</script>
@stop