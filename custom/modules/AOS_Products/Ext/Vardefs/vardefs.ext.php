<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2017-11-14 16:54:05
$dictionary["AOS_Products"]["fields"]["opportunities_aos_products_1"] = array (
  'name' => 'opportunities_aos_products_1',
  'type' => 'link',
  'relationship' => 'opportunities_aos_products_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OPPORTUNITIES_AOS_PRODUCTS_1_FROM_OPPORTUNITIES_TITLE',
);


// created: 2017-11-14 16:52:19
$dictionary["AOS_Products"]["fields"]["leads_aos_products_1"] = array (
  'name' => 'leads_aos_products_1',
  'type' => 'link',
  'relationship' => 'leads_aos_products_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_AOS_PRODUCTS_1_FROM_LEADS_TITLE',
);


 // created: 2018-01-30 15:06:00
$dictionary['AOS_Products']['fields']['price']['inline_edit']=true;
$dictionary['AOS_Products']['fields']['price']['merge_filter']='disabled';

 

 // created: 2018-01-30 15:06:42
$dictionary['AOS_Products']['fields']['one_time_price_c']['inline_edit']='1';
$dictionary['AOS_Products']['fields']['one_time_price_c']['labelValue']='One Time Price';

 

$dictionary["AOS_Products"]["fields"]["qbcreatedtime_c"]= array (
		'name' => 'qbcreatedtime_c',
		'vname' => 'LBL_QB_CREATED_TIME',
		'type' => 'datetime',
		'len' => '50',
		);

$dictionary["AOS_Products"]["fields"]["qbmodifiedtime_c"]= array (
		'name' => 'qbmodifiedtime_c',
		'vname' => 'LBL_QB_MODIFIED_TIME',
		'type' => 'datetime',
		'len' => '50',
		);

$dictionary["AOS_Products"]["fields"]["qbid_c"]= array (
		'name' => 'qbid_c',
		'vname' => 'LBL_QBID',
		'type' => 'varchar',
		'len' => '50',
		);

$dictionary["AOS_Products"]["fields"]["qbincomeaccount_c"] = array (
		'name' => 'qbincomeaccount_c',
		'vname' => 'LBL_QBINCOMEACCOUNT',
		'type' => 'enum',
                'required' => false,
		'function' => 'getQBItemAccounts_qbs_QBSugar',
		); 

$dictionary["AOS_Products"]["fields"]["qbexpenseaccount_c"] = array (
		'name' => 'qbexpenseaccount_c',
		'vname' => 'LBL_QBEXPENSEACCOUNT',
		'type' => 'enum',
		'required' => false,
		'function' => 'getQBItemAccounts_qbs_QBSugar',
		); 
$dictionary["AOS_Products"]["fields"]["qbstartdate_c"] = array(
		'name' => 'qbstartdate_c',
		'vname' => 'Start Date',
		'type' => 'date',
                'len' => '50',
		);
$dictionary["AOS_Products"]["fields"]["qbqtyinhand_c"] = array(
		'name' => 'qbqtyinhand_c',
		'vname' => 'Qty. in Hand',
		'type' => 'decimal(25,3)',
                'len' => '25',
		);
$dictionary["AOS_Products"]["fields"]["qbtype_c"] = array(
		'name' => 'qbtype_c',
		'vname' => 'Type',
		'type' => 'enum',
		'required' => false,
		'function' => 'getQBType_qbs_QBSugar',
		);
$dictionary["AOS_Products"]["fields"]["qbassetaccount_c"] = array(
		'name' => 'qbassetaccount_c',
		'vname' => 'Asset Account',
		'type' => 'enum',
		'required' => false,
		'function' => 'getQBAssetAccount_qbs_QBSugar',
		);




$dictionary["AOS_Products"]["fields"]["out_sourced"] = array (
	'studio' => true,
	'name' => 'out_sourced',
	'type' => 'enum',
	'vname' => 'LBL_OUT_SOURCED', 
	'options' => 'outsourced_dom',
	'required' => false,
);

?>