@extends('app')

@section('content')

	@include('utilities.quickLinks', ['links' => [['/expenseReports', 'Active Reports'], ['/expenseReports/'. $report->id, $report->title]]])
	<h3>Add a new expense to <span class="reportTitle">{{ $report->title }}</span></h3>
	<hr>
	{!! Form::open(['url' => 'expenses/add/'. $report->id]) !!}
		@include('expenses._expenseForm', ['editForm' => 0, 'expenseEditable' => 1, 'expense' =>null])
	{!! Form::close() !!}

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