<?php
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2017 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */


$relationships = array (
  'aok_knowledge_base_sub_cat_modified_user' => 
  array (
    'id' => '853fb0b0-eb01-28ba-b62a-5adf6bfb54cd',
    'relationship_name' => 'aok_knowledge_base_sub_cat_modified_user',
    'lhs_module' => 'Users',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'AOK_Knowledge_Base_Sub_Cat',
    'rhs_table' => 'aok_knowledge_base_sub_cat',
    'rhs_key' => 'modified_user_id',
    'join_table' => NULL,
    'join_key_lhs' => NULL,
    'join_key_rhs' => NULL,
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'aok_knowledge_base_sub_cat_created_by' => 
  array (
    'id' => '8877a915-7c46-5c90-486c-5adf6be59bfd',
    'relationship_name' => 'aok_knowledge_base_sub_cat_created_by',
    'lhs_module' => 'Users',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'AOK_Knowledge_Base_Sub_Cat',
    'rhs_table' => 'aok_knowledge_base_sub_cat',
    'rhs_key' => 'created_by',
    'join_table' => NULL,
    'join_key_lhs' => NULL,
    'join_key_rhs' => NULL,
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'aok_knowledge_base_sub_cat_assigned_user' => 
  array (
    'id' => '8ba5495e-dc37-8901-9f9c-5adf6bc6b4f3',
    'relationship_name' => 'aok_knowledge_base_sub_cat_assigned_user',
    'lhs_module' => 'Users',
    'lhs_table' => 'users',
    'lhs_key' => 'id',
    'rhs_module' => 'AOK_Knowledge_Base_Sub_Cat',
    'rhs_table' => 'aok_knowledge_base_sub_cat',
    'rhs_key' => 'assigned_user_id',
    'join_table' => NULL,
    'join_key_lhs' => NULL,
    'join_key_rhs' => NULL,
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'securitygroups_aok_knowledge_base_sub_cat' => 
  array (
    'id' => '8ed10325-c02c-1477-c7c1-5adf6bcce7bd',
    'relationship_name' => 'securitygroups_aok_knowledge_base_sub_cat',
    'lhs_module' => 'SecurityGroups',
    'lhs_table' => 'securitygroups',
    'lhs_key' => 'id',
    'rhs_module' => 'AOK_Knowledge_Base_Sub_Cat',
    'rhs_table' => 'aok_knowledge_base_sub_cat',
    'rhs_key' => 'id',
    'join_table' => 'securitygroups_records',
    'join_key_lhs' => 'securitygroup_id',
    'join_key_rhs' => 'record_id',
    'relationship_type' => 'many-to-many',
    'relationship_role_column' => 'module',
    'relationship_role_column_value' => 'AOK_Knowledge_Base_Sub_Cat',
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => NULL,
    'lhs_subpanel' => NULL,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
  ),
  'aok_knowledge_base_categories_aok_knowledge_base_sub_cat_1' => 
  array (
    'id' => '99aabbb5-11c1-2791-d505-5adf6bcf1f8b',
    'relationship_name' => 'aok_knowledge_base_categories_aok_knowledge_base_sub_cat_1',
    'lhs_module' => 'AOK_Knowledge_Base_Categories',
    'lhs_table' => 'aok_knowledge_base_categories',
    'lhs_key' => 'id',
    'rhs_module' => 'AOK_Knowledge_Base_Sub_Cat',
    'rhs_table' => 'aok_knowledge_base_sub_cat',
    'rhs_key' => 'id',
    'join_table' => 'aok_knowledge_base_categories_aok_knowledge_base_sub_cat_1_c',
    'join_key_lhs' => 'aok_knowlea2e5egories_ida',
    'join_key_rhs' => 'aok_knowle931asub_cat_idb',
    'relationship_type' => 'one-to-many',
    'relationship_role_column' => NULL,
    'relationship_role_column_value' => NULL,
    'reverse' => '0',
    'deleted' => '0',
    'readonly' => true,
    'rhs_subpanel' => 'default',
    'lhs_subpanel' => NULL,
    'from_studio' => true,
    'is_custom' => true,
    'relationship_only' => false,
    'for_activities' => false,
  ),
  'aok_knowledge_base_sub_cat_aok_knowledgebase_1' => 
  array (
    'rhs_label' => 'Knowledge Base',
    'lhs_label' => 'KB Sub Catagories',
    'rhs_subpanel' => 'default',
    'lhs_module' => 'AOK_Knowledge_Base_Sub_Cat',
    'rhs_module' => 'AOK_KnowledgeBase',
    'relationship_type' => 'one-to-many',
    'readonly' => true,
    'deleted' => false,
    'relationship_only' => false,
    'for_activities' => false,
    'is_custom' => false,
    'from_studio' => true,
    'relationship_name' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1',
  ),
);