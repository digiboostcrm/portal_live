var SMS_making_f = true;
var currentModule = '';
$(document).ready(function(){
	


});

function getSelectedRecordIds() {
	var ids = "";
	if (typeof document.MassUpdate != 'undefined') {
		if (typeof document.MassUpdate.elements != 'undefined') {
			inputs = document.MassUpdate.elements;
			ar = new Array();
			for (i = 0; i < inputs.length; i++) {
				if (inputs[i].name == 'mass[]' && inputs[i].checked && typeof(inputs[i].value) != 'function') {
					ar.push(inputs[i].value);
				}
			}
			return ar;
		}
	}
	return false;
}

/*
* handle outgoing and incoming twilio SMS 	
*/
function send_bulk_sms(module)
{
	smsIds= [];

	$(".makeSMS").attr("disabled","disabled");
	currentModule = module;
	if (!getSelectedRecordIds()) {
		alert('Select Atleast One Record');
		return false;
	}

	makingSMSDiv();// Create dynamic div for making outbound call			
	
}


/*
*	SMS div dynamically n destroy that if no needed
*/
function makingSMSDiv() {
	if (document.getElementById('myModal')) 
	{
		//code if element exists
	} 
	else {
		var newChatClient = document.createElement('div');
		newChatClient.setAttribute("id", "myModal");
		newChatClient.setAttribute("class", "modal");
		document.body.appendChild(newChatClient);						
	}						
	chatWindowAppear();
}

function chatWindowAppear() {
	chatObj = document.getElementById('myModal');
	var chatDiv = '<div class="modal-content">';
	chatDiv += '<div class="modal-header">';
	chatDiv += '<span class="number">Bulk SMS</span>';
	chatDiv += '<span onclick="hideChatWindow()" class="close">&times;</span>';
	chatDiv += '</div>';
	chatDiv += '<div class="smsCompose">';
    chatDiv += '<div class="smsText">';
    chatDiv += '<textarea id="sms_message" ></textarea>';
    chatDiv += '</div>';
    chatDiv += '<input type="image" src="custom/include/twilio_images/send_sms.png" id="sms_sender" value="Send" width="70" height="50" onclick="sendSMS()">';
    chatDiv += '</div>';
	chatDiv += '</div>';
	chatDiv += '</div>';
	
	chatObj.innerHTML = chatDiv;
	chatObj.style.display = 'block';
	
	$("#sms_message").keydown(function(e){
	    if (e.keyCode == 13 && !e.shiftKey)
	    {
			sendSMS();
	    }
	});
}
function hideChatWindow() {
    chatObj.style.display = "none";
    SMS_window_open = false;
}


/*
*	this will send the Instant SMS Message to destination number and also save current SMS in Database
*/
function sendSMS() {
	if (!getSelectedRecordIds()) {
		alert('Select Atleast One Record');
		return false;
	}
	var selectedRecordIds = getSelectedRecordIds();
	var smsText = document.getElementById('sms_message').value;
	if (smsText == '') {
		$( ".smsText" ).animate({ backgroundColor: "#f00"}, 100, "swing", function(){$("#sms_message").text("Please type a message");});
		$( ".smsText" ).animate({ backgroundColor: "#fff"}, 1200, "swing", function(){$("#sms_message").text("");});
		return false;
		//return;
	}

	if(currentModule == '') {
		console.log('Current Module not available');
		return;
	}
	var data = {selectedRecordIds: selectedRecordIds, smsText: smsText, currentModule: currentModule};

	// Fire off the request to create job to send SMS
    request = $.ajax({
        url: 'index.php?entryPoint=sendBulkSMS',
        type: "post",
        data: data
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        hideChatWindow();
    });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown){
        // Log the error to the console
        console.error(
            "The following error occurred: "+
            textStatus, errorThrown
        );
    });
}