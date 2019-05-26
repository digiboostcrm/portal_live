<?php
$popupMeta = array (
    'moduleMain' => 'bc_Notification',
    'varName' => 'bc_Notification',
    'orderBy' => 'bc_notification.name',
    'whereClauses' => array (
  'name' => 'bc_notification.name',
  'parent_type' => 'bc_notification.parent_type',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'parent_type',
),
    'searchdefs' => array (
  'name' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'name' => 'name',
  ),
  'parent_type' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PARENT_TYPE',
    'width' => '10%',
    'name' => 'parent_type',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'PARENT_TYPE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_PARENT_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'IS_NOTIFY' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_IS_NOTIFY',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'link' => true,
    'type' => 'relate',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'type' => 'datetime',
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'default' => true,
  ),
),
);
