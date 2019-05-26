<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/**
 * The file used for adding admin panel section for the Customer Portal
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
global $mod_strings;
$portal_admin_links['Administration']['portal_licence_link'] = array(
    $mod_strings['LBL_MODULE_NAME'],
    // title of the link 
    $mod_strings['LBL_PORTAL_CONF_LINK'],
    // description for the link
    $mod_strings['LBL_PORTAL_CONF_LINK_DESC'],
    // where to send the user when the link is clicked
    './index.php?module=Administration&action=license',
);
$portal_admin_links['Administration']['configure_portal_link'] = array(
    $mod_strings['LBL_ADMIN_MODULE_NAME'],
    // title of the link
    $mod_strings['LBL_PORTAL_CONFIGURE_MODULE_LINK'],
    // description for the link
    $mod_strings['LBL_PORTAL_CONFIGURE_MODULE_LINK_DESC'],
    // where to send the user when the link is clicked
    './index.php?module=Administration&action=setportallayout',
);
$portal_admin_links['Administration']['user_group'] = array(
    'Users',
    // title of the link
    $mod_strings['LBL_WP_USER_GROUP'],
    // description for the link
    $mod_strings['LBL_WP_USER_GROUP_DESC'],
    // where to send the user when the link is clicked
    './index.php?module=bc_user_group&action=ListView',
);
$portal_admin_links['Administration']['portalconvertedcontactsexport'] = array(
    $mod_strings['LBL_ADMIN_MODULE_NAME'],
    // title of the link
    $mod_strings['LBL_EXPORT_PORTAL_CONTACTS_CSV'],
    // description for the link
    $mod_strings['LBL_EXPORT_PORTAL_CONTACTS'],
    // where to send the user when the link is clicked
    './index.php?module=Administration&action=portalconvertedcontactsexport',
);
$portal_admin_links['Administration']['generalconfiguration'] = array(
        //Icon name. Available icons are located in ./themes/default/images
        'Administration',
        
        //Link name label 
        'LBL_LINK_NAME_GENERAL_CONFIG',
        
        //Link description label
        'LBL_LINK_DESCRIPTION_GENERAL_CONFIG',
        
        //Link URL
        './index.php?module=Administration&action=generalconfiguration',
);
//$portal_admin_links['Administration']['emailtemplateconfig'] = array(
//        //Icon name. Available icons are located in ./themes/default/images
//        'Administration',
//        
//        //Link name label 
//        'LBL_EMAIL_TEMPLATE_CONFIG',
//        
//        //Link description label
//        'LBL_Email_TEMPLATE_DESCRIPTION_CONFIG',
//        
//        //Link URL
//        './index.php?module=Administration&action=emailtemplateconfig',
//);
$admin_group_header [] = array(
    // The title for the group of links
    $mod_strings['LBL_PORTAL_CONF_TITLE'],
    '',
    false,
    $portal_admin_links,
    // a description for what this section is about
    $mod_strings['LBL_PORTAL_CONF_DESC']
);

