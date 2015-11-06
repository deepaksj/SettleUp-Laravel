$(document).ready(function(){
	
	checkedOrderArr = []; //maintain an order of which owed users were checked first so amounts can be calculated appropriately
	previousRadioChecked = null;
	
	$(":radio").click(function () {
		if(previousRadioChecked != null) {
			while(checkedOrderArr[0]) {	
				$("#" + checkedOrderArr[0]).click();
			}
			restoreOwedOrOwee(previousRadioChecked);
			restoreOwedOrOwee($(this));
			restoreTransactionMessageDisplay(previousRadioChecked);
		}
		previousRadioChecked = $(this);
	});
	
	$(":radio:first").click();
	$("#submitBtnDiv").hide();

    $(":checkbox").click(function() {
    	oweeRadioInput = $(":radio:checked");
    	if($(this).prop("checked")) {
    		checkedOrderArr.push($(this).attr("id"));
    		transactionAmount = updateOwed($(this), Number(oweeRadioInput.attr("updatedValue")));
    		updatedOwedAmountToBeSettled = updateOwee(transactionAmount);
    		displayTransactionMessage(oweeRadioInput, $(this), transactionAmount);
    		if(updatedOwedAmountToBeSettled < 0.01) {
    			$(":checkbox:not(:checked)").prop("disabled", true);
    			$("#confirmBtn").prop("disabled", false);
    		}
    	}
    	else {
    		//If unchecked, remove user from the array
    		//Find user's index in the checked order
    		index = checkedOrderArr.indexOf($(this).attr("id"));
    		//remove user
    		checkedOrderArr.splice(index, 1);
    		
    		$("#confirmBtn").prop("disabled", true);
    		restoreOwedOrOwee($(this));
    		restoreTransactionMessageDisplay(oweeRadioInput);
    		$(":checkbox").prop("disabled", false);
    		owedAmountToBeSettled = restoreOwedOrOwee(oweeRadioInput);
    		owedAmountToBeSettled = Number(oweeRadioInput.val());
    		sumOfTransactions = 0;
    		for(i=0; i<checkedOrderArr.length; i++) {
   				transactionAmount = updateOwed($("#" + checkedOrderArr[i]), owedAmountToBeSettled);
    			sumOfTransactions += transactionAmount;
    			owedAmountToBeSettled -= transactionAmount;
    			displayTransactionMessage(oweeRadioInput, $("#" + checkedOrderArr[i]), transactionAmount);
    		}
    		if(owedAmountToBeSettled < 0.01) {
    			$(":checkbox:not(:checked)").prop("disabled", true);
    			$("#confirmBtn").prop("disabled", false);
    		}
    		updateOwee(sumOfTransactions);
    	}
    });
    
    $("#confirmBtn").click(function() {
    	$(":radio:checked").remove();
    	for(i=0; i<checkedOrderArr.length; i++) {
    		inputElement = $("#" + checkedOrderArr[i]);
    		if((updatedValue = Number(inputElement.attr("updatedValue"))) < 0.01) {
    			//Remove element from history of checks
    			index = checkedOrderArr.indexOf(inputElement.attr("id"));
    			checkedOrderArr.splice(index, 1);
    			i--;
    			//Remove element itself
    			inputElement.remove();
    		}
    		else {
    			inputElement.val(updatedValue);
    			inputElement.click();
    		}
    	}
    	//Prepare for next pairing if this is not the last one
    	if($(":radio").length > 0) {
	    	$(this).prop("disabled", true);
	    	previousRadioChecked = null;
	    	$(":radio:first").click();
	    	checkedOrderArr = [];
    	}
    	else {
    		$("#confirmBtnDiv").hide();
    		$("#submitBtnDiv").show();
    	}
    }); 

    function updateOwed(inputElement, owedAmountToBeSettled) {
    	inputElement.next().css("text-decoration", "line-through");
    	
    	currentValueOwed = Number(inputElement.val());
    	transactionValue = owedAmountToBeSettled - currentValueOwed;
    	if(transactionValue >= 0.01) {
    		transactionValue = currentValueOwed;
    		currentValueOwed = 0;
    	}
    	else {
    		transactionValue = owedAmountToBeSettled;
    		currentValueOwed = currentValueOwed - owedAmountToBeSettled;
    	}
    	inputElement.attr("updatedValue", currentValueOwed);
    	inputElement.next().next().text(currentValueOwed.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
    	inputElement.next().next().show();
    	
    	return transactionValue;
    }
    
    function restoreOwedOrOwee(inputElement) {
    	currentValueOwed = Number(inputElement.attr("updatedValue"));
    	originalValueOwed = Number(inputElement.val());

    	inputElement.next().removeAttr("style");
		inputElement.next().next().hide();
		inputElement.next().text(originalValueOwed.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
    	inputElement.attr("updatedValue", originalValueOwed);

    	return originalValueOwed;
    }

    function updateOwee(transactionAmount) {
    	inputElement = $(":radio:checked");
    	currentValueOwed = Number(inputElement.attr("updatedValue"));
    	originalValueOwed = Number(inputElement.val());
    	currentValueOwed = currentValueOwed - transactionAmount;
    	inputElement.attr("updatedValue", currentValueOwed);
    	
    	if((originalValueOwed - currentValueOwed) >= 0.01) {
	    	inputElement.next().css("text-decoration", "line-through");
	    	inputElement.next().next().text(currentValueOwed.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
	    	inputElement.next().next().show();
    	}
    	else {
    		inputElement.next().removeAttr("style");
    		inputElement.next().next().hide();
    	}
    	
    	return currentValueOwed;
    }
    
    function displayTransactionMessage(oweeInputElement, owedInputElement, transactionAmount) {
    	messageDiv = $("#messagesFor" + oweeInputElement.attr("id"));
    	modalMessageDiv = $("#modalMessagesFor" + oweeInputElement.attr("id"));
    	msg = "<span class='reportTitle'>" + oweeInputElement.attr("userName") + "</span> owes " + "<span class='reportTitle'>" + owedInputElement.attr("userName") + " </span> <span class='currency'>" + transactionAmount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + "</span><br>";
    	messageDiv.append(msg);
    	modalMessageDiv.append(msg);
    	//Add settlements as inputs to the form so they can be submitted
    	inputHtml = "<input name='settlement" + oweeInputElement.attr("id") + owedInputElement.attr("id") +"' type='hidden' oweeId='" + oweeInputElement.attr("id") + "' owedId='" + owedInputElement.attr("id") + "' value=" + transactionAmount + ">";
    	$("#inputSectionFor" + oweeInputElement.attr("id")).append(inputHtml);
    }
    
    function restoreTransactionMessageDisplay(oweeInputElement) {
    	messageDiv = $("#messagesFor" + oweeInputElement.attr("id"));
    	modalMessageDiv = $("#modalMessagesFor" + oweeInputElement.attr("id"));
    	messageDiv.html("");
    	modalMessageDiv.html("");
    	//remove all settlements
    	$("#inputSectionFor" + oweeInputElement.attr("id")).html("");
    }

});