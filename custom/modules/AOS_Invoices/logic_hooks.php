<?php

$hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['after_save'] = Array(); 
$hook_array['after_save'][] = Array(81, 'Sent Email After changing renewel date', 'custom/modules/AOS_Invoices/SentEmail_LogicHook.php','SentEmail_LogicHook', 'sent_email'); 
//$hook_array['after_save'][] = Array(77, 'updateRelatedMeetingsGeocodeInfo', 'modules/Accounts/AccountsJjwg_MapsLogicHook.php','AccountsJjwg_MapsLogicHook', 'updateRelatedMeetingsGeocodeInfo'); 




?>