<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2018-07-18 09:56:51
$dictionary["Note"]["fields"]["opportunities_notes_1"] = array (
  'name' => 'opportunities_notes_1',
  'type' => 'link',
  'relationship' => 'opportunities_notes_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_OPPORTUNITIES_NOTES_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'opportunities_notes_1opportunities_ida',
);
$dictionary["Note"]["fields"]["opportunities_notes_1_name"] = array (
  'name' => 'opportunities_notes_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_OPPORTUNITIES_NOTES_1_FROM_OPPORTUNITIES_TITLE',
  'save' => true,
  'id_name' => 'opportunities_notes_1opportunities_ida',
  'link' => 'opportunities_notes_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["Note"]["fields"]["opportunities_notes_1opportunities_ida"] = array (
  'name' => 'opportunities_notes_1opportunities_ida',
  'type' => 'link',
  'relationship' => 'opportunities_notes_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_OPPORTUNITIES_NOTES_1_FROM_NOTES_TITLE',
);


 // created: 2017-12-13 17:21:55
$dictionary['Note']['fields']['created_from_c']['inline_edit']=1;
$dictionary['Note']['fields']['created_from_c']['duplicate_merge_dom_value']=0;

 

$dictionary["Note"]["fields"]["notes_type"] =array (
	'name' => 'notes_type',
	'studio' => true,
	'required' => false,
	'vname' => 'LBL_NOTES_TYPE',
	'type' => 'enum',
	'options' => 'notes_type_dom',
	'default_value' => '',
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);

 
?>