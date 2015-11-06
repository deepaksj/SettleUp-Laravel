$(document).ready(function(){
	
	$(":radio:first").click();

	checkedOrderArr = []; //maintain an order of which owed users were checked first so amounts can be calculated appropriately
	
    $(":checkbox").click(function() {

    	indexOfCheckedUser = $(this).parent().parent().index();
    	if($(this).prop("checked")) {
    		checkedOrderArr.push(indexOfCheckedUser);
    	}
    	else {
    		//If unchecked, remove user from the array
    		//Find user's index in the checked order
    		index = checkedOrderArr.indexOf(indexOfCheckedUser);
    		//remove user
    		checkedOrderArr.splice(index, 1);
    		displayUpdatedValue($(this), 0, false);
    	}
    	$(":checkbox:not(:checked)").removeAttr("disabled");
		sumOfChecked = 0.0;
		msg = "";
		owedUsersArr = $(":checkbox");
		amountToBeSettled = Number($(":radio:checked").val());
		updatedAmountToBeSettled = amountToBeSettled; 
		
		for(i=0; i<checkedOrderArr.length; i++) {
			amntChecked = Number(owedUsersArr[checkedOrderArr[i]].value);
			sumOfChecked += amntChecked;
			
			transactionAmount = amntChecked;
			if(sumOfChecked >= amountToBeSettled) {
				if(i == 0) {
					transactionAmount = amountToBeSettled;
				}
				else {
					transactionAmount = amountToBeSettled - (sumOfChecked - amntChecked);
				}
				$(":checkbox:not(:checked)").attr("disabled", "true");
			}
			updatedAmountToBeSettled -= transactionAmount;
			displayUpdatedValue($("#" + owedUsersArr[checkedOrderArr[i]].id), amntChecked-transactionAmount, true);
			msg += "<br>" + $(":radio:checked").attr("id") + " owes " + owedUsersArr[checkedOrderArr[i]].name + " $" + transactionAmount.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,');
		}
		
		restoreRadioDisplay = false;
		if(checkedOrderArr.length > 0) {
			restoreRadioDisplay = true;
		}
		displayUpdatedValue($(":radio:checked"), updatedAmountToBeSettled, restoreRadioDisplay);
		
        $("#messageSection").html(msg);
        
        checkForPairingCompleteness();
    });
    
    function displayUpdatedValue(inputElement, updatedValue, checked) {
    	if(checked) {
	    	inputElement.next().css("text-decoration", "line-through");
	    	inputElement.next().next().text(updatedValue.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
	    	inputElement.next().next().show();
    	}
    	else {
    		inputElement.next().removeAttr("style");
    		inputElement.next().next().hide();
    	}
    	inputElement.attr("updatedValue", updatedValue);
    }
    
    function checkForPairingCompleteness() {
    	if(Number($(":radio:checked").attr("updatedValue")) < 0.01) {
    		$("#confirmBtn").prop("disabled", false);
    	}
    	else {
    		$("#confirmBtn").prop("disabled", true);
    	}
    }
    
    $("#confirmBtn").click(function() {
    	$(":radio:checked").prop("disabled", true);
    	$(":radio:checked").prop("checked", false);
    	
    	owedUsersArr = $(":checkbox");
    	for(i=0; i<checkedOrderArr.length; i++) {
    		updatedValue = Number(owedUsersArr[checkedOrderArr[i]].attributes.updatedValue.value) 
			if(updatedValue < 0.01) {
				owedUsersArr[checkedOrderArr[i]].remove();
			}
			else {
				owedUsersArr[checkedOrderArr[i]].value = updatedValue;
			}
    	}
    }); 
});

--------------------------------------------------------------------------

$(document).ready(function(){
	
	checkedOrderArr = []; //maintain an order of which owed users were checked first so amounts can be calculated appropriately
	owedAmountToBeSettled = 0;
	
	$(":radio").click(function () {
		owedAmountToBeSettled = Number($(this).val());
	});
	
	$(":radio:first").click();

    $(":checkbox").click(function() {
    	if($(this).prop("checked")) {
    		checkedOrderArr.push($(this).attr("id"));
    		updateValueAndDisplayOfOwed($(this));
    		//what impact does the checking (or unchecking of this owed user have on others?
    		updateOtherOwedUsers(true);
    	}
    	else {
    		//If unchecked, remove user from the array
    		//Find user's index in the checked order
    		index = checkedOrderArr.indexOf($(this).attr("id"));
    		//remove user
    		checkedOrderArr.splice(index, 1);
    		restoreOriginalValueAndDisplayOfOwed($(this));
    		updateOtherOwedUsers(false);
    	}
		updateDisplayOfOwee($(":radio:checked"));
		updateMessageSection();
		checkForPairingCompleteness();
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
    	//Prepare for next pairing
    	$(this).prop("disabled", true);
    	$(":radio:first").click();
    	checkedOrderArr = [];
    	updateOtherOwedUsers(false);
    }); 
    
    function updateValueAndDisplayOfOwed(inputElement) {
    	inputElement.next().css("text-decoration", "line-through");
    	
    	updatedValue = Number(inputElement.attr("updatedValue"));
    	if((owedAmountToBeSettled-updatedValue) >= 0.01) {
    		owedAmountToBeSettled = owedAmountToBeSettled - updatedValue; 
    		updatedValue = 0;
    	}
    	else {
    		updatedValue = updatedValue - owedAmountToBeSettled;
    		owedAmountToBeSettled = 0;
    	}
    	inputElement.attr("updatedValue", updatedValue);
    	inputElement.next().next().text(updatedValue.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
    	inputElement.next().next().show();
    }
    
    function restoreOriginalValueAndDisplayOfOwed(inputElement) {
		inputElement.next().removeAttr("style");
		inputElement.next().next().hide();
		originalValue = Number(inputElement.val());
		inputElement.next().text(originalValue.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
		owedAmountToBeSettled = owedAmountToBeSettled + (originalValue - Number(inputElement.attr("updatedValue")));
    	inputElement.attr("updatedValue", originalValue);
    }
    
    function updateMessageSection() {
    	messageDiv = $("#messagesFor" + $(":radio:checked").attr("id"));
    	msg = "";
    	for(i=0; i<checkedOrderArr.length; i++) {
    		inputElement = $("#" + checkedOrderArr[i]);
    		originalValue = Number(inputElement.val());
    		updatedValue = Number(inputElement.attr("updatedValue"));
    		transactionValue = originalValue - updatedValue;
    		msg += $(":radio:checked").attr("userName") + " owes " + inputElement.attr("userName") + " " + transactionValue.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + "<br>";
    	}
    	messageDiv.html(msg);
    }
    
    function updateOtherOwedUsers(checked) {
    	if(!checked) {
    		for(i=0; i<checkedOrderArr.length; i++) {
    			updateValueAndDisplayOfOwed($("#" + checkedOrderArr[i]));
    		}
    	}
    	if(owedAmountToBeSettled < 0.01) {
    		$(":checkbox:not(:checked)").attr("disabled", "true");
    	}
    	else {
    		$(":checkbox:not(:checked)").removeAttr("disabled");
    	}
    }
    
    function updateDisplayOfOwee(inputElement) {
    	if(Number(inputElement.val()) != owedAmountToBeSettled) {
	    	inputElement.next().css("text-decoration", "line-through");
	    	inputElement.next().next().text(owedAmountToBeSettled.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,'));
	    	inputElement.next().next().show();
    	}
    	else {
    		inputElement.next().removeAttr("style");
    		inputElement.next().next().hide();
    	}
    }
    
    function checkForPairingCompleteness() {
    	if(owedAmountToBeSettled < 0.01) {
    		$("#confirmBtn").prop("disabled", false);
    	}
    	else {
    		$("#confirmBtn").prop("disabled", true);
    	}
    }
    
});