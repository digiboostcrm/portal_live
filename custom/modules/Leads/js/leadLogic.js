$( document ).ready(function() {
	var oppID = $.trim($("#opportunity_id").val());
	if(oppID.length > 0){
		$("#convert_lead_button").hide();
	}
});
	