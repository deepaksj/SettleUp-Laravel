@if($amountToBeFormatted < 0)
	<span class="currencyNegative">{{ number_format($amountToBeFormatted*(-1), 2, ".", ",") }}</span>
@elseif($amountToBeFormatted == 0)
	-
@else
	<span class="currency">{{ number_format($amountToBeFormatted, 2, ".", ",") }}</span>
@endif
