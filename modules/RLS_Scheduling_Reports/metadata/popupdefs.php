<?php
$popupMeta = array (
    'moduleMain' => 'RLS_Scheduling_Reports',
    'varName' => 'RLS_Scheduling_Reports',
    'orderBy' => 'rls_scheduling_reports.name',
    'whereClauses' => array (
  'name' => 'rls_scheduling_reports.name',
  'last_run' => 'rls_scheduling_reports.last_run',
  'next_run' => 'rls_scheduling_reports.next_run',
  'subscribe_active' => 'rls_scheduling_reports.subscribe_active',
  'single_launch' => 'rls_scheduling_reports.single_launch',
  'periodicity' => 'rls_scheduling_reports.periodicity',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'last_run',
  5 => 'next_run',
  6 => 'subscribe_active',
  7 => 'single_launch',
  8 => 'periodicity',
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
  'last_run' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_LAST_RUN',
    'width' => '10%',
    'name' => 'last_run',
  ),
  'next_run' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_NEXT_RUN',
    'width' => '10%',
    'name' => 'next_run',
  ),
  'subscribe_active' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_SUBSCRIBE_ACTIVE',
    'width' => '10%',
    'name' => 'subscribe_active',
  ),
  'single_launch' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_SINGLE_LAUNCH',
    'width' => '10%',
    'name' => 'single_launch',
  ),
  'periodicity' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_PERIODICITY',
    'width' => '10%',
    'name' => 'periodicity',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '20%',
    'default' => true,
    'name' => 'name',
  ),
  'PERIODICITY' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_PERIODICITY',
    'width' => '20%',
    'name' => 'periodicity',
  ),
  'LAST_RUN' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_LAST_RUN',
    'width' => '20%',
    'default' => true,
    'name' => 'last_run',
  ),
  'NEXT_RUN' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_NEXT_RUN',
    'width' => '20%',
    'default' => true,
    'name' => 'next_run',
  ),
  'SUBSCRIBE_ACTIVE' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_SUBSCRIBE_ACTIVE',
    'width' => '20%',
  ),
),
);
