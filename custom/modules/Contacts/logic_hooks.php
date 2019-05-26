<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(1, 'Contacts push feed', 'modules/Contacts/SugarFeeds/ContactFeed.php','ContactFeed', 'pushFeed'); 
$hook_array['before_save'][] = Array(77, 'updateGeocodeInfo', 'modules/Contacts/ContactsJjwg_MapsLogicHook.php','ContactsJjwg_MapsLogicHook', 'updateGeocodeInfo'); 
$hook_array['before_save'][] = Array(200, 'create user in customer portal', 'custom/modules/Contacts/custom_Portallogic.php','custom_Portallogic', 'createWPUser'); 
$hook_array['before_save'][] = Array(198, 'save photo fields in contact', 'custom/modules/Contacts/custom_Portallogic.php','custom_Portallogic', 'uploadPhoto'); 
$hook_array['before_save'][] = Array(205, 'Sent Mail when new user created', 'custom/modules/Contacts/custom_Portallogic.php','custom_Portallogic', 'sendMailtoUser'); 
$hook_array['before_save'][] = Array(206, 'Save record data on db operation ', 'custom/include/ActivityLog.php','ActivityLog', 'save_activity_data'); 

$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(1, 'Update Portal', 'modules/Contacts/updatePortal.php','updatePortal', 'updateUser'); 
$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Contacts/ContactsJjwg_MapsLogicHook.php','ContactsJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 
$hook_array['after_ui_frame'] = Array(); 
$hook_array['after_ui_frame'][] = Array(200, 'Include javascript files for Survey', 'custom/modules/Contacts/custom_Portallogic.php','custom_Portallogic', 'includeJSFile'); 
$hook_array['before_delete'] = Array(); 
$hook_array['before_delete'][] = Array(100, 'Save record data on db operation ', 'custom/include/ActivityLog.php','ActivityLog', 'save_activity_data'); 


?>