<?php 
 //WARNING: The contents of this file are auto-generated


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


 // created: 2015-05-26 16:33:30
$layout_defs["RLS_Scheduling_Reports"]["subpanel_setup"]['rls_scheduling_reports_contacts'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_RLS_SCHEDULING_REPORTS_CONTACTS_FROM_CONTACTS_TITLE',
  'get_subpanel_data' => 'rls_scheduling_reports_contacts',
  'top_buttons' => 
  array (
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2015-05-26 16:33:30
$layout_defs["RLS_Scheduling_Reports"]["subpanel_setup"]['rls_scheduling_reports_rls_reports'] = array (
  'order' => 100,
  'module' => 'rls_Reports',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_RLS_SCHEDULING_REPORTS_RLS_REPORTS_FROM_RLS_REPORTS_TITLE',
  'get_subpanel_data' => 'rls_scheduling_reports_rls_reports',
  'top_buttons' => 
  array (
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);

?>