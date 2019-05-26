<?php
$dashletData['UT_RSActivitiesDashlet']['searchFields'] = array (
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
$dashletData['UT_RSActivitiesDashlet']['columns'] = array (
  'rs_summary' => 
  array (
    'width' => '60%',
    'label' => 'LBL_SUMMARY',
    'default' => true,
    'name' => 'name',
    'sortable' => false,
  ),
  /*
  'name' => 
  array (
    'width' => '35%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'ut_rightsignature_ut_rsactivities_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_UT_RIGHTSIGNATURE_UT_RSACTIVITIES_FROM_UT_RIGHTSIGNATURE_TITLE',
    'id' => 'UT_RIGHTSIGNATURE_UT_RSACTIVITIESUT_RIGHTSIGNATURE_IDA',
    'width' => '10%',
    'default' => true,
  ),
  */
  'date_entered' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
    'name' => 'date_entered',
  ),
  'assigned_user_name' => 
  array (
    'width' => '8%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'name' => 'assigned_user_name',
    'default' => false,
  ),
);
