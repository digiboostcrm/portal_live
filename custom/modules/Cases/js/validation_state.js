$("#total_spent_minuts").attr('readonly', true);
$("#spent_hours").val('');
/* valid$ation on bases of state*/
	function check_state(db_start_date , db_end_date , rec_id){
		/*Field Validation */
		var time_piriority = $("#spent_hours").val();
		var time_category = $("#time_category").val();
		var account_name = $("#account_name").val();
		var subject = $("#subject").val();
		var category = $("#category").val();
		var status1 = $("#status").val();
		var update_attachment = $("#update_attachment").val();
		var case_attachment = $("#case_attachment").val();
		var myarr = ["txt", "doc", "docx", "pdf", "png", "jpeg", "jpg", "img", "ppt", "xls", "xlsx", "csv", "css", "xml", "gif"];
		
		//var description = $.trim(tinyMCE.get('description').getContent());
		if(!account_name){	
			$("#account_name").css("background-color", "red");
			//$("#account_name").parent("div").append("<span style='color : red'>Missing Field Required : Account Name <span>");
			setTimeout(function(){
					$("#account_name").css("background-color", "#a5e8d6");
				},1000);
				
			return false;
		}
		
		if(!time_piriority){			
			return false;
		}else if(time_piriority < 0){
			alert("Minuts Worked Value shouldn't be Negative '-' !");
			return false;
		}
		if(!time_category){			
			return false;
		}
		if(!subject){			
			return false;
		}
		if(!category){			
			return false;
		}
		if(!status1){			
			return false;
		}
		/*
		if(description === ''){			
			alert("Description Box Must Be Filled !")
			return false;
		}
		*/		
		if(case_attachment){
			case_attachment = case_attachment.split(".");
			case_attachment_len = case_attachment.length;

			var noArray = parseInt(case_attachment_len)-1;

			case_attachment_ext = case_attachment[noArray].toLowerCase();
			var checkList = (myarr.indexOf(case_attachment_ext) > -1);
			if(checkList === false){
				$("#case_attachment").css("color", "red");
				alert('Case Attachment File Extension "'+case_attachment_ext+'" Not Allowed !');
				return false;
			}
			
		}
		if(update_attachment){
			update_attachment = update_attachment.split(".");
			update_attachment = update_attachment.split(".");
			update_attachment_len = update_attachment.length;
			var noArray = parseInt(update_attachment_len)-1;
			update_attachment_ext = update_attachment[noArray].toLowerCase();
		var checkList = (myarr.indexOf(update_attachment_ext) > -1);
			if(checkList === false){
				$("#update_attachment").css("color", "red");
				alert('Update Attachment File Extension "'+case_attachment_ext+'" Not Allowed !');
				return false;
			}
		}

		var resultDate = '';
		var startDate = new Date($("#start_date").val());
		var endDate = new Date ($("#end_date").val());
		if(rec_id ){
			db_end_date = new Date(db_end_date);
			db_end_date.setHours(0,0,0,0);
			db_start_date = new Date(db_start_date);
			db_start_date.setHours(0,0,0,0);
			
			endDate.setHours(0,0,0,0);
			startDate.setHours(0,0,0,0);
			
		}else{
			
			if(startDate != 'Invalid Date'){			
				startDate.setHours(0,0,0,0);
			}
			if(endDate != 'Invalid Date'){
				endDate.setHours(0,0,0,0);
			}
		}
	
	/*Date Validation */
		var state = $("#status").val();	

		if(state == 'Closed_Closed'){
			if(startDate == 'Invalid Date'){
				alert ("Start Date Must Be Filled! ");
				return false;
			}else if(endDate == 'Invalid Date'){
				alert ("End Date Must Be Filled!");
			}else{
				var resultDate = date_validation(startDate , endDate , rec_id);
				if(resultDate == true){
					return true;
				}
				
			}
		}
		else{
			
			if(startDate == 'Invalid Date' && endDate == 'Invalid Date'){
			
				return true;
			}
			else if(startDate > db_start_date || startDate < db_start_date){
				resultDate = date_validation(startDate , endDate , rec_id);
				if(resultDate == true){
			
					return true;
				}
				
			}else if(endDate > db_end_date || endDate < db_end_date){
				resultDate = date_validation(startDate , endDate , rec_id);
				if(resultDate == true){
			
					return true;
				}
				
			}else if(db_end_date == 'Invalid Date' && endDate != 'Invalid Date'){
				resultDate = date_validation(startDate, endDate , rec_id );
				if(resultDate == true){
				
					return true;
				}
			}else if(startDate == 'Invalid Date' && endDate != 'Invalid Date'){
				resultDate = date_validation(startDate, endDate , rec_id );
				if(resultDate == true){
				
					return true;
				}
			}
			else{
				return true;
			}

		}
	}
	
	
	function date_validation(startDate , endDate, rec_id){

		if (rec_id === undefined || rec_id === null || rec_id === '') {
			rec_id = 1;
		}else{
			rec_id = rec_id;
		}
		var _text = $.trim(tinyMCE.get('update_text').getContent());
		var todayDate = new Date();
		todayDate.setHours(0,0,0,0);
		if(startDate != 'Invalid Date' && endDate != 'Invalid Date'){
				if(startDate > endDate){
					alert("Start Date Must Be Less Than End Date! ");
					return false;
					
				}
				else if(startDate <= endDate && rec_id != 1)
				{
					if(_text && _text !== ""){
						return true;
					}else{
						alert('Update Text Must Be Filled !! ');
					}
				}
				else{
					return true;
				}
		}else if(startDate != 'Invalid Date' && endDate == 'Invalid Date'){
			if(_text && _text !== "" || rec_id == 1){
					return true;
				}else{
					alert('Update Text Must Be Filled !! ');
				}
		}else if(endDate != 'Invalid Date' && startDate == 'Invalid Date'){
					alert('Start Date Must Be Filled! ');
					return false;
			
				}
				else{
					
					return true;
				}
		
		
	}
	
