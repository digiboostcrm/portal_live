<?php
$module_name = 'RLS_Scheduling_Reports';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'PERIODICITY' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_PERIODICITY',
    'width' => '20%',
  ),
  'LAST_RUN' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_LAST_RUN',
    'width' => '20%',
    'default' => true,
  ),
  'NEXT_RUN' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_NEXT_RUN',
    'width' => '20%',
    'default' => true,
  ),
  'SUBSCRIBE_ACTIVE' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_SUBSCRIBE_ACTIVE',
    'width' => '20%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
?>
