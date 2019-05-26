<?php
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
