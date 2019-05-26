<?php
$dashletData['UT_RightSignatureDashlet']['searchFields'] = array (
  'date_entered' => 
  array (
    'default' => '',
  ),
  'date_modified' => 
  array (
    'default' => '',
  ),
  'assigned_user_id' => 
  array (
    'type' => 'assigned_user_name',
    'default' => 'Administrator',
  ),
);
$dashletData['UT_RightSignatureDashlet']['columns'] = array (
  'document_name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
    'name' => 'document_name',
  ),
  'state' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATE',
    'width' => '10%',
    'default' => true,
  ),
  'original_file' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_ORIGINAL_FILE',
    'id' => 'NOTE_ID_C',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'exp_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DOC_EXP_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'date_modified' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
    'name' => 'date_entered',
  ),
);
