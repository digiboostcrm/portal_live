<?php
// created: 2017-02-13 09:15:27
$dictionary["ut_rightsignature_ut_rsactivities"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'ut_rightsignature_ut_rsactivities' => 
    array (
      'lhs_module' => 'UT_RightSignature',
      'lhs_table' => 'ut_rightsignature',
      'lhs_key' => 'id',
      'rhs_module' => 'UT_RSActivities',
      'rhs_table' => 'ut_rsactivities',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'ut_rightsignature_ut_rsactivities_c',
      'join_key_lhs' => 'ut_rightsignature_ut_rsactivitiesut_rightsignature_ida',
      'join_key_rhs' => 'ut_rightsignature_ut_rsactivitiesut_rsactivities_idb',
    ),
  ),
  'table' => 'ut_rightsignature_ut_rsactivities_c',
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
      'name' => 'ut_rightsignature_ut_rsactivitiesut_rightsignature_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'ut_rightsignature_ut_rsactivitiesut_rsactivities_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'ut_rightsignature_ut_rsactivitiesspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'ut_rightsignature_ut_rsactivities_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'ut_rightsignature_ut_rsactivitiesut_rightsignature_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'ut_rightsignature_ut_rsactivities_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'ut_rightsignature_ut_rsactivitiesut_rsactivities_idb',
      ),
    ),
  ),
);