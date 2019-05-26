<?php
// created: 2019-02-26 01:20:46
$subpanel_layout['list_fields'] = array (
  'account_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_ACCOUNT_NAME',
    'id' => 'ACCOUNT_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Accounts',
    'target_record_key' => 'account_id',
  ),
  'case_number' => 
  array (
    'vname' => 'LBL_LIST_NUMBER',
    'width' => '6%',
    'default' => true,
  ),
  'subject' => 
  array (
    'studio' => true,
    'type' => 'enum',
    'default' => true,
    'vname' => 'LBL_SUBJECT',
    'width' => '10%',
  ),
  'status' => 
  array (
    'vname' => 'LBL_LIST_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'priority' => 
  array (
    'vname' => 'LBL_LIST_PRIORITY',
    'width' => '10%',
    'default' => true,
  ),
  'date_entered' => 
  array (
    'vname' => 'LBL_LIST_DATE_CREATED',
    'width' => '15%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'widget_class' => 'SubPanelDetailViewLink',
    'target_record_key' => 'assigned_user_id',
    'target_module' => 'Employees',
    'vname' => 'LBL_LIST_ASSIGNED_TO_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'category' => 
  array (
    'studio' => true,
    'type' => 'enum',
    'vname' => 'LBL_CATEGORY',
    'width' => '10%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'Cases',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'Cases',
    'width' => '5%',
    'default' => true,
  ),
);