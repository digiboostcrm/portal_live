<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$links = array();
global $mod_strings;
$links['Administration']['configure_notification_link'] = array(
    $mod_strings['LBL_ADMIN_MODULE_NAME'],
    // title of the link 
    $mod_strings['LBL_NOTIFICATION_CONFIGURE_MODULE_LINK'],

    // description for the link
    $mod_strings['LBL_NOTIFICATION_CONFIGURE_MODULE_LINK_DESC'],

    // where to send the user when the link is clicked
    './index.php?module=bc_Notification&action=setNotificationModules',

);

$admin_group_header [] = array(

    // The title for the group of links
    $mod_strings['LBL_NOTIFICATION_TITLE'],

    '',

    false,

    $links,

    // a description for what this section is about
    $mod_strings['LBL_NOTIFICATION_DESC']

);

