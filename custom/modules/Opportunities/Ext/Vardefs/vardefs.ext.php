<?php 
 //WARNING: The contents of this file are auto-generated


$dictionary["Opportunity"]["fields"]["lead_type"] = array (
	'name' => 'lead_type',
	'type' => 'enum',
	'vname' => 'LBL_LEAD_TYPE',
	'options' => 'opportunity_type_dom',
	'studio' => true,
	'required' => false,
);


$dictionary["Opportunity"]["fields"]["sdr"] =array (
	'name' => 'sdr',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_SDR',
	'type' => 'enum',
	'options' => 'sales_sdr_list',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);

 

// created: 2017-06-22 13:57:06
$dictionary["Opportunity"]["fields"]["custom_lead_id"] = array (
	'name' => 'custom_lead_id',
	'type' => 'varchar',
	'source' => 'non-db',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_LEAD_ID',
);


// created: 2017-11-14 16:53:31
$dictionary["Opportunity"]["fields"]["opportunities_aos_product_categories_1"] = array (
  'name' => 'opportunities_aos_product_categories_1',
  'type' => 'link',
  'relationship' => 'opportunities_aos_product_categories_1',
  'source' => 'non-db',
  'module' => 'AOS_Product_Categories',
  'bean_name' => 'AOS_Product_Categories',
  'vname' => 'LBL_OPPORTUNITIES_AOS_PRODUCT_CATEGORIES_1_FROM_AOS_PRODUCT_CATEGORIES_TITLE',
);


 // created: 2018-01-09 17:29:47
