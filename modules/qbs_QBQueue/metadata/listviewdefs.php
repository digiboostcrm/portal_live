<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 ********************************************************************************/


require_once('modules/qbs_QBSugar/license/OutfittersLicense.php');
        $validate_license = OutfittersLicense::isValid('qbs_QBSugar');
        if($validate_license !== true) {
            if(is_admin($current_user)) {
                SugarApplication::appendErrorMessage('qbs_QBQueue is no longer active due to the following reason: '.$validate_license.' Users will have limited to no access until the issue has been addressed.');
            }
            echo '<h2><p class="error">qbs_QBQueue is no longer active</p></h2><p class="error">Please renew your subscription or check your license configuration.</p>';
            die;
            //functionality may be altered here in response to the key failing to validate
        }


$module_name = 'qbs_QBQueue';
$listViewDefs[$module_name] = array(
	'NAME' => array(
		'width' => '16', 
		'label' => 'LBL_NAME', 
		'default' => true,
        'link' => true),         
	'qbqueuemode' => array('width' => '16', 'default' => true, 'label' => 'LBL_QBQUEUEMODE'),
        'source' => array('width' => '16', 'default' => 'true', 'label' => 'LBL_SOURCE'),
        'qborvtid' => array('width' => '32', 'default' => true, 'label' => 'LBL_QBORVTID'),
        'failcount' => array('width' => '16', 'default' => true, 'label' => 'LBL_FAILCOUNT'),
	'description' => array('width' => '32', 'default' => 'true', 'label' => 'LBL_DESCRIPTION'),
	'ASSIGNED_USER_NAME' => array(
		'width' => '16', 
		'label' => 'LBL_ASSIGNED_TO_NAME',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true),

);
?>

