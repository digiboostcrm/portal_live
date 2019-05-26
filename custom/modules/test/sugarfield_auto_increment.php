<?


$dictionary["Account"]["fields"]["aupair_number"] =array (
'required' => false,
'name' => 'aupair_number',
'vname' => 'LBL_AUPAIRS_AUPAIR_NUMBER',
'type' => 'int',
'massupdate' => 0,

'importable' => ‘true’,
'duplicate_merge' => 'disabled',
'duplicate_merge_dom_value' => 0,
'audited' => false,
'reportable' => true,
'calculated' => false,
'auto_increment'=>true,
);

$dictionary["Account"]["indices"]["aupair_number"] = array(
'name' => 'aupair_number',
'type' => 'unique',
'fields' => array(
'aupair_number'
),
);



?>