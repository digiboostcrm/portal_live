<?php
// created: 2018-04-24 17:41:51
$dictionary["aok_knowledge_base_sub_cat_aok_knowledgebase_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'aok_knowledge_base_sub_cat_aok_knowledgebase_1' => 
    array (
      'lhs_module' => 'AOK_Knowledge_Base_Sub_Cat',
      'lhs_table' => 'aok_knowledge_base_sub_cat',
      'lhs_key' => 'id',
      'rhs_module' => 'AOK_KnowledgeBase',
      'rhs_table' => 'aok_knowledgebase',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1_c',
      'join_key_lhs' => 'aok_knowle9facsub_cat_ida',
      'join_key_rhs' => 'aok_knowledceedgebase_idb',
    ),
  ),
  'table' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1_c',
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
      'name' => 'aok_knowle9facsub_cat_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'aok_knowledceedgebase_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'aok_knowle9facsub_cat_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'aok_knowledceedgebase_idb',
      ),
    ),
  ),
);