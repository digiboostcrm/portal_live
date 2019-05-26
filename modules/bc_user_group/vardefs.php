<?php

/**
 * The file used to store vardefs for User Group module.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
$dictionary['bc_user_group'] = array(
    'table' => 'bc_user_group',
    'audited' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'name' =>
        array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'name',
            'link' => true,
            'dbType' => 'varchar',
            'len' => '255',
            'unified_search' => false,
            'required' => true,
            'importable' => 'required',
            'duplicate_merge' => 'disabled',
            'merge_filter' => 'selected',
            'massupdate' => 0,
            'inline_edit' => FALSE,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'size' => '20',
        ),
        'accessible_modules' =>
        array(
            'required' => FALSE,
            'name' => 'accessible_modules',
            'vname' => 'LBL_ACCESSIBLE_MODULES',
            'type' => 'multienum',
            'massupdate' => 0,
            'default' => '^Accounts^,^Calls^,^Cases^,^AOS_Contracts^,^Documents^,^AOS_Invoices^,^Meetings^,^AOS_Quotes^,^AOK_KnowledgeBase^',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'inline_edit' => FALSE,
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'size' => '20',
            'function' => array('name' => 'get_modules', 'returns' => '', 'include' => 'custom/biz/function/default_portal_module.php'),
            'studio' => 'visible',
            'isMultiSelect' => true,
        ),
        'is_portal_accessible_group' =>
        array(
            'name' => 'is_portal_accessible_group',
            'label' => 'LBL_IS_PORTAL_ACCESSIBLE_GROUP',
            'type' => 'bool',
            'inline_edit' => FALSE,
            'default_value' => false, // true or false
            'audited' => false,
            'mass_update' => false,
            'duplicate_merge' => false,
            'reportable' => true,
            'importable' => 'true',
            'studio' => 'visible',
        ),
        'portal_accessiable_module' =>
        array(
            'name' => 'portal_accessiable_module',
            'label' => 'LBL_PORTAL_ACCESSIABLE_MODULE',
            'type' => 'text',
            'dbType' => 'text',
            'audited' => false,
            'inline_edit' => false ,
            'mass_update' => false,
            'duplicate_merge' => false,
    ),
    ),
    'relationships' => array(
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('bc_user_group', 'bc_user_group', array('basic', 'assignable'));
