<?php
$module_name = 'qbs_QBLog';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'RESULT' => 
  array (
    'width' => '16%',
    'default' => true,
    'label' => 'LBL_RESULT',
  ),
  'FLOW' => 
  array (
    'width' => '32%',
    'default' => true,
    'label' => 'LBL_FLOW',
  ),
  'SUGARID' => 
  array (
    'width' => '32%',
    'default' => true,
    'label' => 'LBL_SUGARID',
  ),
  'QBID' => 
  array (
    'width' => '22%',
    'default' => true,
    'label' => 'LBL_QBID',
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
  'ACTION_LOG' => 
  array (
    'width' => '16%',
    'default' => true,
    'label' => 'LBL_ACTION_LOG',
  ),
  'DATE_MODIFIED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '24%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
;
?>
