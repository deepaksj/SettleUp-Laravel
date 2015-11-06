@extends('app')
@section('content')

	<h3>Your Settlement Details</h3>
	<hr>
	@include('utilities.flashmessage')
	<div class="col-sm-7">
	    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	        <li class="{{!$statusTab?'active':''}}"><a href="/settlements?statusTab=0">Open Settlements</a></li>
	        <li class="{{$statusTab?'active':''}}"><a href="/settlements?statusTab=1">Completed Settlements</a></li>
	    </ul>
		<div id="my-tab-content" class="tab-content">
			<div class="tab-pane active" id="openSettlements">
				@if(!$statusTab)
					{!! Form::open(['url' => 'settlements/complete']) !!}
						@include('settlements._showSettlements')
				    	<div class="row" style="float:right;">
				    		<div class="form-group"><button class="btn btn-info" id="confirmationBtn" type="button" data-toggle="modal" data-target="#confirmSettlements" disabled>Settle Amounts</button>
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
				@else
					@include('settlements._showSettlements')
				@endif
			</div>
		</div>
	</div>	
@stop
@section('footer')
	<script type="text/javascript">
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();

		    $(":checkbox").click(function() {
				allAmountsOfUserArr = $("[userId='" + $(this).attr("userId") + "']");
				checkedAmountsOfUserArr = [];
				for(i=0; i<allAmountsOfUserArr.length; i++) {
					settlementId = allAmountsOfUserArr[i].value;
					mainSpan = $("#" + settlementId + "mainspan");
					altSpan = $("#" + settlementId + "altspan");  
					originalValue = Number(mainSpan.attr("value"));
					setDisplaySpanWithAmount(mainSpan, originalValue);
					altSpan.hide();
					mainSpan.prev().hide();
					if(allAmountsOfUserArr[i].checked) {
						checkedAmountsOfUserArr.push(allAmountsOfUserArr[i]);
					}
				}
				sumOfCheckedAmounts = 0;
				if(checkedAmountsOfUserArr.length > 1) {
					for(i=0; i<checkedAmountsOfUserArr.length; i++) {
						sumOfCheckedAmounts += Number(checkedAmountsOfUserArr[i].attributes.trnAmount.value);
						settlementId = checkedAmountsOfUserArr[i].value;
						mainSpan = $("#" + settlementId + "mainspan");
						altSpan = $("#" + settlementId + "altspan");  
						if(i<checkedAmountsOfUserArr.length-1) {
							mainSpan.hide();
							altSpan.show();
						}
						else {
							setDisplaySpanWithAmount(mainSpan, sumOfCheckedAmounts);
							mainSpan.prev().show();
							altSpan.hide();
							addConfirmationMessage(checkedAmountsOfUserArr[i], sumOfCheckedAmounts);
						}
					}
		    	} else if(checkedAmountsOfUserArr.length == 1) {
		    		addConfirmationMessage(checkedAmountsOfUserArr[0], Number(checkedAmountsOfUserArr[0].attributes.trnAmount.value));
		    	} else {
			    	removeConfirmationMessage($(this));
		    	}
		    	if($('#confirmationMessage:has(li)').length>0) {
			    	$('#confirmationBtn').prop("disabled", false);
		    	} else {
		    		$('#confirmationBtn').prop("disabled", true);
		    	}
			});

			function addConfirmationMessage(checkBox, amount) {
				msg = "";
				if(amount<0) {
					msg += "You paid " + checkBox.attributes.userName.value + " $" + ((-1)*amount).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
				} else {
					msg += checkBox.attributes.userName.value + " paid you $" + amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
				}
					
				//check if a previous message exists and either create a new one or overwrite it
				msgDiv = $("#" + checkBox.attributes.userId.value + "Message");
				if(msgDiv==null || msgDiv.length==0) {
					$("#confirmationMessage").append("<li id='" + checkBox.attributes.userId.value+ "Message'>" + msg + "</li>");
				} else {
					msgDiv.html(msg);
				}
			}

			function removeConfirmationMessage(checkBox) {
				msgDiv = $("#" + checkBox.attr("userId") + "Message");
				if(msgDiv!=null && msgDiv.length>0) {
					msgDiv.remove();
				} 
			}

			function setDisplaySpanWithAmount(displaySpan, amount) {
				mainSpan.removeClass(); 
				if(amount < 0){
					amount *= (-1);
					mainSpan.addClass("currencyNegative")
				}
				else {
					mainSpan.addClass("currency");
				}
				mainSpan.text(amount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
				mainSpan.show();
			}
		});
	</script>
@stop