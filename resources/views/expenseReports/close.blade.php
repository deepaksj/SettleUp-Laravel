@extends('app')

@section('content')
	<script src="/js/settleUp.js"></script>
	@include('utilities.quickLinks', ['links' => [['/expenseReports', 'Active Reports'], ['/expenseReports/'. $report->id, $report->title]]])
	<h3><span class="reportTitle">{{ $report->title }}</span></h3>
	<div style="font-style: italic;">Determine settlements by pairing-off owed users with those who owe</div>
	<hr>
	<div class="row">
		{!! Form::open(['url' => '/settlements/'. $report->id . '/add']) !!}
			<div id="pairingSection" class="col-sm-9">
				<div class="row" style="font-weight: bold;" >
					<div class="col-sm-4">Users who owe</div>
					<div class="col-sm-4">Users who are owed</div>
				</div>
				<div class="row"><hr></div>
				<div class="row">
					<div id="owees" class="col-sm-4">
						@foreach($oweesAndOwed['owees'] as $key => $owee)
							<div class="radio">
		      					<label><input type="radio" name="owee" id="owee{{$key}}" value="{{$owee[0]*(-1)}}" updatedValue="{{$owee[0]*(-1)}}" userName="{{$owee[1]->name}}">{{$owee[1]->name}}: <span class="currencyNegative">{{ number_format($owee[0]*(-1), 2, ".", ",") }}</span>  <span class="currencyNegative" hidden></</span></label>
								<div id="inputSectionForowee{{$key}}" hidden></div>
		      				</div>
						@endforeach
					</div>
					<div id="oweed" class="col-sm-4">
						@foreach($oweesAndOwed['owed'] as $key => $owed)
							<div class="checkbox">
		      					<label><input type="checkbox" name="{{$key}}" id="owed{{$key}}" value="{{$owed[0]}}" updatedValue="{{$owed[0]}}" userName="{{$owed[1]->name}}">{{$owed[1]->name}}: <span class="currency">{{ number_format($owed[0], 2, ".", ",") }}</span>  <span class="currency" hidden></span></label>
		    				</div>
						@endforeach
					</div>
					<div id="messageSection" class="col-sm-4">
						@foreach($oweesAndOwed['owees'] as $key => $owee)
							<div id="messagesForowee{{$key}}"></div>
						@endforeach
					</div>
				</div>
				<div class="row"><hr></div>
				<div class="row">
					<div class="col-sm-2" id="confirmBtnDiv"><button type="button" class="btn btn-primary col-sm-12" id="confirmBtn" disabled>Match Off</button></div>
					<!-- div class="col-sm-3" id="submitBtnDiv"><input class="btn btn-primary form-control" id="submitBtn" type="submit" value="Confirm Settlements"></div-->
					<div class="col-sm-3" id="submitBtnDiv"><a class="btn btn-primary col-sm-12" role="button" data-toggle="modal" data-target="#submitSettlements">Confirm Settlements</a></div>
					<div class="col-sm-2"><a href="/expenseReports/{{ $report->id }}/close" class="btn btn-primary col-sm-12" role="button">Reset</a></div>
				</div>
			</div>
			<div id="submitSettlements" class="modal" role="dialog">
				<div class="modal-dialog modal-sm">
			    	<div class="modal-content">
			      		<div class="modal-header">
			        		<button type="button" class="close" data-dismiss="modal">&times;</button>
			        		<h4 class="modal-title">Confirm Settlements</h4>
			      		</div>
			      		<div class="modal-body">
			      			<div style="font-weight: bold; font-style: italic;">Settlements cannot be modified once confirmed</div><br>
							<div id="modalMessageSection">
								@foreach($oweesAndOwed['owees'] as $key => $owee)
									<div id="modalMessagesForowee{{$key}}"></div>
								@endforeach
							</div>
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