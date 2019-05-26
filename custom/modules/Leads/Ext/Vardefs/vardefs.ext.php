<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2018-06-29 17:39:20
$dictionary['Lead']['fields']['sdr']['name']='sdr';
$dictionary['Lead']['fields']['sdr']['studio']=true;
$dictionary['Lead']['fields']['sdr']['required']=true;
$dictionary['Lead']['fields']['sdr']['vname']='LBL_SDR';
$dictionary['Lead']['fields']['sdr']['type']='enum';
$dictionary['Lead']['fields']['sdr']['options']='sales_sdr_list';
$dictionary['Lead']['fields']['sdr']['importable']=true;
$dictionary['Lead']['fields']['sdr']['audited']=false;
$dictionary['Lead']['fields']['sdr']['reportable']=true;
$dictionary['Lead']['fields']['sdr']['default']='Madison_Spaulding';
$dictionary['Lead']['fields']['sdr']['inline_edit']=true;
$dictionary['Lead']['fields']['sdr']['merge_filter']='disabled';

 

 // created: 2017-09-13 15:42:10
$dictionary['Lead']['fields']['jjwg_maps_lat_c']['inline_edit']=1;

 

 // created: 2018-03-03 19:26:06
$dictionary['Lead']['fields']['contact_id_c']['inline_edit']=1;

 

$dictionary["Lead"]["fields"]["company_name"] =array (
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



 // created: 2017-11-06 11:02:13
$dictionary['Lead']['fields']['phone_fax']['inline_edit']=true;
$dictionary['Lead']['fields']['phone_fax']['comments']='Contact facebook:';
$dictionary['Lead']['fields']['phone_fax']['merge_filter']='disabled';

 

 // created: 2019-05-08 19:12:05
$dictionary['Lead']['fields']['lead_source_1_c']['inline_edit']='1';
$dictionary['Lead']['fields']['lead_source_1_c']['labelValue']='Secondary Lead Source';

 

$dictionary["Lead"]["fields"]["tracking_number"] =array (
	'name' => 'tracking_number',
	'default_value' => '',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_TRACKING_NUMBER',
	'type' => 'varchar',
	'massupdate' => 0,
	'max_size' => 11,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2017-11-14 14:43:40
$dictionary['Lead']['fields']['next_step_c']['inline_edit']='1';
$dictionary['Lead']['fields']['next_step_c']['labelValue']='Next Step:';

 

 // created: 2019-05-12 15:54:47
$dictionary['Lead']['fields']['title']['required']=false;
$dictionary['Lead']['fields']['title']['inline_edit']=true;
$dictionary['Lead']['fields']['title']['comments']='The title of the contact';
$dictionary['Lead']['fields']['title']['merge_filter']='disabled';

 

 // created: 2019-05-08 19:13:28
$dictionary['Lead']['fields']['lead_source']['inline_edit']=true;
$dictionary['Lead']['fields']['lead_source']['options']='lead_source_0';
$dictionary['Lead']['fields']['lead_source']['comments']='Lead source (ex: Web, print)';
$dictionary['Lead']['fields']['lead_source']['merge_filter']='disabled';
$dictionary['Lead']['fields']['lead_source']['required']=true;
$dictionary['Lead']['fields']['lead_source']['default']='';

 

 // created: 2019-05-12 14:00:04
$dictionary['Lead']['fields']['first_name']['required']=true;
$dictionary['Lead']['fields']['first_name']['inline_edit']=true;
$dictionary['Lead']['fields']['first_name']['comments']='First name of the contact';
$dictionary['Lead']['fields']['first_name']['merge_filter']='disabled';

 

 // created: 2019-05-12 16:39:26
$dictionary['Lead']['fields']['email1']['required']=true;
$dictionary['Lead']['fields']['email1']['inline_edit']=true;
$dictionary['Lead']['fields']['email1']['merge_filter']='disabled';

 

 // created: 2019-05-12 14:01:13
$dictionary['Lead']['fields']['status']['inline_edit']=true;
$dictionary['Lead']['fields']['status']['comments']='Status of the lead';
$dictionary['Lead']['fields']['status']['merge_filter']='disabled';
$dictionary['Lead']['fields']['status']['required']=true;

 

 // created: 2018-03-03 19:26:06
$dictionary['Lead']['fields']['contact_lead_c']['inline_edit']='1';
$dictionary['Lead']['fields']['contact_lead_c']['labelValue']='Contacts';

 

 // created: 2019-05-14 19:57:08
$dictionary['Lead']['fields']['phone_work']['required']=true;
$dictionary['Lead']['fields']['phone_work']['inline_edit']=true;
$dictionary['Lead']['fields']['phone_work']['comments']='Work phone number of the contact';
$dictionary['Lead']['fields']['phone_work']['merge_filter']='disabled';

 

 // created: 2019-05-14 19:27:33
$dictionary['Lead']['fields']['description']['required']=true;
$dictionary['Lead']['fields']['description']['inline_edit']=true;
$dictionary['Lead']['fields']['description']['comments']='Full text of the note';
$dictionary['Lead']['fields']['description']['merge_filter']='disabled';

 

 // created: 2018-03-01 16:32:40
$dictionary['Lead']['fields']['lead_type']['name']='lead_type';
$dictionary['Lead']['fields']['lead_type']['studio']=true;
$dictionary['Lead']['fields']['lead_type']['required']=true;
$dictionary['Lead']['fields']['lead_type']['vname']='LBL_LEAD_TYPE';
$dictionary['Lead']['fields']['lead_type']['type']='enum';
$dictionary['Lead']['fields']['lead_type']['options']='opportunity_type_dom';
$dictionary['Lead']['fields']['lead_type']['importable']=true;
$dictionary['Lead']['fields']['lead_type']['audited']=false;
$dictionary['Lead']['fields']['lead_type']['reportable']=true;
$dictionary['Lead']['fields']['lead_type']['inline_edit']=true;
$dictionary['Lead']['fields']['lead_type']['merge_filter']='disabled';

 

 // created: 2017-09-13 15:42:10
$dictionary['Lead']['fields']['jjwg_maps_lng_c']['inline_edit']=1;

 

// created: 2017-11-14 16:52:19
$dictionary["Lead"]["fields"]["leads_aos_products_1"] = array (
  'name' => 'leads_aos_products_1',
  'type' => 'link',
  'relationship' => 'leads_aos_products_1',
  'source' => 'non-db',
  'module' => 'AOS_Products',
  'bean_name' => 'AOS_Products',
  'vname' => 'LBL_LEADS_AOS_PRODUCTS_1_FROM_AOS_PRODUCTS_TITLE',
);


 // created: 2017-09-13 15:42:11
$dictionary['Lead']['fields']['jjwg_maps_address_c']['inline_edit']=1;

 

 // created: 2017-09-13 15:42:11
$dictionary['Lead']['fields']['jjwg_maps_geocode_status_c']['inline_edit']=1;

 

// created: 2017-11-14 16:51:37
$dictionary["Lead"]["fields"]["leads_aos_product_categories_1"] = array (
  'name' => 'leads_aos_product_categories_1',
  'type' => 'link',
  'relationship' => 'leads_aos_product_categories_1',
  'source' => 'non-db',
  'module' => 'AOS_Product_Categories',
  'bean_name' => 'AOS_Product_Categories',
  'vname' => 'LBL_LEADS_AOS_PRODUCT_CATEGORIES_1_FROM_AOS_PRODUCT_CATEGORIES_TITLE',
);

?>