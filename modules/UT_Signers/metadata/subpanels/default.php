<?php
$module_name='UT_Signers';
$subpanel_layout = array (
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'popup_module' => 'UT_Signers',
    ),
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '25%',
      'default' => true,
    ),
    'recipient_email' => 
    array (
      'type' => 'varchar',
      'vname' => 'LBL_RECIPIENT_EMAIL',
      'width' => '15%',
      'default' => true,
    ),
    'parent_type'=>array(
        'vname' => 'LBL_PARENT_NAME',
        'width' => '22%',
    ),
    'parent_name'=>array(
         'vname' => 'LBL_RELATE_MODULE',
         'width' => '22%',
         'target_record_key' => 'parent_id',
         'target_module_key'=>'parent_type',
         'widget_class' => 'SubPanelDetailViewLink',
         'sortable'=>false,
    ),
    'role' => 
    array (
      'type' => 'varchar',
      'vname' => 'LBL_ROLE',
      'width' => '15%',
      'default' => true,
    ),
    'date_modified' => 
    array (
      'vname' => 'LBL_DATE_MODIFIED',
      'width' => '45%',
      'default' => true,
    ),
    'parent_id'=>array(
        'usage'=>'query_only',
    ),
    'edit_button' => 
    array (
      'vname' => 'LBL_EDIT_BUTTON',
      'widget_class' => 'SubPanelEditButton',
      'module' => 'UT_Signers',
      'width' => '4%',
      'default' => true,
    ),
    'remove_button' => 
    array (
      'vname' => 'LBL_REMOVE',
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'UT_Signers',
      'width' => '5%',
      'default' => true,
    ),
  ),
);