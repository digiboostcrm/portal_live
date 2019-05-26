	var destination_number = ''
	var source_number_SMS = ''; 
	var userNumber = '';
	var final_picked_date = '';
	var SMS_window_open = false;
	var sent_flag = false;
	$(document).ready(function(){
		$( "div[type='phone']" ).each(function(){
			var phone = $(this).text();
			phone = phone.trim();
			if($(this).attr('field') != 'phone_fax') {
				$(this).append('<input type="image" class="field attribute" title="Click to Start Conversation" id="startSMS" name = "startSMS" style="width:20px;height:20px;border:none;float:right" onclick="callOpenChat(\''+phone+'\')" src="custom/include/twilio_images/click_sms.png" />');
			}
		});

		var modal = document.getElementById('myModal');
		window.onclick = function(event) {
		    if (event.target == modal) {
		        modal.style.display = "none";
		    }
		}

	});
	function callOpenChat(destination) {
	    request = $.ajax({
	        url: 'index.php?entryPoint=getConfig',
	        type: "post",
	    });
	    request.done(function (response, textStatus, jqXHR){
	        var config = JSON.parse(response);
	        openChat(destination, config[2]);
	    });
	    request.fail(function (jqXHR, textStatus, errorThrown){
	        console.error(
	            "The following error occurred: "+
	            textStatus, errorThrown
	        );
	    });
	}
	function openChat(destination, source_num) {
		userNumber = destination;
		final_picked_date = '';		
	    SMS_window_open = false;
		smsIds= [];
		$(".makeSMS").attr("disabled","disabled");
		source_number_SMS = process_number(source_num);
		destination_number = process_number(destination);
		if(destination_number == source_number_SMS)
		{
			alert("Conversation cannot be made between same source and destination !");
			$(".makeSMS").removeAttr("disabled");
		}
		else if(destination_number == false || source_number_SMS == false)
		{
			alert("Invalid Source / Destination Number !");
			$(".makeSMS").removeAttr("disabled");
		}
		else
		{	
			source_number_SMS = source_number_SMS.replace("+","%2B");
			destination_number = destination_number.replace("+","%2B");
			
			
			makeChatWindow();			
			
		}
	}


		function process_number(destination)
		{          
			destination = destination.trim();  
			var regex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
            if (!destination.match(regex)) {
                return false
            }
			
			dest_number ='';
			dest_original = destination;
			dest_num = destination.replace(/[^0-9]/g,'');
			if(dest_num.substr(0,2) == "00") 
			{
				dest_number = dest_num.replace("00","+");
				if(dest_number.length < 8 || is_repeated_digit(dest_number)) ///check for invalid number
				{				
					dest_number = false;
				}
			}
			else if(dest_num.substr(0,1) == "1" && dest_num.length == 11) {
				if(is_repeated_digit(dest_num))///check for invalid number
				{
					dest_number = false;
				} else {

					dest_number = "+"+dest_num;
				}
			}
			else if(dest_original.substr(0,1) == "+")
			{
				dest_number = "+"+dest_num;
				if(dest_number.length < 8 || is_repeated_digit(dest_number))///check for invalid number(include alphabetical or alphanumerical phone number or single digit repeated phone number)
				{
					dest_number = false;
				}
			}
			else
			{
				if(dest_num.length < 6 || is_repeated_digit(dest_num))///check for invalid number
				{
					dest_number = false;
				}
				else
				{			
					dest_number = "+1"+dest_num;
				}
			}
			return dest_number;
		}


	function is_repeated_digit(phone_number) {
		var arr = '';
		arr = phone_number.split(''); //convert string to array
		var freq_of_num = 0;
		var rep_num ='';
		for(var i=0;i<arr.length;i++)
		{
			if(freq_of_num == 0)
			{
				rep_num = arr[i];
				freq_of_num++;
			}
			else if(freq_of_num >0)
			{
				if(arr[i]== rep_num)
				{
					freq_of_num++;
				}
				rep_num = arr[i];
			}
		}
		if(freq_of_num >= 6)
		{
			return true;        
		}
		else
		{
			return false;
		}
	}


	function makeChatWindow() {		
		if (document.getElementById('myModal')) 
		{
		} 
		else {
			var newChatClient = document.createElement('div');
			newChatClient.setAttribute("id", "myModal");
			newChatClient.setAttribute("class", "modal");
			document.body.appendChild(newChatClient);						
		}						
		chatWindowAppear();
		conversationTimeout();
	}
	function conversationTimeout()
	{
		SMS_window_open = true;
		conversationTimer = setTimeout("fetchConversation()",3000);		
	}
	function fetchConversation() {
		getSMSHistory()
	}

	/*
	*	Show Chat client
	*/
	function chatWindowAppear() {
		chatObj = document.getElementById('myModal');
		var chatDiv = '<div class="modal-content">';
		chatDiv += '<div class="modal-header">';
		chatDiv += '<span class="number">'+userNumber+'</span>';
		chatDiv += '<span onclick="hideChatWindow()" class="close">&times;</span>';

		chatDiv += '<span onclick="minimizeChatWindow()" class="minimize">&macr;</span>';
		chatDiv += '<div class="callStatus"><span id="sms_log" class="yellow">Loading...</span></div>';
		chatDiv += '</div>';
		chatDiv += '<div class="modal-body smsView" id="sms_area" ></div>';

		chatDiv += '<div class="modal-footer">';
		chatDiv += '<div class="smsCompose">';
        chatDiv += '<div class="smsText">';
        chatDiv += '<textarea id="sms_message" ></textarea>';
        chatDiv += '</div>';
        chatDiv += '<input type="image" src="custom/include/twilio_images/send_sms.png" id="sms_sender" value="Send" width="70" height="50" onclick="sendSMS()">';
        chatDiv += '</div>';
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

	function minimizeChatWindow() {
		hideChatWindow();
    	var minimizeChatClient = document.createElement('div');
		minimizeChatClient.setAttribute("id", "myModalMinimize");
		minimizeChatClient.setAttribute("class", "chatWindow-min");
		//minimizeChatClient.setAttribute("onclick", "reopenChatWindow()");
		document.body.appendChild(minimizeChatClient);		
		//minimizeChatClient.style.display = 'block';

		appendItems();
	}

	function appendItems() {		
		minChat = document.getElementById('myModalMinimize');		
		var minDiv = '<div class="minWin-content">';
		minDiv += '<span class="numberMin">'+userNumber+'</span>';
		minDiv += '<span onclick="hideMinChatWindow()" class="closeMin">&times;</span>';
		minDiv += '<span onclick="reopenChatWindow()" class="closeMin"> + </span>';
		minDiv += '</div>';
		minChat.innerHTML = minDiv;
		minChat.style.display = 'block';
	}
	function reopenChatWindow() {
		hideMinChatWindow();
		openChat(userNumber);
	}
	function hideMinChatWindow() {		
	    minChat.style.display = "none";
	}
	function showSMSConversation(unit_sms) {			
		if(unit_sms.direction == "outgoing")	
		{
			displayOutboudConversation(unit_sms);					
		}
		else if(unit_sms.direction == "incoming")	
		{
			displayInboundConversation(unit_sms);
		}			
	}

	function displayOutboudConversation(unit_sms) {
		inboundConDiv = '';
		/* Start User SMS area*/
		inboundConDiv += '<div class="userSMS">';						
		inboundConDiv += '<div class="chatBox">';			
		inboundConDiv += '<div class="contentCenter"><p2>'+unit_sms.message+'</p2></div>';
		inboundConDiv += '<div class="bottomCenter"><span id ="'+unit_sms.id+'">'+unit_sms.status+" "+unit_sms.date_entered+'</span></div>';
		inboundConDiv += '</div>'; // end date class 
		inboundConDiv += '</div>'; // end chatBox class 
		inboundConDiv += '</div>'; //end userSMS class
		inboundConDiv += '<div class="clear"></div>';

		if($(".clientSMS").length != 0)
		{				
			$(".smsView").append(inboundConDiv);	
			$('.smsView').animate({scrollTop: $('.smsView').get(0).scrollHeight}, 50);								
		}
		else if($(".userSMS").length != 0)
		{				
			$(".smsView").append(inboundConDiv);
			$('.smsView').animate({scrollTop: $('.smsView').get(0).scrollHeight}, 50);				
		}
		else
		{				
			$(".smsView").html(inboundConDiv);				
			$('.smsView').animate({scrollTop: $('.smsView').get(0).scrollHeight}, 50);				
		}					
	}

	function displayInboundConversation(unit_sms)
	{
		outboundConDiv = '';
		outboundConDiv += '<div class="clientSMS">';			
		outboundConDiv += '<div class="chatBox">';
		outboundConDiv += '<div class="contentCenter"><p2>'+unit_sms.message+'</p2>';			
		outboundConDiv += '</div>';
		outboundConDiv += '<div class="bottomCenter"><span id ="'+unit_sms.id+'">'+unit_sms.status+" "+unit_sms.date_entered+'</span></div>';
		outboundConDiv += '</div>'; // end chatBox class
		outboundConDiv += '</div>'; //end chatBox  class			
		outboundConDiv += '<div class="clear"></div>';
		outboundConDiv += '</div>';

		if($(".clientSMS").length != 0)
		{				
			$(".smsView").append(outboundConDiv);
			$('.smsView').animate({scrollTop: $('.smsView').get(0).scrollHeight}, 50);					
		}
		else if($(".userSMS").length != 0)
		{				
			$(".smsView").append(outboundConDiv);
			$('.smsView').animate({scrollTop: $('.smsView').get(0).scrollHeight}, 50);				
		}
		else
		{						
			$(".smsView").html(outboundConDiv);				
			$('.smsView').animate({scrollTop: $('.smsView').get(0).scrollHeight}, 50);				
		}
	}

	function sendSMS(){
		if($("#sms_message").val() == ''){
			$( ".smsText" ).animate({ backgroundColor: "#f00"}, 100, "swing", function(){$("#sms_log").text("Please type a message");});
			$( ".smsText" ).animate({ backgroundColor: "#fff"}, 1200, "swing", function(){$("#sms_log").text("");});
			return false;
		}
		else{
			sendingSMS();																
		}
	}
	function sendingSMS()
	{
		if($("#sms_message").val() == '')
		{
			return false;
		}
		else
		{				
			var sms_text_encodable = $("#sms_message").val();
			var sms_text=document.getElementById('sms_message').value;
			$("#sms_message").val('');	// use to get empty textarea to represent message is sent and will display in the history div later on in response of the long polling ajax call 
			if(sent_flag == false) //setting to send sms only once
			{
				sent_flag = true;
				$("#sms_log").text("Sending...");
				$.ajax({
					url:"index.php",
					type:"POST",
					dataType:"html",
					data:"module=sp_sms_log&action=save&sugar_body_only=true&source_number="+source_number_SMS+"&destination_number="+destination_number+"&sms_text="+sms_text+"&direction=outgoing",
					async:true,
					cache:false,
					success:function(response)
					{
						if(response == "failed")
						{												
							$("#sms_log").text("Failed");
							alert("Invalid destination number or twilio settings");
							sent_flag = false;				
						}
						if(response == "sent")
						{
							sent_flag = false;															
							$("#sms_log").text("Sent");	
						}						
					},
					error:function(jqXHR,textStatus,errorThrown)
					{
						if(jqXHR.readyState == 0)
						{
							alert("Internet Connection Problem");						
						}
						else
						{
							console.log("Error Occurred at server side(sending Outbound SMS) : "+textStatus);
						}							
					}			
				});				
			}
			return true;
		}						
					
	}
	function getSMSHistory()
	{	
		var found = false;
		$.ajax({
			url:"index.php",
			data:"module=sp_sms_log&action=fetch_sms_conversation&sugar_body_only=true&source_number="+source_number_SMS+"&destination_number="+destination_number+"&final_picked_date="+final_picked_date,
			type: "POST",
			dataType:"json",
			async:true,
			cache:false,			
			success:function(SMS_Conversation)
			{			
				var final_date_picker = 0;
				
				if(SMS_Conversation.result != "empty") //check if there is some sms conversation in database relating to current destination number
				{						
					$.each(SMS_Conversation,function(index,unit_sms) //iterate through all sms objects					
					{	
						found = false;
						final_date_picker++;
						for (var k=0;k<smsIds.length;k++)
						{
							if (smsIds[k] == unit_sms.id)
							{
								found = true;
								if (typeof $('#'+unit_sms.id) !='undefined')
								{
								  $('#'+unit_sms.id).text(unit_sms.status+" "+unit_sms.date_entered);
								}
								break;
							}
						}
						if (found == false)
						{
						showSMSConversation(unit_sms); // display outbound/inbound sms messages and date created	
						}
																									
						if(final_date_picker == SMS_Conversation.length)							
						{								
							final_picked_date = unit_sms.date_entered_query; //to query from database getting the date_entered of the latest sms fetched in a single current ajax call								
							final_picked_date = encodeURIComponent(final_picked_date);								
						}
						smsIds.push(unit_sms.id);					
					});
					
					//$("#sms_log").text("Fetched");
					$("#sms_log").text("");	
				}
				else
				{
					if(final_picked_date == '')
					{
						//$("#sms_log").text("No Conversation Found");
						$("#sms_log").text("");
					}							
				}		
				if(SMS_window_open == true)	
				{
					conversationTimer = setTimeout("fetchConversation()",3000); //restart ajax call to ensure polling and fetching the updated sms					
				}	
				else if(SMS_window_open == false)
				{
					final_picked_date = '';
				}
			},
			error:function(jqXHR,textStatus,errorThrown)
			{
				if(jqXHR.readyState == 0)
				{
					alert("Internet Connection Problem");						
				}
				else
				{
					console.log("Error Occurred at server side(getting SMS Conversation) : "+textStatus);
				}					
			}					
		});
	}