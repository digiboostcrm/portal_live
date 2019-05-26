<?php
$module_name='RLS_Scheduling_Reports';
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
      'popup_module' => 'RLS_Scheduling_Reports',
    ),
  ),
  'where' => '',
  'list_fields' => 
  array (
    'name' => 
    array (
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '20%',
      'default' => true,
    ),
    'periodicity' => 
    array (
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_PERIODICITY',
      'width' => '20%',
    ),
    'last_run' => 
    array (
      'type' => 'datetimecombo',
      'vname' => 'LBL_LAST_RUN',
      'width' => '20%',
      'default' => true,
    ),
    'next_run' => 
    array (
      'type' => 'datetimecombo',
      'vname' => 'LBL_NEXT_RUN',
      'width' => '20%',
      'default' => true,
    ),
    'subscribe_active' => 
    array (
      'type' => 'bool',
      'default' => true,
      'vname' => 'LBL_SUBSCRIBE_ACTIVE',
      'width' => '10%',
    ),
    'edit_button' => 
    array (
      'vname' => 'LBL_EDIT_BUTTON',
      'widget_class' => 'SubPanelEditButton',
      'module' => 'RLS_Scheduling_Reports',
      'width' => '4%',
      'default' => true,
    ),
    'remove_button' => 
    array (
      'vname' => 'LBL_REMOVE',
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'RLS_Scheduling_Reports',
      'width' => '5%',
      'default' => true,
    ),
  ),
);