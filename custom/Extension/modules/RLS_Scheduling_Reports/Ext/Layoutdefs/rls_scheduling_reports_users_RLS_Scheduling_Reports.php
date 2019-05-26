<?php
 // created: 2015-06-16 11:39:51
$layout_defs["RLS_Scheduling_Reports"]["subpanel_setup"]['rls_scheduling_reports_users'] = array (
  'order' => 100,
  'module' => 'Users',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_RLS_SCHEDULING_REPORTS_USERS_FROM_USERS_TITLE',
  'get_subpanel_data' => 'rls_scheduling_reports_users',
  'top_buttons' => 
  array (
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);
