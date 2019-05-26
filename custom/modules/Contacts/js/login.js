
function autoLogin(cont_id){

			$.ajax({
			method : 'POST',
			url: 'index.php?to_pdf=true&module=Contacts&action=autoLogin',
			data: {user_id : cont_id},
			success: function(response) {
				var url = "https://portal.digiboost.com/autologin/"+response;
				window.open(url, '_blank');
				
			}

	});
			

}
