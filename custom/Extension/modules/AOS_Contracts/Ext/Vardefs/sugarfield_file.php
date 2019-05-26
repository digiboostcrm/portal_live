<?php


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


?>

<?php
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