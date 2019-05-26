<?php
// created: 2017-02-21 13:32:26
$dictionary["ut_signers_ut_rightsignature"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'ut_signers_ut_rightsignature' => 
    array (
      'lhs_module' => 'UT_RightSignature',
      'lhs_table' => 'ut_rightsignature',
      'lhs_key' => 'id',
      'rhs_module' => 'UT_Signers',
      'rhs_table' => 'ut_signers',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'ut_signers_ut_rightsignature_c',
      'join_key_lhs' => 'ut_signers_ut_rightsignatureut_rightsignature_ida',
      'join_key_rhs' => 'ut_signers_ut_rightsignatureut_signers_idb',
    ),
  ),
  'table' => 'ut_signers_ut_rightsignature_c',
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
      'name' => 'ut_signers_ut_rightsignatureut_rightsignature_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'ut_signers_ut_rightsignatureut_signers_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'ut_signers_ut_rightsignaturespk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'ut_signers_ut_rightsignature_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'ut_signers_ut_rightsignatureut_rightsignature_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'ut_signers_ut_rightsignature_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'ut_signers_ut_rightsignatureut_signers_idb',
      ),
    ),
  ),
);