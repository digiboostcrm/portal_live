<?

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



?>