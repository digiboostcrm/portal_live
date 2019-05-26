	$('#convert_create_Accounts').val('false');
		toggleDisplay('createAccounts');
	$('#convert_create_Opportunities').val('true');
	$('#newOpportunities').prop('checked', true);
		toggleDisplay('createOpportunities');
	/*remove check box*/
	setTimeout(function(){
		$('#newAccounts').prop('checked', false);
	},1000);
	/*Validation for opportunities*/
	$( "#SAVE_HEADER" ).click(function() {
		if(!$("#Opportunitiesname").val()){
			$('#Opportunitiesname').css("background-color", "red");
			setTimeout(function(){
				$('#Opportunitiesname').css("background-color", "rgba(165, 232, 214, 1)");
			},1000);
			return false;
		}
		if(!$("#Opportunitiesamount").val()){
			$('#Opportunitiesamount').css("background-color", "red");
			setTimeout(function(){
				$('#Opportunitiesamount').css("background-color", "rgba(165, 232, 214, 1)");
			},1000);
			return false;
		}
		if(!$("#Opportunitiesdate_closed").val()){
			$('#Opportunitiesdate_closed').css("background-color", "red");
			setTimeout(function(){
				$('#Opportunitiesdate_closed').css("background-color", "rgba(165, 232, 214, 1)");
			},1000);
			return false;
		}
	});
/*Probablity logic */
	$( "#Opportunitiessales_stage" )
	  .change(function () {
		var str = "";
		str = this.value;
		/*probiblty logic */
		if(str == 'Discovery')
			$("#Opportunitiesprobability").val("5");
		else if(str == 'Educate')
			$("#Opportunitiesprobability").val("30");
		else if(str == 'Validate')
			$("#Opportunitiesprobability").val("50");
		else if(str == 'Justify')
			$("#Opportunitiesprobability").val("75");
		else if(str == 'Closed Won')
			$("#Opportunitiesprobability").val("95");
		else
			$("#Opportunitiesprobability").val("0");
		
		
	  })
	  .change();	