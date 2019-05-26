var notes = $('#add_notes').html();
$("#add_notes").parent("div").parent("div").hide();
console.log("shatty testing ");
if(notes){	
	makeHtml(notes);
}
else{
	$("#lessmargin").hide();
}


function add_notes_logic(){
				if($("#update_text").val() == ""){
					alert("Please Add Notes ! ");
					return false;
				}
				var text = $('#update_text').val();
				var d = new Date();
				var date =	d.toLocaleString();
				var date =	date.replace(',','');;
				
				var user = $('#user').val();
				var rec_id = $('input[name=record]').val();
				var notes = $('#add_notes').html();
				
				var datArr= '';
				if(notes){					
					var obj = jQuery.parseJSON( notes );	
					$( obj ).each(function( index , value) {
						datArr += '{"date":"'+value.date+'","text":"'+value.text+'","user":"'+value.user+'"},';					   
					});
				}
				
				var data = '['+datArr + '{"date":"'+date+'","text":"'+text+'","user":"'+user+'"}]';
					
				$.ajax({
						method : 'POST',
						url: 'index.php?to_pdf=true&module=Opportunities&action=addNote',
						data: {data : data, id: rec_id},
						success: function(response) {
							console.log(response);
							makeHtml(response);
						}
				});
					
					
			}

function makeHtml(response){
	
			var obj = JSON.parse(response);
			var htmlDiv= '';
			$( obj ).each(function( index , value) {
				
				
				   htmlDiv += '	<div id="lessmargin"><div id="caseStyleUser"><span>'+value.user+ " " +value.date+'</span>'
									+'<div id="" class="caseUpdate" style="display: block;">'+value.text+'</div>'
									+'</div>'
								+'</div>';
				   
				   
			});
		   $("#add_notes_div_1").html(htmlDiv);
		   $("#add_notes").html(response);
		   $("#update_text").val("");

}			