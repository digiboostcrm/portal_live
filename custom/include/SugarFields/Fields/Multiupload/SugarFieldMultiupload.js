
function removeLine(obj, field_id)
{
	if(confirm('Are you sure to remove this file?'))
	{
		var jqObj 		= $(obj);
		var container 	= jqObj.closest('tr');
		var index 		= container.attr("id").split('_')[1];
		data 			= new FormData();
		var img 		= jqObj.closest('td').siblings().text();
		data.append( 'file_name', img);
		var record 		=  $('input[name^=record]').val();
		container.remove();
		data.append('record_id', record);
		data.append('field_id', field_id);
		
		$.ajax({
			url: "multiupload.php",
			type: "POST",
			data:  data,
			contentType: false,
			cache: false,
			processData:false,
			success: function(data)
			{
				console.log(data);
			},
			error: function() 
			{
				console.log("An error occured while deleting this file.");
				return false;
			},
			complete: function (data) {
				console.log("File deletion completed.");
			} 	        
	   });
	}
}