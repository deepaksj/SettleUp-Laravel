@extends('app')
@section('content')
	@include('utilities.quickLinks', ['links' => [['/expenseReports', 'Active Reports'], ['/expenseReports/'. $report->id, $report->title]]])
	<h3>Settlement Details for Report: <span class="reportTitle">{{ $report->title }}</span></h3>
	<hr>
	@include('utilities.flashmessage')
	<div id="settlementDetails" class="col-sm-6">
	    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	        <li class="active"><a href="#userSettlements" data-toggle="tab">Your Settlements</a></li>
	        <li><a href="#otherSettlements" data-toggle="tab">Other Settlements</a></li>
	    </ul>
	
		{!! Form::open(['url' => 'settlements/' . $report->id . '/complete']) !!}
			<div id="my-tab-content" class="tab-content">
				<div class="tab-pane active" id="userSettlements">
					<table class="table table-striped expensesTable">
						<tr  class="info expensesTable">
							<th class="col-sm-2"></th>
							<th class="col-sm-3"></th>
							<th class="col-sm-1"></th>
							<th class="col-sm-3"></th>
							<th class="col-sm-3"></th>
						</tr>
						<?php $authUserTotal = 0 ?> <!-- Hack...Come up with a better solution -->
						@forelse($authUserSettlements as $settlement)
							<tr>
								@if($settlement->completed)
									<td><input checked="checked" name="settled_ids[]" type="checkbox" value="{{$settlement->id}}" title="Settlement Completed" disabled></td>
								@else
									<td>{!! Form::checkbox('settlement_ids[]', $settlement->id) !!}</td>
								@endif
								@if($settlement->owee_id == $authenticatedUser->id)
									<td class="reportTitle">You</td>
									<td>owe</td>
									<?php $msg = "You paid " . $settlement->oweeName . " $" . $settlement->amount ?>
								@else
									<td class="reportTitle">{{$settlement->oweeName}}</td>
									<td>owes</td>
									<?php $msg = $settlement->oweeName . " paid you $" . $settlement->amount ?>
								@endif
								@if($settlement->owed_id == $authenticatedUser->id)
									<td class="reportTitle">You</td>
								@else
									<td class="reportTitle">{{$settlement->owedName}}</td>
								@endif
								<td>@include('utilities.currency', ['amountToBeFormatted' => $settlement->amount])</td>
							</tr>
							<?php $authUserTotal += $settlement->amount ?>
							<span id="{{$settlement->id}}Message" hidden>{{$msg}}</span>
							@empty
								<tr><td colspan=5>You neither owe nor are owed any money for this report!</td></tr>
						@endforelse
						<tr class="reportTotals">
							<td><button class="btn btn-info" id="confirmationBtn" type="button" data-toggle="modal" data-target="#confirmSettlements" disabled>Settle Amounts</button></td>
							<td colspan=3>You {{((count($authUserSettlements)>0 && $authUserSettlements[0]->owee_id==$authenticatedUser->id)?"owe ":"are owed ")}} a total of </td>
							<td>@include('utilities.currency', ['amountToBeFormatted' => $authUserTotal])</td>
						</tr>
					</table>
				</div>
				<div class="tab-pane" id="otherSettlements">
					<table class="table table-striped expensesTable">
						<tr  class="info expensesTable">
							<th colspan=4>Other Settlements for this report</th>
						</tr>
						@forelse($otherUserSettlements as $settlement)
							<tr>
								<td class="reportTitle">{{$settlement->oweeName}}</td>
								<td>owes</td>
								<td class="reportTitle">{{$settlement->owedName}}</td>
								<td>@include('utilities.currency', ['amountToBeFormatted' => $settlement->amount])</td>
							</tr>
							@empty
								<tr><td colspan=4>No Settlements here!</td></tr>
						@endforelse
					</table>
				</div>
			</div>
			<div id="confirmSettlements" class="modal" role="dialog">
				<div class="modal-dialog modal-sm">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal">&times;</button>
			        		<h4 class="modal-title">Confirm Settlements</h4>
			      		</div>
			      		<div class="modal-body">
			      			Confirm the following payments:
			      			<div class="row">&nbsp;</div>
			      			<div id="confirmationMessage"></div>
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
@stop
@section('footer')
	<script type="text/javascript">
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();

		    $(":checkbox").click(function() {
			    checkedAndEnabled = $(":checked:enabled"); 
			    if(checkedAndEnabled.length>0) {
				    $("#confirmationBtn").prop("disabled", false);
				    $("#confirmationMessage").html("");
				    for(i=0; i<checkedAndEnabled.length; i++) {
					    msg = $("#" + checkedAndEnabled[i].value + "Message").text();
					    $("#confirmationMessage").append(msg + "<br>");
				    }
			    } else {
			    	$("#confirmationBtn").prop("disabled", true);
			    }
			});
		});
	</script>
@stop