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
if(!isAOPEnabled()){
    return;
}
global $sugar_config, $mod_strings;

require_once('modules/Contacts/Contact.php');
require_once('custom/DigiboostMailer/DigiboostMailer.php');
require_once('custom/Digiboost/UtilFunctions.php');

$oContactBean = new Contact();
$oContactBean->retrieve($_REQUEST['record']);
$oAccBean = BeanFactory::getBean('Accounts', $oContactBean->account_id);

$sErrorMessage = "";

if(!empty($oContactBean->username_c)){
	$sErrorMessage = "Portal user already created";
}

if(empty($oAccBean->account_number_c)){
	$sErrorMessage = "Account number seems empty";
}

if(empty($oContactBean->first_name)){
	$sErrorMessage = "First name seems empty";
}

if(empty($oContactBean->last_name)){
	$sErrorMessage = "Last name seems empty";
}

if(empty($sErrorMessage)){
    $sPassword = generatePortalUserRandomPassword();
	
	$sFormattedFirstName = preg_replace('/[^\w]/', '', $oContactBean->first_name);
	$sFormattedLastName = preg_replace('/[^\w]/', '', $oContactBean->last_name);
	
	/*password convert into sh512*/
	$oContactBean->username_c = substr(strtolower($sFormattedFirstName), 0, 1).strtolower($sFormattedLastName).$oAccBean->account_number_c;
	$oContactBean->password_c = hash('sha512', $sPassword);
	$oContactBean->enable_portal_c = 1;
	$oContactBean->save();
	
	$oDigiMailer = new \DigiboostMailer();
	$sSubject = "Digiboost Portal - Account created"; 
	
	$sTemplate = 'portal/create_user.txt';
	$aUserTemplateVars = array(
		'customer_name' => $oContactBean->name,
		'user_name' => $oContactBean->username_c,
		'password' => $sPassword
	);
	
	$oDigiMailer->sendEmail($sTemplate, $aUserTemplateVars, $sSubject, array(
		$oContactBean->email1
	));
	
	SugarApplication::appendErrorMessage("Portal user created and details sent successfully");
	
}else{
    SugarApplication::appendErrorMessage($sErrorMessage);
}

SugarApplication::redirect("index.php?module=Contacts&action=DetailView&record=".$_REQUEST['record']);