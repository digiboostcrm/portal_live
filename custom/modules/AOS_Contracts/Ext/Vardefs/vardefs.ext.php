<?php 
 //WARNING: The contents of this file are auto-generated



$dictionary["AOS_Contracts"]["fields"]["name_created_by"] =array (
	'name' => 'name_created_by',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_CREATED_BY',
	'type' => 'varchar',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);





 // created: 2017-12-13 17:21:54
$dictionary['AOS_Contracts']['fields']['created_from_c']['inline_edit']=1;
$dictionary['AOS_Contracts']['fields']['created_from_c']['duplicate_merge_dom_value']=0;

 



$dictionary["AOS_Contracts"]["fields"]["custom_file"] =array (
	'name' => 'custom_file',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_CCUSTOM_FILE',
	'type' => 'file',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);





/*
$dictionary['AOS_Contracts']['fields']['custom_file'] = array(

  'name' => 'custom_file',

  'vname' => 'LBL_FILE_MIME_TYPE',

  'type' => 'varchar',

  'len' => '100',

  'importable' => false,

);

$dictionary['AOS_Contracts']['fields']['file_url'] = array(

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

$dictionary['AOS_Contracts']['fields']['filename'] = array(

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