$dictionary['Opportunity']['fields']['hosting_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['hosting_c']['labelValue']='Hosting';

 

 // created: 2017-09-13 15:42:11
$dictionary['Opportunity']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 


$dictionary["Opportunity"]["fields"]["add_notes"] = array (
	'name' => 'add_notes',
	'type' => 'text',
	'vname' => 'LBL_ADD_NOTES', 
	'studio' => true,
	'required' => false,
);


 // created: 2018-03-03 19:36:16
$dictionary['Opportunity']['fields']['contact_id_c']['inline_edit']=1;

 

 // created: 2017-11-13 13:24:06
$dictionary['Opportunity']['fields']['social_engagement_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['social_engagement_c']['labelValue']='Social Engagement Price:';

 

 // created: 2017-11-13 10:28:54
$dictionary['Opportunity']['fields']['website_design_description_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['website_design_description_c']['labelValue']='Website Design Description:';

 

 // created: 2017-11-13 09:55:08
$dictionary['Opportunity']['fields']['website_design_amount_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['website_design_amount_c']['labelValue']='Website Design Amount:';

 

 // created: 2019-05-18 11:13:16
$dictionary['Opportunity']['fields']['user_id_c']['inline_edit']=1;

 

 // created: 2018-01-26 16:07:28
$dictionary['Opportunity']['fields']['number_post_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['number_post_c']['labelValue']='Number of Posts';

 

$dictionary["Opportunity"]["fields"]["rowData"] = array (
	'name' => 'rowData',
	'type' => 'text',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_ROW_DATA',
	'default_value' => '',
);


 // created: 2017-12-21 22:35:45
$dictionary['Opportunity']['fields']['account_manager_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['account_manager_c']['labelValue']='Account Manager';

 

 // created: 2018-01-15 18:56:22
$dictionary['Opportunity']['fields']['opportunity_type']['len']=100;
$dictionary['Opportunity']['fields']['opportunity_type']['required']=true;
$dictionary['Opportunity']['fields']['opportunity_type']['inline_edit']=true;
$dictionary['Opportunity']['fields']['opportunity_type']['comments']='Type of opportunity (ex: Existing, New)';
$dictionary['Opportunity']['fields']['opportunity_type']['merge_filter']='disabled';

 

 // created: 2017-11-13 10:07:56
$dictionary['Opportunity']['fields']['online_date_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['online_date_c']['labelValue']='Online Date:';

 

 // created: 2018-01-10 15:52:51
$dictionary['Opportunity']['fields']['hosting_mrr_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['hosting_mrr_c']['labelValue']='Hosting';

 

// created: 2018-07-18 09:56:51
$dictionary["Opportunity"]["fields"]["opportunities_notes_1"] = array (
  'name' => 'opportunities_notes_1',
  'type' => 'link',
  'relationship' => 'opportunities_notes_1',
  'source' => 'non-db',
  'module' => 'Notes',
  'bean_name' => 'Note',
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_NOTES_1_FROM_NOTES_TITLE',
);


// created: 2017-06-22 13:57:06
$dictionary["Opportunity"]["fields"]["account_panel_show"] = array (
	'name' => 'account_panel_show',
	'type' => 'bool',
	'source' => 'non-db',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_ACCOUNT_SHOW',
);


// created: 2017-06-22 13:57:06
$dictionary["Opportunity"]["fields"]["account_email"] = array (
	'name' => 'account_email',
	'type' => 'varchar',
	'source' => 'non-db',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_ACCOUNT_EMAIL',
	'default_value' => '',
);


$dictionary['Opportunity']['fields']['se_manager'] = array(

      'required' => false,
      'name' => 'se_manager',
      'vname' => 'LBL_AOP_CASE_UPDATES_THREADED',
      'type' => 'function',
      'source' => 'non-db',
      'massupdate' => 0,
      'studio' => 'visible',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => false,
      'reportable' => false,
      'inline_edit' => 0,
      'function' => 
      array (
        'name' => 'custom_notes',
        'returns' => 'html',
        'include' => 'custom/modules/Opportunities/Case_Updates.php',
      ),

);


 // created: 2018-01-26 16:04:45
$dictionary['Opportunity']['fields']['ad_management_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['ad_management_c']['labelValue']='Ad Management';

 

 // created: 2019-05-23 13:28:56
$dictionary['Opportunity']['fields']['lead_source']['len']=100;
$dictionary['Opportunity']['fields']['lead_source']['inline_edit']=true;
$dictionary['Opportunity']['fields']['lead_source']['options']='lead_source_0';
$dictionary['Opportunity']['fields']['lead_source']['comments']='Source of the opportunity';
$dictionary['Opportunity']['fields']['lead_source']['merge_filter']='disabled';

 

 // created: 2018-01-10 15:52:02
$dictionary['Opportunity']['fields']['email_mrr_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['email_mrr_c']['labelValue']='Email';

 

// created: 2017-06-22 13:57:06
$dictionary["Opportunity"]["fields"]["account_phone"] = array (
	'name' => 'account_phone',
	'type' => 'int',
	'source' => 'non-db',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_ACCOUNT_PHONE',
	'default_value' => '',
);


 // created: 2018-01-26 16:07:05
$dictionary['Opportunity']['fields']['number_media_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['number_media_c']['labelValue']='Number of Social Accounts';

 

 // created: 2018-01-26 16:08:06
$dictionary['Opportunity']['fields']['webiste_maintenace_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['webiste_maintenace_c']['labelValue']='Website Maintenance';

 

 // created: 2017-11-13 10:30:37
$dictionary['Opportunity']['fields']['free_time_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['free_time_c']['labelValue']='Free Time:';

 

 // created: 2017-12-26 23:53:56
$dictionary['Opportunity']['fields']['date_closed']['inline_edit']=true;
$dictionary['Opportunity']['fields']['date_closed']['help']='This is the day the contract is signed';
$dictionary['Opportunity']['fields']['date_closed']['comments']='Expected or actual date the oppportunity will close';
$dictionary['Opportunity']['fields']['date_closed']['merge_filter']='disabled';

 

 // created: 2018-10-16 19:06:16
$dictionary['Opportunity']['fields']['opp_owner_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['opp_owner_c']['labelValue']='Opp Owner';

 

 // created: 2018-01-15 18:55:23
$dictionary['Opportunity']['fields']['setup_fee_c']['inline_edit']='';
$dictionary['Opportunity']['fields']['setup_fee_c']['labelValue']='Setup Fee (one time)';

 

 // created: 2018-03-03 19:36:16
$dictionary['Opportunity']['fields']['contact_lead_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['contact_lead_c']['labelValue']='Contacts';

 

// created: 2017-06-22 13:57:06
$dictionary["Opportunity"]["fields"]["lead_account_name"] = array (
	'name' => 'lead_account_name',
	'type' => 'varchar',
	'source' => 'non-db',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_ACCOUNT_NAME',
	'default_value' => '',
);


$dictionary["Opportunity"]["fields"]["totalCountRow"] = array (
	'name' => 'totalCountRow',
	'type' => 'int',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_ROW_COUNT',
	'default_value' => '',
);


 // created: 2017-12-21 17:37:02
$dictionary['Opportunity']['fields']['churned_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['churned_c']['labelValue']='churned';

 

 // created: 2018-01-10 15:49:43
$dictionary['Opportunity']['fields']['amount']['inline_edit']=true;
$dictionary['Opportunity']['fields']['amount']['comments']='Unconverted amount of the opportunity';
$dictionary['Opportunity']['fields']['amount']['duplicate_merge']='enabled';
$dictionary['Opportunity']['fields']['amount']['duplicate_merge_dom_value']='1';
$dictionary['Opportunity']['fields']['amount']['merge_filter']='disabled';

 

 // created: 2017-09-13 15:42:11
$dictionary['Opportunity']['fields']['jjwg_maps_lng_c']['inline_edit']=1;

 

 // created: 2017-11-13 11:44:45
$dictionary['Opportunity']['fields']['term_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['term_c']['labelValue']='Term:';

 

// created: 2017-06-22 13:57:06
$dictionary["Opportunity"]["fields"]["account_description"] = array (
	'name' => 'account_description',
	'type' => 'text',
	'source' => 'non-db',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_ACCOUNT_DESCRIPTION',
	'default_value' => '',
);



$dictionary["Opportunity"]["fields"]["account_type"] = array (
	'name' => 'account_type',
	'type' => 'enum',
	'source' => 'non-db',
	'vname' => 'LBL_ACCOUNT_TYPE', 
	'options' => 'account_type_dom',
	'studio' => true,
	'required' => false,
);


 // created: 2018-01-12 23:01:48
$dictionary['Opportunity']['fields']['industry_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['industry_c']['labelValue']='Industry';

 

 // created: 2018-01-26 16:05:08
$dictionary['Opportunity']['fields']['ad_budget_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['ad_budget_c']['labelValue']='Ad Budget';

 

 // created: 2018-01-15 19:05:56
$dictionary['Opportunity']['fields']['contract_term_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['contract_term_c']['labelValue']='contract term';

 

 // created: 2018-01-09 17:29:09
$dictionary['Opportunity']['fields']['email_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['email_c']['labelValue']='Email';

 

 // created: 2017-12-21 17:48:25
$dictionary['Opportunity']['fields']['on_boarding_incomplete_notes_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['on_boarding_incomplete_notes_c']['labelValue']='On Boarding Incomplete Notes';

 

 // created: 2019-05-18 11:13:16
$dictionary['Opportunity']['fields']['user_opp_owner_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['user_opp_owner_c']['labelValue']='Opp Owner';

 

$dictionary["Opportunity"]["fields"]["project_status"] =array (
	'name' => 'project_status',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_PROJECT_STATUS',
	'type' => 'enum',
	'options' => 'project_status_list',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);

 

$dictionary["Opportunity"]["fields"]["logo_design"] =array (
	'name' => 'logo_design',
	'default_value' => '',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_LOGO_DESIGN',
	'type' => 'int',
	'max_size' => 11,
	'massupdate' => 0,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2017-12-21 17:59:19
$dictionary['Opportunity']['fields']['on_boarding_incomplete_c']['inline_edit']='1';
$dictionary['Opportunity']['fields']['on_boarding_incomplete_c']['labelValue']='On Boarding Incomplete';

 

// created: 2017-06-22 13:57:06
$dictionary["Opportunity"]["fields"]["account_industary"] = array (
	'name' => 'account_industary',
	'type' => 'enum',
	'source' => 'non-db',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_ACCOUNT_INDUSTRY',
	'default_value' => '',
	'options' => 'industry_dom',
);


 // created: 2017-11-14 07:10:38
$dictionary['Opportunity']['fields']['contract_date_c']['inline_edit']='';
$dictionary['Opportunity']['fields']['contract_date_c']['labelValue']='Contract Date';

 

 // created: 2018-06-01 20:17:42
$dictionary['Opportunity']['fields']['account_industary']['inline_edit']=true;
$dictionary['Opportunity']['fields']['account_industary']['merge_filter']='disabled';

 

 // created: 2018-01-19 13:36:31
$dictionary['Opportunity']['fields']['payment_notes_c']['inline_edit']='';
$dictionary['Opportunity']['fields']['payment_notes_c']['labelValue']='Payment Notes';

 

 // created: 2017-09-13 15:42:11
$dictionary['Opportunity']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

// created: 2017-11-14 16:54:05
$dictionary["Opportunity"]["fields"]["opportunities_aos_products_1"] = array (
  'name' => 'opportunities_aos_products_1',
  'type' => 'link',
  'relationship' => 'opportunities_aos_products_1',
  'source' => 'non-db',
  'module' => 'AOS_Products',
  'bean_name' => 'AOS_Products',
  'vname' => 'LBL_OPPORTUNITIES_AOS_PRODUCTS_1_FROM_AOS_PRODUCTS_TITLE',
);


 // created: 2017-09-13 15:42:11
$dictionary['Opportunity']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 
?>