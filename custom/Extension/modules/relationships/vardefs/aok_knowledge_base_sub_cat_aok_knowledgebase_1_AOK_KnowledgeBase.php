<?php
// created: 2018-04-24 17:41:51
$dictionary["AOK_KnowledgeBase"]["fields"]["aok_knowledge_base_sub_cat_aok_knowledgebase_1"] = array (
  'name' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1',
  'type' => 'link',
  'relationship' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1',
  'source' => 'non-db',
  'module' => 'AOK_Knowledge_Base_Sub_Cat',
  'bean_name' => 'AOK_Knowledge_Base_Sub_Cat',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_SUB_CAT_AOK_KNOWLEDGEBASE_1_FROM_AOK_KNOWLEDGE_BASE_SUB_CAT_TITLE',
  'id_name' => 'aok_knowle9facsub_cat_ida',
);
$dictionary["AOK_KnowledgeBase"]["fields"]["aok_knowledge_base_sub_cat_aok_knowledgebase_1_name"] = array (
  'name' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_SUB_CAT_AOK_KNOWLEDGEBASE_1_FROM_AOK_KNOWLEDGE_BASE_SUB_CAT_TITLE',
  'save' => true,
  'id_name' => 'aok_knowle9facsub_cat_ida',
  'link' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1',
  'table' => 'aok_knowledge_base_sub_cat',
  'module' => 'AOK_Knowledge_Base_Sub_Cat',
  'rname' => 'name',
);
$dictionary["AOK_KnowledgeBase"]["fields"]["aok_knowle9facsub_cat_ida"] = array (
  'name' => 'aok_knowle9facsub_cat_ida',
  'type' => 'link',
  'relationship' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_SUB_CAT_AOK_KNOWLEDGEBASE_1_FROM_AOK_KNOWLEDGEBASE_TITLE',
);
