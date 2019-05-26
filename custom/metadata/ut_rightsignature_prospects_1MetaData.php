<?php
// created: 2017-06-22 13:58:40
$dictionary["ut_rightsignature_prospects_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'ut_rightsignature_prospects_1' => 
    array (
      'lhs_module' => 'UT_RightSignature',
      'lhs_table' => 'ut_rightsignature',
      'lhs_key' => 'id',
      'rhs_module' => 'Prospects',
      'rhs_table' => 'prospects',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'ut_rightsignature_prospects_1_c',
      'join_key_lhs' => 'ut_rightsignature_prospects_1ut_rightsignature_ida',
      'join_key_rhs' => 'ut_rightsignature_prospects_1prospects_idb',
    ),
  ),
  'table' => 'ut_rightsignature_prospects_1_c',
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
      'name' => 'ut_rightsignature_prospects_1ut_rightsignature_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'ut_rightsignature_prospects_1prospects_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'ut_rightsignature_prospects_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'ut_rightsignature_prospects_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'ut_rightsignature_prospects_1ut_rightsignature_ida',
        1 => 'ut_rightsignature_prospects_1prospects_idb',
      ),
    ),
  ),
);