$('#friendsList').select2({
	placeholder: "Select a friend"
});

$('#addFriend').on('show.bs.modal', function() {
	//Reset the modal for a fresh start
	$('#addFriendName').val("");
	$('#addFriendEmail').val("");
	$('#errorsDiv').html("");
});

$('#addFriendForm').submit(function (e) {
	e.preventDefault();
	$.ajax ({
	    url: '/quickAddFriend',
        type: "post",
        data: $('#addFriendForm').serialize(),
        
	}).done( function(data) {
		$('#infoAndErrorsDiv').addClass("alert alert-info");
		if(data[1] != 0) {
			//If user needs to be added
			$('#friendsList').append('<option value=' + data[0].id + '>' + data[0].name + '</option>');
		}
		$('#friendsList option[value="' + data[0].id + '"]').prop('selected', true);
		$('#friendsList').change();
		$('#infoAndErrorsDiv').html(data[2]);
		$('#modalCloseBtn').click();
		
		
    }).fail(function(data, status, errorThrown) {

    	if(status == "error") {
	    	var htmlStr = '<ul class="alert alert-danger">';
	    	if(data.responseJSON.name != null) {
		    	htmlStr += "<li>" + data.responseJSON.name[0] + "</li>";
	    	}
	    	if(data.responseJSON.email != null) {
		    	htmlStr += "<li>" + data.responseJSON.email[0] + "</li>";
	    	}
	    	$('#errorsDiv').html(htmlStr);
    	}
    });
});
