<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * The file used to store matadata for dashlet view for User Group module. 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */ 

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/bc_user_group/bc_user_group.php');

class bc_user_groupDashlet extends DashletGeneric { 
    function bc_user_groupDashlet($id, $def = null) {
		global $current_user, $app_strings;
		require('modules/bc_user_group/metadata/dashletviewdefs.php');

        parent::DashletGeneric($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'bc_user_group');

        $this->searchFields = $dashletData['bc_user_groupDashlet']['searchFields'];
        $this->columns = $dashletData['bc_user_groupDashlet']['columns'];

        $this->seedBean = new bc_user_group();        
    }
}