<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/


global $mod_strings, $app_strings, $sugar_config;
 
if(ACLController::checkAccess('UT_RSActivities', 'edit', true))$module_menu[]=Array("index.php?module=UT_RSActivities&action=EditView&return_module=UT_RSActivities&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"Add", 'UT_RSActivities');
if(ACLController::checkAccess('UT_RSActivities', 'list', true))$module_menu[]=Array("index.php?module=UT_RSActivities&action=index&return_module=UT_RSActivities&return_action=DetailView", $mod_strings['LNK_LIST'],"View", 'UT_RSActivities');
