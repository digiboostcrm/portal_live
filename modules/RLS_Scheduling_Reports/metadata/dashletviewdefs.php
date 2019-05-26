<?php
$dashletData['RLS_Scheduling_ReportsDashlet']['searchFields'] = array (
  'name' => 
  array (
    'default' => '',
  ),
  'last_run' => 
  array (
    'default' => '',
  ),
  'next_run' => 
  array (
    'default' => '',
  ),
  'subscribe_active' => 
  array (
    'default' => '',
  ),
  'single_launch' => 
  array (
    'default' => '',
  ),
  'periodicity' => 
  array (
    'default' => '',
  ),
);
$dashletData['RLS_Scheduling_ReportsDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'periodicity' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_PERIODICITY',
    'width' => '20%',
  ),
  'last_run' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_LAST_RUN',
    'width' => '20%',
    'default' => true,
  ),
  'next_run' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_NEXT_RUN',
    'width' => '20%',
    'default' => true,
  ),
  'subscribe_active' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_SUBSCRIBE_ACTIVE',
    'width' => '20%',
  ),
  'date_modified' => 
  array (
    'width' => '15%',
    'label' => 'LBL_DATE_MODIFIED',
    'name' => 'date_modified',
    'default' => false,
  ),
  'created_by' => 
  array (
    'width' => '8%',
    'label' => 'LBL_CREATED',
    'name' => 'created_by',
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
