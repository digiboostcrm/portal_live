//$("#created_by_name").prop("readonly", true);

function assign_user(rec_id , assVal){
	
		var ass_id = $("#ass_id").val();
		var ass_name = $("#ass_name").val();
		if(assVal != 'assign'){
			ass_id = '';
		}
			
		$.ajax({
			method : 'POST',
			url: 'index.php?to_pdf=true&module=Cases&action=ass_user',
			data: {user_id : ass_id, id: rec_id},
			success: function(response) {
				if(response){
					$("#assigned_user_id").text(ass_name);
					alert(ass_name + ' ' +'This ticket is Assigned To you ! ');
				}else{
					$("#assigned_user_id").text('');
					alert('Assigned User is Removed  ! ');
				}
				console.log(response);
			}
	});
	
}