<?php

$hook_version = 1;
$hook_array = Array();

$hook_array['before_save'] = Array();
$hook_array['before_save'][] = Array(1, 'ser Assigned UserID', 'custom/modules/setupCNLogic.php', 'setupCNLogic', 'setAssignedUserId');

$hook_array['after_save'] = Array();
$hook_array['after_save'][] = Array(1, 'Set entry in queueNotification table', 'custom/modules/setupCNLogic.php', 'setupCNLogic', 'queueNotification');


?>