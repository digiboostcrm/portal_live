<?php
// created: 2015-06-16 11:39:51
$dictionary["rls_scheduling_reports_users"] = array (
  'true_relationship_type' => 'many-to-many',
  'relationships' => 
  array (
    'rls_scheduling_reports_users' => 
    array (
      'lhs_module' => 'RLS_Scheduling_Reports',
      'lhs_table' => 'rls_scheduling_reports',
      'lhs_key' => 'id',
      'rhs_module' => 'Users',
      'rhs_table' => 'users',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'rls_scheduling_reports_users_c',
      'join_key_lhs' => 'rls_scheduling_reports_usersrls_scheduling_reports_ida',
      'join_key_rhs' => 'rls_scheduling_reports_usersusers_idb',
    ),
  ),
  'table' => 'rls_scheduling_reports_users_c',
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
      'name' => 'rls_scheduling_reports_usersrls_scheduling_reports_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'rls_scheduling_reports_usersusers_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'rls_scheduling_reports_usersspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'rls_scheduling_reports_users_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'rls_scheduling_reports_usersrls_scheduling_reports_ida',
        1 => 'rls_scheduling_reports_usersusers_idb',
      ),
    ),
  ),
);