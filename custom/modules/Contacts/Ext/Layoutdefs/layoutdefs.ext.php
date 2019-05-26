<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2018-04-02 13:41:51
$layout_defs["Contacts"]["subpanel_setup"]['contacts_accounts_1'] = array (
  'order' => 100,
  'module' => 'Accounts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
  'get_subpanel_data' => 'contacts_accounts_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2015-06-16 11:43:23
$layout_defs["Contacts"]["subpanel_setup"]['rls_scheduling_reports_contacts'] = array (
  'order' => 100,
  'module' => 'RLS_Scheduling_Reports',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_RLS_SCHEDULING_REPORTS_CONTACTS_FROM_RLS_SCHEDULING_REPORTS_TITLE',
  'get_subpanel_data' => 'rls_scheduling_reports_contacts',
  'top_buttons' => 
  array (
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);

?>