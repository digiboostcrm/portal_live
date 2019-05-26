<?php
/**
 * The file used to store vardefs for relationship of User Group module and Contacts.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */ 
$dictionary["Contact"]["fields"]["bc_user_group_contacts"] = array (
  'name' => 'bc_user_group_contacts',
  'type' => 'link',
  'relationship' => 'bc_user_group_contacts',
  'source' => 'non-db',
  'vname' => 'LBL_BC_USER_GROUP_CONTACTS_FROM_BC_USER_GROUP_TITLE',
  'id_name' => 'bc_user_group_contactsbc_user_group_ida',
);
$dictionary["Contact"]["fields"]["bc_user_group_contacts_name"] = array (
  'name' => 'bc_user_group_contacts_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_USER_GROUP_CONTACTS_FROM_BC_USER_GROUP_TITLE',
  'save' => true,
  'id_name' => 'bc_user_group_contactsbc_user_group_ida',
  'link' => 'bc_user_group_contacts',
  'table' => 'bc_user_group',
  'module' => 'bc_user_group',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["bc_user_group_contactsbc_user_group_ida"] = array (
  'name' => 'bc_user_group_contactsbc_user_group_ida',
  'type' => 'link',
  'relationship' => 'bc_user_group_contacts',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_USER_GROUP_CONTACTS_FROM_CONTACTS_TITLE',
);
