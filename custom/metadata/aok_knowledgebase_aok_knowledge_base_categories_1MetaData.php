<?php
// created: 2018-04-20 11:11:53
$dictionary["aok_knowledgebase_aok_knowledge_base_categories_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => false,
  'relationships' => 
  array (
    'aok_knowledgebase_aok_knowledge_base_categories_1' => 
    array (
      'lhs_module' => 'AOK_KnowledgeBase',
      'lhs_table' => 'aok_knowledgebase',
      'lhs_key' => 'id',
      'rhs_module' => 'AOK_Knowledge_Base_Categories',
      'rhs_table' => 'aok_knowledge_base_categories',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'aok_knowledgebase_aok_knowledge_base_categories_1_c',
      'join_key_lhs' => 'aok_knowle7f42dgebase_ida',
      'join_key_rhs' => 'aok_knowleec4eegories_idb',
    ),
  ),
  'table' => 'aok_knowledgebase_aok_knowledge_base_categories_1_c',
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
      'name' => 'aok_knowle7f42dgebase_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'aok_knowleec4eegories_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'aok_knowledgebase_aok_knowledge_base_categories_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'aok_knowledgebase_aok_knowledge_base_categories_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'aok_knowle7f42dgebase_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'aok_knowledgebase_aok_knowledge_base_categories_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'aok_knowleec4eegories_idb',
      ),
    ),
  ),
);