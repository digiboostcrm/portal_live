<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-06-16 11:39:51
$dictionary["User"]["fields"]["rls_scheduling_reports_users"] = array (
  'name' => 'rls_scheduling_reports_users',
  'type' => 'link',
  'relationship' => 'rls_scheduling_reports_users',
  'source' => 'non-db',
  'module' => 'RLS_Scheduling_Reports',
  'bean_name' => 'RLS_Scheduling_Reports',
  'vname' => 'LBL_RLS_SCHEDULING_REPORTS_USERS_FROM_RLS_SCHEDULING_REPORTS_TITLE',
);


$dictionary['User']['fields']['custom_user_type'] = array(
	'name' => 'custom_user_type',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_USER_TYPE',
	'type' => 'varchar',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,
);

?>