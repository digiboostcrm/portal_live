<?php
// created: 2018-08-29 21:52:21
$listViewDefs['Employees'] = array (
  'USER_HASH' => 
  array (
    'type' => 'varchar',
    'studio' => 
    array (
      'no_duplicate' => true,
      'listview' => false,
      'searchview' => false,
    ),
    'label' => 'LBL_USER_HASH',
    'width' => '10%',
    'default' => true,
  ),
  'NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'related_fields' => 
    array (
      0 => 'last_name',
      1 => 'first_name',
    ),
    'orderBy' => 'last_name',
    'default' => true,
  ),
  'USER_NAME' => 
  array (
    'type' => 'user_name',
    'studio' => 
    array (
      'no_duplicate' => true,
      'editview' => false,
      'detailview' => true,
      'quickcreate' => false,
      'basic_search' => false,
      'advanced_search' => false,
    ),
    'label' => 'LBL_USER_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'SYSTEM_GENERATED_PASSWORD' => 
  array (
    'type' => 'bool',
    'studio' => 
    array (
      'listview' => false,
      'searchview' => false,
      'editview' => false,
      'quickcreate' => false,
    ),
    'label' => 'LBL_SYSTEM_GENERATED_PASSWORD',
    'width' => '10%',
    'default' => true,
  ),
  'DEPARTMENT' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DEPARTMENT',
    'link' => true,
    'default' => true,
  ),
  'TITLE' => 
  array (
    'width' => '15%',
    'label' => 'LBL_TITLE',
    'link' => true,
    'default' => true,
  ),
  'REPORTS_TO_NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_REPORTS_TO_NAME',
    'link' => true,
    'sortable' => false,
    'default' => true,
  ),
  'EMAIL1' => 
  array (
    'width' => '15',
    'label' => 'LBL_LIST_EMAIL',
    'link' => true,
    'customCode' => '{$EMAIL1_LINK}',
    'default' => true,
    'sortable' => false,
  ),
  'PHONE_WORK' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_PHONE',
    'link' => true,
    'default' => true,
  ),
  'EMPLOYEE_STATUS' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_EMPLOYEE_STATUS',
    'link' => false,
    'default' => true,
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
  ),
);