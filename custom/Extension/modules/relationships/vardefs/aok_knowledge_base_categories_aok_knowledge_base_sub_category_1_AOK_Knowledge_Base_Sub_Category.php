<?php
// created: 2018-04-18 21:18:12
$dictionary["AOK_Knowledge_Base_Sub_Category"]["fields"]["aok_knowledge_base_categories_aok_knowledge_base_sub_category_1"] = array (
  'name' => 'aok_knowledge_base_categories_aok_knowledge_base_sub_category_1',
  'type' => 'link',
  'relationship' => 'aok_knowledge_base_categories_aok_knowledge_base_sub_category_1',
  'source' => 'non-db',
  'module' => 'AOK_Knowledge_Base_Categories',
  'bean_name' => 'AOK_Knowledge_Base_Categories',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_CATEGORIES_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_1_FROM_AOK_KNOWLEDGE_BASE_CATEGORIES_TITLE',
  'id_name' => 'aok_knowlee694egories_ida',
);
$dictionary["AOK_Knowledge_Base_Sub_Category"]["fields"]["aok_knowle529fgory_1_name"] = array (
  'name' => 'aok_knowle529fgory_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_CATEGORIES_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_1_FROM_AOK_KNOWLEDGE_BASE_CATEGORIES_TITLE',
  'save' => true,
  'id_name' => 'aok_knowlee694egories_ida',
  'link' => 'aok_knowledge_base_categories_aok_knowledge_base_sub_category_1',
  'table' => 'aok_knowledge_base_categories',
  'module' => 'AOK_Knowledge_Base_Categories',
  'rname' => 'name',
);
$dictionary["AOK_Knowledge_Base_Sub_Category"]["fields"]["aok_knowlee694egories_ida"] = array (
  'name' => 'aok_knowlee694egories_ida',
  'type' => 'link',
  'relationship' => 'aok_knowledge_base_categories_aok_knowledge_base_sub_category_1',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_AOK_KNOWLEDGE_BASE_CATEGORIES_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_1_FROM_AOK_KNOWLEDGE_BASE_SUB_CATEGORY_TITLE',
);
