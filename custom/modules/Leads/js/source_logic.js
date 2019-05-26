
function validate_source(){
	
	var status = $("#status").val();
	var first_name = $("#first_name").val();
	var last_name = $("#last_name").val();
	var lead_source = $("#lead_source").val();
	var lead_type = $("#lead_type").val();
	var description = $("#description").val();
	var email = $("#Leads0emailAddress0").val();
	var lead_source = $("#lead_source").val();
	var lead_source_p = $("#lead_source_1_c").val();
	var referal = $("#refered_by").val();
	var phone_work = $("#phone_work").val();
	var company_name = $("#company_name").val();
	if(!company_name){
		$("#company_name").css("background-color", "red");
		setTimeout(function(){
				$("#company_name").css("background-color", "#d8f5ee");
			},2000);
		return false;
	}
	if(!first_name){
		$("#first_name").css("background-color", "red");
		setTimeout(function(){
				$("#first_name").css("background-color", "#d8f5ee");
			},2000);
		return false;
	}
	if(!last_name){
		$("#last_name").css("background-color", "red");
		setTimeout(function(){
				$("#last_name").css("background-color", "#d8f5ee");
			},2000);
		return false;
	}
	if(!phone_work){
		$("#phone_work").css("background-color", "red");
		setTimeout(function(){
				$("#phone_work").css("background-color", "#d8f5ee");
			},2000);
		return false;
	}
	if(!description){
		$("#description").css("background-color", "red");
		setTimeout(function(){
				$("#description").css("background-color", "#d8f5ee");
			},2000);
		return false;
	}
	if(!email){
		return false;
	}
	if(!status){
		$("#status").css("background-color", "red");
		setTimeout(function(){
				$("#status").css("background-color", "white");
			},2000);
		return false;
	}
	
	if(!lead_source){
		$("#lead_source").css("background-color", "red");
		setTimeout(function(){
				$("#lead_source").css("background-color", "white");
			},2000);
		return false;
	}
	if(!lead_type){
		$("#lead_type").css("background-color", "red");
		setTimeout(function(){
				$("#lead_type").css("background-color", "white");
			},2000);
		return false;
	}

	/*LIst Logic 12-05-2019*/	
	if(lead_source == 'Call_In'){
		return validate(lead_source_p , referal);
	}
	else if(lead_source == 'Channel_Partners'){
		
		return validate(lead_source_p , referal);
	}
	else if(lead_source == 'Chat'){
		return validate(lead_source_p , referal);
	}	
	else if(lead_source == 'Personal_Referral'){
		if(referal == ''){
			$("#refered_by").css("background-color", "red");
			alert("Referred By Must Be Selected !!");
			setTimeout(function(){
					$("#refered_by").css("background-color", "#d8f5ee");
				},1000);
				
			return false;
			
		}
	}
	else if(lead_source == 'IB'){
		if(referal == ''){
			$("#refered_by").css("background-color", "red");
			alert("Referred By Must Be Selected !!");
			setTimeout(function(){
					$("#refered_by").css("background-color", "#d8f5ee");
				},1000);
				
			return false;
			
		}
	}
	else if(lead_source == 'Site_Submission'){
		return validate(lead_source_p , referal);
	}
	
	return true;
	
}

function validate(lead_source_p , referal){
	if(lead_source_p == ''){
		$("#lead_source_1_c").css("background-color", "red");
		alert("Secondary Lead Source Must Be Selected !!");
		setTimeout(function(){
				$("#lead_source_1_c").css("background-color", "white");
			},1000);
			
		return false;
	}
	if(referal == ''){
		$("#refered_by").css("background-color", "red");
		alert("Referred By Must Be Selected !!");
		setTimeout(function(){
				$("#refered_by").css("background-color", "#d8f5ee");
			},1000);
			
		return false;
		
	}
	return true;
}