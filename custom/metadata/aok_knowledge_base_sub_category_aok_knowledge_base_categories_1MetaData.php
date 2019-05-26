<?php
// created: 2018-04-18 21:23:07
$dictionary["aok_knowledge_base_sub_category_aok_knowledge_base_categories_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'aok_knowledge_base_sub_category_aok_knowledge_base_categories_1' => 
    array (
      'lhs_module' => 'AOK_Knowledge_Base_Sub_Category',
      'lhs_table' => 'aok_knowledge_base_sub_category',
      'lhs_key' => 'id',
      'rhs_module' => 'AOK_Knowledge_Base_Categories',
      'rhs_table' => 'aok_knowledge_base_categories',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'aok_knowle1d67egories_1_c',
      'join_key_lhs' => 'aok_knowle5e99ategory_ida',
      'join_key_rhs' => 'aok_knowlecd19egories_idb',
    ),
  ),
  'table' => 'aok_knowle1d67egories_1_c',
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
      'name' => 'aok_knowle5e99ategory_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'aok_knowlecd19egories_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'aok_knowledge_base_sub_category_aok_knowledge_base_categories_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'aok_knowledge_base_sub_category_aok_knowledge_base_categories_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'aok_knowle5e99ategory_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'aok_knowledge_base_sub_category_aok_knowledge_base_categories_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'aok_knowlecd19egories_idb',
      ),
    ),
  ),
);