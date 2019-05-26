<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['after_login'] = Array(); 
$hook_array['after_login'][] = Array(1, 'SugarFeed old feed entry remover', 'modules/SugarFeed/SugarFeedFlush.php','SugarFeedFlush', 'flushStaleEntries'); 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(2, 'user assign to role', 'custom/modules/Users/roles.php','Rolesassign', 'custom_user_type'); 

$hook_array['before_relationship_delete'] = Array();
    $hook_array['before_relationship_delete'][] = Array(

	//Processing index. For sorting the array.
	1, 

	//Label. A string value to identify the hook.
	'after_delete example', 

	//The PHP file where your class is located.
	'custom/modules/Users/roles.php', 

	//The class the method is in.
	'Rolesassign', 

	//The method to call.
	'delete_role' 
);


?>