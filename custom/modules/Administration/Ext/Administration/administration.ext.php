<?php 
 //WARNING: The contents of this file are auto-generated



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




/*
 * Created by Richlode Solutions
 * Author: Andrii Vasylchenko
 */

$admin_option_defs = array();
$admin_option_defs['Administration']['rls_Reports_configurator'] = array('Administration', 'LBL_MANAGE_REPORT_CONFIG', 'LBL_REPORT_CONFIG', './index.php?module=Configurator&action=rls_Reports_configurator');

$admin_group_header[] = array('LBL_REPORT_CONFIG_TITLE', '', false, $admin_option_defs, 'LBL_REPORT_CONFIG_DESC');



global $sugar_version;

$admin_option_defs=array();

if(preg_match( "/^6.*/", $sugar_version) ) {
    $admin_option_defs['Administration']['qbs_QBSugar_info']= array('helpInline','LBL_qbs_QBSugar_LICENSE_TITLE','LBL_qbs_QBSugar_LICENSE','./index.php?module=qbs_QBSugar&action=license');
} else {
    $admin_option_defs['Administration']['qbs_QBSugar_info']= array('helpInline','LBL_qbs_QBSugar_LICENSE_TITLE','LBL_qbs_QBSugar_LICENSE','javascript:parent.SUGAR.App.router.navigate("#bwc/index.php?module=qbs_QBSugar&action=license", {trigger: true});');
}

$admin_group_header[]= array('LBL_qbs_QBSugar','',false,$admin_option_defs, '');



$admin_option_defs=array();
$admin_option_defs['Administration']['rls_Reports_info']= array('helpInline','LBL_REPORTLICENSEADDON_LICENSE_TITLE','LBL_REPORTLICENSEADDON_LICENSE','./index.php?module=rls_Reports&action=license');

$admin_group_header[]= array('LBL_REPORTLICENSEADDON','',false,$admin_option_defs, '');




global $sugar_version;

$admin_option_defs=array();

if(preg_match( "/^6.*/", $sugar_version) ) {
    $admin_option_defs['Administration']['multiupload_info']= array('helpInline','LBL_MULTIUPLOAD_LICENSE_TITLE','LBL_MULTIUPLOAD_LICENSE','./index.php?module=Multiupload&action=license');
} else {
    $admin_option_defs['Administration']['multiupload_info']= array('helpInline','LBL_MULTIUPLOAD_LICENSE_TITLE','LBL_MULTIUPLOAD_LICENSE','javascript:parent.SUGAR.App.router.navigate("#bwc/index.php?module=Multiupload&action=license", {trigger: true});');
}

$admin_group_header[]= array('LBL_MULTIUPLOAD','',false,$admin_option_defs, '');


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


?>