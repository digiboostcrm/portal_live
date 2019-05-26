<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2017-09-13 15:42:10
$dictionary['Contact']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

// created: 2018-04-02 13:41:51
$dictionary["Contact"]["fields"]["contacts_accounts_1"] = array (
  'name' => 'contacts_accounts_1',
  'type' => 'link',
  'relationship' => 'contacts_accounts_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_CONTACTS_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
);


 // created: 2017-12-13 17:21:56
$dictionary['Contact']['fields']['enable_portal_c']['inline_edit']=1;
$dictionary['Contact']['fields']['enable_portal_c']['duplicate_merge_dom_value']=0;
$dictionary['Contact']['fields']['enable_portal_c']['labelValue']='Enable Portal';

 

$dictionary["Contact"]["fields"]["company_name"] =array (
	'name' => 'company_name',
	'default_value' => '',
	'studio' => true,
	'required' => true,
	'vname' => 'LBL_COMPANY_NAME',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 11,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



// created: 2015-06-16 11:43:23
$dictionary["Contact"]["fields"]["rls_scheduling_reports_contacts"] = array (
  'name' => 'rls_scheduling_reports_contacts',
  'type' => 'link',
  'relationship' => 'rls_scheduling_reports_contacts',
  'source' => 'non-db',
  'module' => 'RLS_Scheduling_Reports',
  'bean_name' => 'RLS_Scheduling_Reports',
  'vname' => 'LBL_RLS_SCHEDULING_REPORTS_CONTACTS_FROM_RLS_SCHEDULING_REPORTS_TITLE',
);



$dictionary["Contact"]["fields"]["user_answer"] =array (
	'name' => 'user_answer',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_USER_ANSWER',
	'type' => 'varchar',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);

 

 // created: 2017-12-13 17:21:56
$dictionary['Contact']['fields']['register_from_c']['inline_edit']=1;
$dictionary['Contact']['fields']['register_from_c']['duplicate_merge_dom_value']=0;

 

 // created: 2017-12-13 17:21:56
$dictionary['Contact']['fields']['password_c']['inline_edit']=1;
$dictionary['Contact']['fields']['password_c']['labelValue']='password';

 

$dictionary["Contact"]["fields"]["qbcreatedtime_c"]= array (
		'name' => 'qbcreatedtime_c',
		'vname' => 'LBL_QB_CREATED_TIME',
		'type' => 'datetime',
		'len' => '50',
		);

$dictionary["Contact"]["fields"]["qbmodifiedtime_c"]= array (
		'name' => 'qbmodifiedtime_c',
		'vname' => 'LBL_QB_MODIFIED_TIME',
		'type' => 'datetime',
		'len' => '50',
		);

$dictionary["Contact"]["fields"]["qbid_c"]= array (
		'name' => 'qbid_c',
		'vname' => 'LBL_QBID',
		'type' => 'varchar',
		'len' => '50',
		);




 // created: 2017-12-13 17:21:56
$dictionary['Contact']['fields']['username_c']['inline_edit']=1;
$dictionary['Contact']['fields']['username_c']['labelValue']='Username';

 


$dictionary['Contact']['fields']['contact_photo']['required']=false;
$dictionary['Contact']['fields']['contact_photo']['name']='contact_photo';
$dictionary['Contact']['fields']['contact_photo']['vname']='LBL_CONTACT_PHOTO';
$dictionary['Contact']['fields']['contact_photo']['type']='image'; 
$dictionary['Contact']['fields']['contact_photo']['massupdate']=false;
$dictionary['Contact']['fields']['contact_photo']['default']=NULL; 
$dictionary['Contact']['fields']['contact_photo']['no_default']=false;
$dictionary['Contact']['fields']['contact_photo']['comments']='';
$dictionary['Contact']['fields']['contact_photo']['help']=''; 
$dictionary['Contact']['fields']['contact_photo']['importable']='true'; 
$dictionary['Contact']['fields']['contact_photo']['duplicate_merge']='enabled';
$dictionary['Contact']['fields']['contact_photo']['duplicate_merge_dom_value']=1;
$dictionary['Contact']['fields']['contact_photo']['audited']=false;
$dictionary['Contact']['fields']['contact_photo']['reportable']=true; 
$dictionary['Contact']['fields']['contact_photo']['unified_search']=false;
$dictionary['Contact']['fields']['contact_photo']['merge_filter']='disabled';
$dictionary['Contact']['fields']['contact_photo']['calculated']=false;
$dictionary['Contact']['fields']['contact_photo']['len']=255;
$dictionary['Contact']['fields']['contact_photo']['size']='20';
$dictionary['Contact']['fields']['contact_photo']['dbType']='varchar';
$dictionary['Contact']['fields']['contact_photo']['width']='96';
$dictionary['Contact']['fields']['contact_photo']['height']='60';
$dictionary['Contact']['fields']['contact_photo']['border']=''; 




 // created: 2017-09-13 15:42:10
$dictionary['Contact']['fields']['jjwg_maps_lng_c']['inline_edit']=1;

 

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



$dictionary["Contact"]["fields"]["user_question"] =array (
	'name' => 'user_question',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_USER_QUESTION',
	'type' => 'enum',
	'options' => 'user_question_dom',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);

 

 // created: 2017-09-13 15:42:10
$dictionary['Contact']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

/**
 * The file used to store vardefs for relationship of User Group module and Contacts.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */ 
$dictionary["Contact"]["fields"]["bc_user_group_contacts"] = array (
  'name' => 'bc_user_group_contacts',
  'type' => 'link',
  'relationship' => 'bc_user_group_contacts',
  'source' => 'non-db',
  'vname' => 'LBL_BC_USER_GROUP_CONTACTS_FROM_BC_USER_GROUP_TITLE',
  'id_name' => 'bc_user_group_contactsbc_user_group_ida',
);
$dictionary["Contact"]["fields"]["bc_user_group_contacts_name"] = array (
  'name' => 'bc_user_group_contacts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_USER_GROUP_CONTACTS_FROM_BC_USER_GROUP_TITLE',
  'save' => true,
  'id_name' => 'bc_user_group_contactsbc_user_group_ida',
  'link' => 'bc_user_group_contacts',
  'table' => 'bc_user_group',
  'module' => 'bc_user_group',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["bc_user_group_contactsbc_user_group_ida"] = array (
  'name' => 'bc_user_group_contactsbc_user_group_ida',
  'type' => 'link',
  'relationship' => 'bc_user_group_contacts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_USER_GROUP_CONTACTS_FROM_CONTACTS_TITLE',
);


 // created: 2017-09-13 15:42:10
$dictionary['Contact']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 

/**
 * The file used to make email field as required
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
$dictionary['Contact']['fields']['email1']['required']=true;

?>