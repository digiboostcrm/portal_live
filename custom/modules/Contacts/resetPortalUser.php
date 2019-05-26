<?php
/**
 *
 * @package Advanced OpenPortal
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author Salesagility Ltd <support@salesagility.com>
 */
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once 'modules/AOP_Case_Updates/util.php';
require_once('custom/Digiboost/UtilFunctions.php');

if(!isAOPEnabled()){
    return;
}
global $sugar_config, $mod_strings;

require_once('modules/Contacts/Contact.php');

$bean = new Contact();
$bean->retrieve($_REQUEST['record']);

if(!empty($bean->username_c) && $bean->enable_portal_c == 1){
    $sPassword = generatePortalUserRandomPassword();
	/*password convert into sh512*/
	$bean->password_c = hash('sha512', $sPassword);
	$bean->is_mail_sent = 1;
	$bean->save();
	
	require_once('custom/DigiboostMailer/DigiboostMailer.php');
	
	$oDigiMailer = new \DigiboostMailer();
	$sSubject = "Digiboost Portal - Password Reset"; 
	
	$sTemplate = 'portal/reset_password.txt';
	$aUserTemplateVars = array(
		'customer_name' => $bean->name,
		'user_name' => $bean->username_c,
		'password' => $sPassword
	);
	
	$oDigiMailer->sendEmail($sTemplate, $aUserTemplateVars, $sSubject, array(
		$bean->email1
	));
	
	SugarApplication::appendErrorMessage($mod_strings['LBL_RESET_PWD_SUCCESS']);
	
}else{
    SugarApplication::appendErrorMessage($mod_strings['LBL_USER_NOT_ENABLED']);
}

SugarApplication::redirect("index.php?module=Contacts&action=DetailView&record=".$_REQUEST['record']);
