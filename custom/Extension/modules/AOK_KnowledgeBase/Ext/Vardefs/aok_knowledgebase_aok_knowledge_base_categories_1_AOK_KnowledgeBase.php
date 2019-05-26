<?php
// created: 2018-04-20 11:11:53
$dictionary["AOK_KnowledgeBase"]["fields"]["aok_knowledgebase_aok_knowledge_base_categories_1"] = array (
  'name' => 'aok_knowledgebase_aok_knowledge_base_categories_1',
  'type' => 'link',
  'relationship' => 'aok_knowledgebase_aok_knowledge_base_categories_1',
  'source' => 'non-db',
  'module' => 'AOK_Knowledge_Base_Categories',
  'bean_name' => 'AOK_Knowledge_Base_Categories',
  'vname' => 'LBL_AOK_KNOWLEDGEBASE_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGE_BASE_CATEGORIES_TITLE',
  'id_name' => 'aok_knowleec4eegories_idb',
);
$dictionary["AOK_KnowledgeBase"]["fields"]["aok_knowledgebase_aok_knowledge_base_categories_1_name"] = array (
  'name' => 'aok_knowledgebase_aok_knowledge_base_categories_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOK_KNOWLEDGEBASE_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGE_BASE_CATEGORIES_TITLE',
  'save' => true,
  'id_name' => 'aok_knowleec4eegories_idb',
  'link' => 'aok_knowledgebase_aok_knowledge_base_categories_1',
  'table' => 'aok_knowledge_base_categories',
  'module' => 'AOK_Knowledge_Base_Categories',
  'rname' => 'name',
);
$dictionary["AOK_KnowledgeBase"]["fields"]["aok_knowleec4eegories_idb"] = array (
  'name' => 'aok_knowleec4eegories_idb',
  'type' => 'link',
  'relationship' => 'aok_knowledgebase_aok_knowledge_base_categories_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_AOK_KNOWLEDGEBASE_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGE_BASE_CATEGORIES_TITLE',
);
