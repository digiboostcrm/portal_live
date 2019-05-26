<?php 
 //WARNING: The contents of this file are auto-generated


$dictionary["Account"]["fields"]["bank_saving"] =array (
	'name' => 'bank_saving',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_BANK_SAVING',
	'default_value' => '',
	'type' => 'bool',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2017-11-15 14:50:32
$dictionary['Account']['fields']['facebook_password_c']['inline_edit']='1';
$dictionary['Account']['fields']['facebook_password_c']['labelValue']='Facebook Password';

 

 // created: 2017-11-15 14:56:08
$dictionary['Account']['fields']['linkedin_username_c']['inline_edit']='1';
$dictionary['Account']['fields']['linkedin_username_c']['labelValue']='Linkedin Username';

 

$dictionary["Account"]["fields"]["credit_card_expire"] =array (
	'name' => 'credit_card_expire',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREDIT_CARD_EXPIRE',
	'type' => 'varchar',
	'massupdate' => 0,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



$dictionary["Account"]["fields"]["bank_acc_number"] =array (
	'name' => 'bank_acc_number',
	'default_value' => '',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_BANK_ACC_NUMBER',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 20,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2017-09-13 15:42:09
$dictionary['Account']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

$dictionary["Account"]["fields"]["bank_acc_title"] =array (
	'name' => 'bank_acc_title',
	'default_value' => '',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_BANK_ACC_TITLE',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 255,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);




$dictionary["Account"]["fields"]["credit_card_name"] =array (
	'name' => 'credit_card_name',
	//'studio' => true,
	'required' => false,
	'inline_edit' => true,
	'vname' => 'LBL_CREDIT_CARD_NAME',
	'type' => 'varchar',
	'massupdate' => 0,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);




 // created: 2017-12-21 22:33:27
$dictionary['Account']['fields']['account_type']['len']=100;
$dictionary['Account']['fields']['account_type']['inline_edit']=true;
$dictionary['Account']['fields']['account_type']['comments']='The Company is of this type';
$dictionary['Account']['fields']['account_type']['merge_filter']='disabled';

 

 // created: 2017-11-15 14:49:46
$dictionary['Account']['fields']['facebook_username_c']['inline_edit']='1';
$dictionary['Account']['fields']['facebook_username_c']['labelValue']='Facebook Username';

 

 // created: 2017-11-15 14:50:50
$dictionary['Account']['fields']['twitter_username_c']['inline_edit']='1';
$dictionary['Account']['fields']['twitter_username_c']['labelValue']='Twitter Username';

 

 // created: 2017-11-15 14:51:39
$dictionary['Account']['fields']['instagram_username_c']['inline_edit']='1';
$dictionary['Account']['fields']['instagram_username_c']['labelValue']='Instagram Username';

 

 // created: 2017-11-15 14:56:30
$dictionary['Account']['fields']['linkedin_password_c']['inline_edit']='1';
$dictionary['Account']['fields']['linkedin_password_c']['labelValue']='Linkedin Password';

 

 // created: 2017-11-15 14:51:10
$dictionary['Account']['fields']['twitter_password_c']['inline_edit']='1';
$dictionary['Account']['fields']['twitter_password_c']['labelValue']='Twitter Password';

 

// created: 2018-04-02 13:41:51
$dictionary["Account"]["fields"]["contacts_accounts_1"] = array (
  'name' => 'contacts_accounts_1',
  'type' => 'link',
  'relationship' => 'contacts_accounts_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_CONTACTS_ACCOUNTS_1_FROM_CONTACTS_TITLE',
);


$dictionary["Account"]["fields"]["ccv_number"] =array (
	'name' => 'ccv_number',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREDIT_CARD_CCV',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 3,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2018-01-30 18:17:33
$dictionary['Account']['fields']['billing_day_c']['inline_edit']='1';
$dictionary['Account']['fields']['billing_day_c']['labelValue']='Billing Day';

 

 // created: 2019-01-03 21:40:43
$dictionary['Account']['fields']['account_manager_c']['inline_edit']='1';
$dictionary['Account']['fields']['account_manager_c']['labelValue']='Account Manager';

 

$dictionary["Account"]["fields"]["bank_name"] =array (
	'name' => 'bank_name',
	'default_value' => '',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_BANK_NAME',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 255,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



$dictionary["Account"]["fields"]["credit_card_amex"] =array (
	'name' => 'credit_card_amex',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREDIT_CARD_AMEX',
	'type' => 'bool',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



$dictionary["Account"]["fields"]["pinterest_number"] =array (
	'name' => 'pinterest_number',
	'default_value' => '',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_PINTREST_NUMBER',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 11,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2018-01-12 22:45:49
$dictionary['Account']['fields']['industry']['len']=100;
$dictionary['Account']['fields']['industry']['inline_edit']=true;
$dictionary['Account']['fields']['industry']['comments']='The company belongs in this industry';
$dictionary['Account']['fields']['industry']['merge_filter']='disabled';

 

$dictionary["Account"]["fields"]["billing_frequecy"] =array (
	'name' => 'billing_frequecy',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_BILLING_FREQUECY',
	'type' => 'enum',
	'options' => 'billing_frequency_dom',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);

 

 // created: 2017-12-13 17:21:53
$dictionary['Account']['fields']['created_from_c']['inline_edit']=1;
$dictionary['Account']['fields']['created_from_c']['duplicate_merge_dom_value']=0;

 

$dictionary["Account"]["fields"]["active"] =array (
	'name' => 'active',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_STATUS',
	'type' => 'bool',
	'default' => '1',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2017-12-09 01:37:02
$dictionary['Account']['fields']['content_files_c']['inline_edit']='1';
$dictionary['Account']['fields']['content_files_c']['labelValue']='Content Files';

 

$dictionary["Account"]["fields"]["bank_checking"] =array (
	'name' => 'bank_checking',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_BANK_CHECKING',
	'default_value' => '',
	'type' => 'bool',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2017-11-15 14:52:28
$dictionary['Account']['fields']['instagram_password_c']['inline_edit']='1';
$dictionary['Account']['fields']['instagram_password_c']['labelValue']='Instagram Password';

 

 // created: 2018-09-26 15:48:08
$dictionary['Account']['fields']['account_status_c']['inline_edit']='1';
$dictionary['Account']['fields']['account_status_c']['labelValue']='Account Status';

 

 // created: 2018-09-26 20:07:02
$dictionary['Account']['fields']['cardholders_name_c']['inline_edit']='1';
$dictionary['Account']['fields']['cardholders_name_c']['labelValue']='Card Holders Name';

 

$dictionary["Account"]["fields"]["credit_card_number"] =array (
	'name' => 'credit_card_number',
	'default_value' => '',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREDIT_CARD_NUMBER',
	'type' => 'varchar',
	'max_size' => 255,
	'massupdate' => 0,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



$dictionary["Account"]["fields"]["bank_city"] =array (
	'name' => 'bank_city',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_BANK_CITY',
	'type' => 'varchar',
	'default_value' => '',
	'massupdate' => 0,
	'max_size' => 255,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2017-10-25 07:42:09
$dictionary['Account']['fields']['jjwg_maps_lng_c']['inline_edit']='1';
$dictionary['Account']['fields']['jjwg_maps_lng_c']['labelValue']='Longitude';

 

$dictionary["Account"]["fields"]["marketing_project_domain"] =array (
	'name' => 'marketing_project_domain',
	'default_value' => '',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_MARKETING_PROJECT',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 255,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);




$dictionary["Account"]["fields"]["auto_account_number"] =array (
'required' => false,
'name' => 'auto_account_number',
'vname' => 'LBL_ACCOUNT_NUMBER',
'type' => 'int',
'massupdate' => 0,
'studio' => true,
'importable' => true,
'duplicate_merge' => 'disabled',
'duplicate_merge_dom_value' => 0,
'audited' => false,
'reportable' => true,
'calculated' => false,
'auto_increment'=>true,
);

$dictionary["Account"]["indices"]["auto_account_number"] = array(
'name' => 'auto_account_number',
'type' => 'unique',
'fields' => array(
'auto_account_number'
),
);





$dictionary["Account"]["fields"]["credit_card_discover"] =array (
	'name' => 'credit_card_discover',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREDIT_CARD_DISCOVER',
	'type' => 'bool',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);




$dictionary["Account"]["fields"]["on_boarding"] =array (
	'name' => 'on_boarding',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_ON_BOARDING',
	'type' => 'bool',
	'default_value' => 0,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



// created: 2018-01-09 18:46:01
$dictionary["Account"]["fields"]["accounts_accounts_1"] = array (
  'name' => 'accounts_accounts_1',
  'type' => 'link',
  'relationship' => 'accounts_accounts_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_ACCOUNTS_ACCOUNTS_1_FROM_ACCOUNTS_L_TITLE',
);
$dictionary["Account"]["fields"]["accounts_accounts_1"] = array (
  'name' => 'accounts_accounts_1',
  'type' => 'link',
  'relationship' => 'accounts_accounts_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'vname' => 'LBL_ACCOUNTS_ACCOUNTS_1_FROM_ACCOUNTS_R_TITLE',
);


$dictionary["Account"]["fields"]["credit_card_master"] =array (
	'name' => 'credit_card_master',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREDIT_CARD_MASTER',
	'type' => 'bool',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2018-10-11 17:48:55
$dictionary['Account']['fields']['account_number_c']['inline_edit']='1';
$dictionary['Account']['fields']['account_number_c']['labelValue']='Account Number';

 

 // created: 2017-09-13 15:42:10
$dictionary['Account']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

$dictionary["Account"]["fields"]["bank_routing"] =array (
	'name' => 'bank_routing',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_BANK_ROUTING',
	'type' => 'int',
	'default_value' => '',
	'massupdate' => 0,
	'max_size' => 11,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2018-01-30 18:30:50
$dictionary['Account']['fields']['name_on_bank_acct_c']['inline_edit']='1';
$dictionary['Account']['fields']['name_on_bank_acct_c']['labelValue']='Name on bank account';

 

$dictionary["Account"]["fields"]["pinterest_password"] =array (
	'name' => 'pinterest_password',
	'default_value' => '',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_PINTREST_PASSWORD',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 11,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2017-09-13 15:42:10
$dictionary['Account']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 

$dictionary["Account"]["fields"]["credit_card_visa"] =array (
	'name' => 'credit_card_visa',
	//'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREDIT_CARD_VISA',
	'type' => 'bool',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);


?>