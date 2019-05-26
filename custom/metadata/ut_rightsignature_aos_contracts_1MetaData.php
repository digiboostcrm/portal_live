<?php
// created: 2017-06-22 14:00:05
$dictionary["ut_rightsignature_aos_contracts_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'ut_rightsignature_aos_contracts_1' => 
    array (
      'lhs_module' => 'UT_RightSignature',
      'lhs_table' => 'ut_rightsignature',
      'lhs_key' => 'id',
      'rhs_module' => 'AOS_Contracts',
      'rhs_table' => 'aos_contracts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'ut_rightsignature_aos_contracts_1_c',
      'join_key_lhs' => 'ut_rightsignature_aos_contracts_1ut_rightsignature_ida',
      'join_key_rhs' => 'ut_rightsignature_aos_contracts_1aos_contracts_idb',
    ),
  ),
  'table' => 'ut_rightsignature_aos_contracts_1_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'ut_rightsignature_aos_contracts_1ut_rightsignature_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'ut_rightsignature_aos_contracts_1aos_contracts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'ut_rightsignature_aos_contracts_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'ut_rightsignature_aos_contracts_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'ut_rightsignature_aos_contracts_1ut_rightsignature_ida',
        1 => 'ut_rightsignature_aos_contracts_1aos_contracts_idb',
      ),
    ),
  ),
);