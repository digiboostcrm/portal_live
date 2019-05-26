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
 
if(ACLController::checkAccess('UT_Signers', 'edit', true))$module_menu[]=Array("index.php?module=UT_Signers&action=EditView&return_module=UT_Signers&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"Add", 'UT_Signers');
if(ACLController::checkAccess('UT_Signers', 'list', true))$module_menu[]=Array("index.php?module=UT_Signers&action=index&return_module=UT_Signers&return_action=DetailView", $mod_strings['LNK_LIST'],"View", 'UT_Signers');
