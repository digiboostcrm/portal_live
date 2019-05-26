<?php
$module_name = 'UT_RightSignature';
$OBJECT_NAME = 'UT_RIGHTSIGNATURE';
$listViewDefs [$module_name] = 
array (
  'NUMBER' => 
  array (
    'studio' => 'visible',
    'label' => 'LBL_RS_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'DOCUMENT_NAME' => 
  array (
    'width' => '40%',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
  ),
  'PARENT_NAME' => 
  array (
    'type' => 'parent',
    'studio' => 'visible',
    'label' => 'LBL_FLEX_RELATE',
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
  'RECIPIENT_NAME' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_RECIPIENT_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'EMAIL_ADDRESS' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_EMAIL_ADDRESS',
    'width' => '10%',
    'default' => true,
  ),
  'STATE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATE',
    'width' => '10%',
    'default' => true,
  ),
  'SENDING_TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_SENDING_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'RIGHTSIGNATURE_TEMPLATES' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_RIGHTSIGNATURE_TEMPLATES',
    'width' => '10%',
    'default' => true,
  ),
  'ACTIVE_DATE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_ACTIVE_DATE',
    'default' => true,
  ),
  'RS_EXPIRES_ON' => 
  array (
    'width' => '10%',
    'label' => 'LBL_RS_EXPIRES_ON',
    'default' => true,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MODIFIED_USER',
    'module' => 'Users',
    'id' => 'USERS_ID',
    'default' => false,
    'sortable' => false,
    'related_fields' => 
    array (
      0 => 'modified_user_id',
    ),
  ),
  'UPLOADFILE' => 
  array (
    'type' => 'file',
    'label' => 'LBL_FILE_UPLOAD',
    'width' => '10%',
    'default' => false,
  ),
  'SUBCATEGORY_ID' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_SUBCATEGORY',
    'default' => false,
  ),
  'CATEGORY_ID' => 
  array (
    'width' => '40%',
    'label' => 'LBL_LIST_CATEGORY',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_LAST_REV_CREATOR',
    'default' => false,
    'sortable' => false,
  ),
);
?>
