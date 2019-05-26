<?php
$module_name = 'UT_Signers';
$listViewDefs [$module_name] = 
array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'RECIPIENT_EMAIL' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_RECIPIENT_EMAIL',
    'width' => '10%',
    'default' => true,
  ),
  'ROLE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ROLE',
    'width' => '10%',
    'default' => true,
  ),
  'PARENT_NAME' => 
  array (
    'type' => 'parent',
    'studio' => 'visible',
    'label' => 'LBL_RELATE_MODULE',
    'link' => true,
    'sortable' => false,
    'ACLTag' => 'PARENT',
    'dynamic_module' => 'PARENT_TYPE',
    'id' => 'PARENT_ID',
    'related_fields' => 
    array (
      0 => 'parent_id',
      1 => 'parent_type',
    ),
    'width' => '10%',
    'default' => true,
  ),
  'UT_SIGNERS_UT_RIGHTSIGNATURE_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_UT_SIGNERS_UT_RIGHTSIGNATURE_FROM_UT_RIGHTSIGNATURE_TITLE',
    'id' => 'UT_SIGNERS_UT_RIGHTSIGNATUREUT_RIGHTSIGNATURE_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
?>
