<?php
// created: 2018-04-18 21:23:07
$dictionary["AOK_Knowledge_Base_Categories"]["fields"]["aok_knowledge_base_sub_category_aok_knowledge_base_categories_1"] = array (
  'name' => 'aok_knowledge_base_sub_category_aok_knowledge_base_categories_1',
  'type' => 'link',
  'relationship' => 'aok_knowledge_base_sub_category_aok_knowledge_base_categories_1',
  'source' => 'non-db',
  'module' => 'AOK_Knowledge_Base_Sub_Category',
  'bean_name' => 'AOK_Knowledge_Base_Sub_Category',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_TITLE',
  'id_name' => 'aok_knowle5e99ategory_ida',
);
$dictionary["AOK_Knowledge_Base_Categories"]["fields"]["aok_knowle2760ries_1_name"] = array (
  'name' => 'aok_knowle2760ries_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_TITLE',
  'save' => true,
  'id_name' => 'aok_knowle5e99ategory_ida',
  'link' => 'aok_knowledge_base_sub_category_aok_knowledge_base_categories_1',
  'table' => 'aok_knowledge_base_sub_category',
  'module' => 'AOK_Knowledge_Base_Sub_Category',
  'rname' => 'name',
);
$dictionary["AOK_Knowledge_Base_Categories"]["fields"]["aok_knowle5e99ategory_ida"] = array (
  'name' => 'aok_knowle5e99ategory_ida',
  'type' => 'link',
  'relationship' => 'aok_knowledge_base_sub_category_aok_knowledge_base_categories_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_AOK_KNOWLEDGE_BASE_CATEGORIES_1_FROM_AOK_KNOWLEDGE_BASE_CATEGORIES_TITLE',
);
