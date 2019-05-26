<?php 
 //WARNING: The contents of this file are auto-generated


$dictionary["AOS_Invoices"]["fields"]["qbcreatedtime_c"]= array (
		'name' => 'qbcreatedtime_c',
		'vname' => 'LBL_QB_CREATED_TIME',
		'type' => 'datetime',
		'len' => '50',
		);

$dictionary["AOS_Invoices"]["fields"]["qbmodifiedtime_c"]= array (
		'name' => 'qbmodifiedtime_c',
		'vname' => 'LBL_QB_MODIFIED_TIME',
		'type' => 'datetime',
		'len' => '50',
		);

$dictionary["AOS_Invoices"]["fields"]["qbid_c"]= array (
		'name' => 'qbid_c',
		'vname' => 'LBL_QBID',
		'type' => 'varchar',
		'len' => '50',
		);




 // created: 2018-06-22 18:18:07
$dictionary['AOS_Invoices']['fields']['type_c']['inline_edit']='1';
$dictionary['AOS_Invoices']['fields']['type_c']['labelValue']='Type';

 

 // created: 2017-12-13 17:21:54
$dictionary['AOS_Invoices']['fields']['created_from_c']['inline_edit']=1;
$dictionary['AOS_Invoices']['fields']['created_from_c']['duplicate_merge_dom_value']=0;

 


$dictionary["AOS_Invoices"]["fields"]["custom_file"] =array (
	'name' => 'custom_file',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREDIT_CARD_MASTER12',
	'type' => 'file',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);




/*
$dictionary['AOS_Invoices']['fields']['file_mime_type'] = array(

  'name' => 'file_mime_type',

  'vname' => 'LBL_FILE_MIME_TYPE',

  'type' => 'varchar',

  'len' => '100',

  'importable' => false,

);

$dictionary['AOS_Invoices']['fields']['file_url'] = array(

  'name'=>'file_url',

  'vname' => 'LBL_FILE_URL',

  'type'=>'function',

  'function_class'=>'UploadFile',

  'function_name'=>'get_upload_url',

  'function_params'=> array('$this'),

  'source'=>'function',

  'reportable'=>false,

  'importable' => false,

);

$dictionary['AOS_Invoices']['fields']['filename'] = array(

  'name' => 'filename',

  'vname' => 'LBL_FILENAME',

  'type' => 'file',

  'dbType' => 'varchar',

  'len' => '255',

  'reportable'=>true,

  'importable' => false,

);
*/
?>