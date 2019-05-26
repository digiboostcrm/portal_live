<?php
/**
 * The file used to store metadata for relationship of User Group module and Contacts.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */ 
$dictionary["bc_user_group_contacts"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'bc_user_group_contacts' => 
    array (
      'lhs_module' => 'bc_user_group',
      'lhs_table' => 'bc_user_group',
      'lhs_key' => 'id',
      'rhs_module' => 'Contacts',
      'rhs_table' => 'contacts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'bc_user_group_contacts_c',
      'join_key_lhs' => 'bc_user_group_contactsbc_user_group_ida',
      'join_key_rhs' => 'bc_user_group_contactscontacts_idb',
    ),
  ),
  'table' => 'bc_user_group_contacts_c',
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
      'name' => 'bc_user_group_contactsbc_user_group_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'bc_user_group_contactscontacts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'bc_user_group_contactsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'bc_user_group_contacts_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'bc_user_group_contactsbc_user_group_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'bc_user_group_contacts_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'bc_user_group_contactscontacts_idb',
      ),
    ),
  ),
);