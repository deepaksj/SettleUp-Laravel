@extends('app')

@section('content')

	@include('utilities.quickLinks', ['links' => [['/expenseReports', 'Active Reports'], ['/expenseReports/'. $report->id, $report->title]]])
	<h3>Add a new expense to <span class="reportTitle">{{ $report->title }}</span></h3>
	<hr>
	<div class="col-sm-6">
		{!! Form::open(['url' => 'expenses/add/'. $report->id]) !!}
			@include('expenses._expenseForm', ['editForm' => 0, 'expenseEditable' => 1, 'expense' =>null])
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