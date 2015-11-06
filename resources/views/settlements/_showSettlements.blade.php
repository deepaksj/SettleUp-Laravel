<table class="table">
	<tr  class="info">
		<th class="col-sm-3"><a href="/settlements?sortBy=counterpartyName&sortOrder={{$sortOrder}}">User</a></th>
		@if(!$statusTab)
			<th class="col-sm-4">Report Title</th>
			<th class="col-sm-2.5">Close Date</th>
			<th class="col-sm-2">Amount</th>
			<th class="col-sm-0.5"></th>
		@else
			<th class="col-sm-4"><a href="/settlements?sortBy=reportTitle&sortOrder={{$sortOrder}}">Report Title</a></th>
			<th class="col-sm-2.5"><a href="/settlements?sortBy=closeDate&sortOrder={{$sortOrder}}">Close Date</a></th>
			<th class="col-sm-2">Amount</th>
		@endif
	</tr>
	<?php $userTotal = 0 ?> <!-- Hack...Come up with a better solution -->
	<?php $prevCounterparty = "" ?>
	@forelse($userSettlements as $settlement)
		<tr>
			<td style="{{($prevCounterparty==$settlement->counterpartyId?'border-top: none;':'')}}">{{$prevCounterparty==$settlement->counterpartyId?'':$settlement->counterpartyName}}</td>
			<td>{{$settlement->reportTitle}}</td>
			<td>{{date('d M Y', strtotime($settlement->closeDate))}}</td>
			@if(\Auth::user()->id == $settlement->owed_id)
				<td><span style="font-weight: bold;" hidden>User Total: </span><span value="{{$settlement->amount}}" class="currency" id="{{$settlement->id}}mainspan" data-toggle="tooltip" data-placement="auto right" title="{{$settlement->counterpartyName}} owes you ${{ number_format($settlement->amount, 2, ".", ",") }}">{{ number_format($settlement->amount, 2, ".", ",") }}</span><span id="{{$settlement->id}}altspan" data-toggle="tooltip" data-placement="auto right" title="{{$settlement->counterpartyName}} owes you ${{ number_format($settlement->amount, 2, ".", ",") }}" hidden>---</span></td>
				@if(!$statusTab)
					<td>{!! Form::checkbox('settlement_ids[]', $settlement->id, false, ['userId' => $settlement->counterpartyId, 'trnAmount' => $settlement->amount, 'userName' => $settlement->counterpartyName] ) !!}</td>
				@endif	
			@else
				<td><span style="font-weight: bold;" hidden>User Total: </span><span value="{{$settlement->amount*(-1)}}" class="currencyNegative" id="{{$settlement->id}}mainspan" data-toggle="tooltip" data-placement="auto right" title="You owe {{$settlement->counterpartyName}} ${{ number_format($settlement->amount, 2, ".", ",") }}">{{ number_format($settlement->amount, 2, ".", ",") }}</span><span id="{{$settlement->id}}altspan" data-toggle="tooltip" data-placement="auto right" title="You owe {{$settlement->counterpartyName}} ${{ number_format($settlement->amount, 2, ".", ",") }}" hidden>---</span></td>
				@if(!$statusTab)
					<td>{!! Form::checkbox('settlement_ids[]', $settlement->id, false, ['userId' => $settlement->counterpartyId, 'trnAmount' => $settlement->amount*(-1), 'userName' => $settlement->counterpartyName] ) !!}</td>
				@endif	
			@endif
		</tr>
		<?php $prevCounterparty = $settlement->counterpartyId ?>
		<?php $userTotal += $settlement->amount ?>
	@empty
		<tr><td colspan=4>You have no {{$statusTab?"closed":"open"}} settlements!</td></tr>
	@endforelse
	@if(!$statusTab)
		<tr class="reportTotals">
			<td colspan="3">Your Total</td>
			<td style="text-align: left;" >@include('utilities.currency', ['amountToBeFormatted' => $userTotal])</td>
			<td></td>
		</tr>
	@else
		<tr>
			<td colspan=4><?php echo $userSettlements->appends(['sortBy' => $sortBy, 'sortOrder' => ($sortOrder=='asc'?'desc':'asc')])->render(); ?></td>
		</tr>
	@endif
</table>
