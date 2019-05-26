<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2018-04-18 22:13:22
$dictionary["AOK_Knowledge_Base_Categories"]["fields"]["aok_knowledge_base_categories_aok_knowledge_base_sub_cat_1"] = array (
  'name' => 'aok_knowledge_base_categories_aok_knowledge_base_sub_cat_1',
  'type' => 'link',
  'relationship' => 'aok_knowledge_base_categories_aok_knowledge_base_sub_cat_1',
  'source' => 'non-db',
  'module' => 'AOK_Knowledge_Base_Sub_Cat',
  'bean_name' => 'AOK_Knowledge_Base_Sub_Cat',
  'side' => 'right',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_CATEGORIES_AOK_KNOWLEDGE_BASE_SUB_CAT_1_FROM_AOK_KNOWLEDGE_BASE_SUB_CAT_TITLE',
);


// created: 2018-04-20 11:11:53
$dictionary["AOK_Knowledge_Base_Categories"]["fields"]["aok_knowledgebase_aok_knowledge_base_categories_1"] = array (
  'name' => 'aok_knowledgebase_aok_knowledge_base_categories_1',
  'type' => 'link',
  'relationship' => 'aok_knowledgebase_aok_knowledge_base_categories_1',
  'source' => 'non-db',
  'module' => 'AOK_KnowledgeBase',
  'bean_name' => 'AOK_KnowledgeBase',
  'vname' => 'LBL_AOK_KNOWLEDGEBASE_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGEBASE_TITLE',
  'id_name' => 'aok_knowle7f42dgebase_ida',
);
$dictionary["AOK_Knowledge_Base_Categories"]["fields"]["aok_knowledgebase_aok_knowledge_base_categories_1_name"] = array (
  'name' => 'aok_knowledgebase_aok_knowledge_base_categories_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOK_KNOWLEDGEBASE_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGEBASE_TITLE',
  'save' => true,
  'id_name' => 'aok_knowle7f42dgebase_ida',
  'link' => 'aok_knowledgebase_aok_knowledge_base_categories_1',
  'table' => 'aok_knowledgebase',
  'module' => 'AOK_KnowledgeBase',
  'rname' => 'name',
);
$dictionary["AOK_Knowledge_Base_Categories"]["fields"]["aok_knowle7f42dgebase_ida"] = array (
  'name' => 'aok_knowle7f42dgebase_ida',
  'type' => 'link',
  'relationship' => 'aok_knowledgebase_aok_knowledge_base_categories_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'left',
  'vname' => 'LBL_AOK_KNOWLEDGEBASE_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGEBASE_TITLE',
);

?>