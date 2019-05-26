<?php
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

$dictionary['UT_RSActivities'] = array(
	'table'=>'ut_rsactivities',
	'audited'=>true,
    'inline_edit'=>true,
		'duplicate_merge'=>true,
		'fields'=>array (
            'rs_keyword' => 
              array (
                'required' => false,
                'name' => 'rs_keyword',
                'vname' => 'LBL_KEYWORD',
                'type' => 'enum',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => '',
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => 100,
                'size' => 20,
                'options' => 'rs_activity_kewords',
              ),
              'recipient_name'=>
                array(
                    'name'=>'recipient_name',
                    'vname'=> 'LBL_RECIPIENT_NAME',
                    'type'=>'name',
                    'link' => false, // bug 39288 
                    'dbType' => 'varchar',
                    'len'=>255,
                    'unified_search' => true,
                    'full_text_search' => array('boost' => 3),
                    'required'=>false,
                    'importable' => 'false',
                    'duplicate_merge' => 'enabled',
                    'merge_filter' => 'selected',
                ),
              'recipient_email'=>
                array(
                    'name'=>'recipient_email',
                    'vname'=> 'LBL_RECIPIENT_EMAIL',
                    'type'=>'name',
                    'link' => false, // bug 39288 
                    'dbType' => 'varchar',
                    'len'=>255,
                    'unified_search' => true,
                    'full_text_search' => array('boost' => 3),
                    'required'=>false,
                    'importable' => 'false',
                    'duplicate_merge' => 'enabled',
                    'merge_filter' => 'selected',
                ),
              'rs_summary' => 
              array (
                'required' => false,
                'name' => 'rs_summary',
                'vname' => 'LBL_SUMMARY',
                'type' => 'varchar',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => '',
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'len' => 5,
                'size' => 5
              ),
              'rs_created_at' => 
              array (
                'required' => false,
                'name' => 'rs_created_at',
                'vname' => 'LBL_RS_CREATED_AT',
                'type' => 'datetimecombo',
                'massupdate' => 0,
                'no_default' => false,
                'comments' => '',
                'help' => '',
                'importable' => 'true',
                'duplicate_merge' => 'disabled',
                'duplicate_merge_dom_value' => '0',
                'audited' => false,
                'inline_edit' => '',
                'reportable' => true,
                'unified_search' => false,
                'merge_filter' => 'disabled',
                'size' => '20',
                'enable_range_search' => false,
                'dbType' => 'datetime',
              ),
              'ut_rightsignature_ut_rsactivities' => 
              array (
                  'name' => 'ut_rightsignature_ut_rsactivities',
                  'type' => 'link',
                  'relationship' => 'ut_rightsignature_ut_rsactivities',
                  'source' => 'non-db',
                  'module' => 'UT_RightSignature',
                  'bean_name' => 'UT_RightSignature',
                  'vname' => 'LBL_UT_RIGHTSIGNATURE_UT_RSACTIVITIES_FROM_UT_RIGHTSIGNATURE_TITLE',
                  'id_name' => 'ut_rightsignature_ut_rsactivitiesut_rightsignature_ida',
              ),
              'ut_rightsignature_ut_rsactivities_name' => 
              array (
                  'name' => 'ut_rightsignature_ut_rsactivities_name',
                  'type' => 'relate',
                  'source' => 'non-db',
                  'vname' => 'LBL_UT_RIGHTSIGNATURE_UT_RSACTIVITIES_FROM_UT_RIGHTSIGNATURE_TITLE',
                  'save' => true,
                  'id_name' => 'ut_rightsignature_ut_rsactivitiesut_rightsignature_ida',
                  'link' => 'ut_rightsignature_ut_rsactivities',
                  'table' => 'ut_rightsignature',
                  'module' => 'UT_RightSignature',
                  'rname' => 'document_name',
              ),
              'ut_rightsignature_ut_rsactivitiesut_rightsignature_ida' => 
              array (
                'name' => 'ut_rightsignature_ut_rsactivitiesut_rightsignature_ida',
                'type' => 'link',
                'relationship' => 'ut_rightsignature_ut_rsactivities',
                'source' => 'non-db',
                'reportable' => false,
                'side' => 'right',
                'vname' => 'LBL_UT_RIGHTSIGNATURE_UT_RSACTIVITIES_FROM_UT_RSACTIVITIES_TITLE',
              ),
),
	'relationships'=>array (
),
	'optimistic_locking'=>true,
		'unified_search'=>true,
	);
if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('UT_RSActivities','UT_RSActivities', array('basic','assignable','security_groups'));