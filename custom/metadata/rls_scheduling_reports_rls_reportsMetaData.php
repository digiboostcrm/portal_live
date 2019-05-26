<?php
// created: 2015-06-16 11:43:23
$dictionary["rls_scheduling_reports_rls_reports"] = array (
  'true_relationship_type' => 'many-to-many',
  'relationships' => 
  array (
    'rls_scheduling_reports_rls_reports' => 
    array (
      'lhs_module' => 'RLS_Scheduling_Reports',
      'lhs_table' => 'rls_scheduling_reports',
      'lhs_key' => 'id',
      'rhs_module' => 'rls_Reports',
      'rhs_table' => 'rls_reports',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'rls_scheduling_reports_rls_reports_c',
      'join_key_lhs' => 'rls_scheduling_reports_rls_reportsrls_scheduling_reports_ida',
      'join_key_rhs' => 'rls_scheduling_reports_rls_reportsrls_reports_idb',
    ),
  ),
  'table' => 'rls_scheduling_reports_rls_reports_c',
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
      'name' => 'rls_scheduling_reports_rls_reportsrls_scheduling_reports_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'rls_scheduling_reports_rls_reportsrls_reports_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'rls_scheduling_reports_rls_reportsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'rls_scheduling_reports_rls_reports_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'rls_scheduling_reports_rls_reportsrls_scheduling_reports_ida',
        1 => 'rls_scheduling_reports_rls_reportsrls_reports_idb',
      ),
    ),
  ),
);