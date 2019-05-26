
<?php

function custom_notes($focus)
{
	/*
<a href="" onclick="collapseAllUpdates(); return false;">Collapse All</a>
<a href="" onclick="expandAllUpdates(); return false;">Expand All</a>
*/	echo '<script type="text/javascript" src="custom/modules/Opportunities/js/add_notes.js"></script>';
	global $current_user;
	
	//$GLOBALS['log']->fatal(print_r($current_user->full_name, 1));
	$html = '<div id= "add_notes_div" class="col-xs-12 col-sm-12 detail-view-field " type="function" field="aop_case_updates_threaded">
<span id="aop_case_updates_threaded_span">
				
		<div id="add_notes_div_1">
			<div id="lessmargin" style="width: fit-content;">
				<div id="caseStyleUser">
					
					<span>Josh Administrator 07/11/2018 13:43</span>
					
					<br>
					<div id="caseUpdate29724432-f8fa-b342-e4c4-5b464fdbccad" class="caseUpdate" style="display: block;">
						sdcsdc
					</div>
				</div>
			</div>


		</div>
				
			<form id="case_updates" enctype="multipart/form-data">
					<div><label for="update_text">Update Text</label></div>
					<textarea id="update_text" name="update_text" cols="80" rows="4"></textarea>
					<div>
					<input id="user" type="hidden" name="user" id="user" value="'.$current_user->full_name.'">
					
				
				</div>
				
				<input type="button" value="Save" onclick="add_notes_logic()" title="Save" name="button"> 
		<br>
			</form>
	</span>
</div>' ;

$html .= "<script>
			

		</script>";

return $html;
	
	
    global $mod_strings;
}



