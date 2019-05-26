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

$oContactBean = new Contact();
$oContactBean->retrieve($_REQUEST['record']);

$sErrorMessage = "";

if(empty($oContactBean->username_c)){
	$sErrorMessage = "Portal user not exists";
}

if(empty($sErrorMessage)){
	
	$oContactBean->enable_portal_c = 0;
	$oContactBean->save();
	
	SugarApplication::appendErrorMessage("Portal user disabled successfully");
	
}else{
    SugarApplication::appendErrorMessage($sErrorMessage);
}

SugarApplication::redirect("index.php?module=Contacts&action=DetailView&record=".$_REQUEST['record']);