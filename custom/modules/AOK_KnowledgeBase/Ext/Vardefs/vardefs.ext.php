<?php 
 //WARNING: The contents of this file are auto-generated


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


 // created: 2017-12-13 17:21:54
$dictionary['AOK_KnowledgeBase']['fields']['created_from_c']['inline_edit']=1;
$dictionary['AOK_KnowledgeBase']['fields']['created_from_c']['duplicate_merge_dom_value']=0;

 

$dictionary["AOK_KnowledgeBase"]["fields"]["video_link"] =array (
	'name' => 'video_link',
	'default_value' => '',
	'required' => false,
	'vname' => 'LBL_VIDEO_LINK',
	'type' => 'url',
	'massupdate' => 0,
	'max_size' => 255,
	'importable' => true,
	'audited' => false,
	'reportable' => true,

);



 // created: 2018-04-18 11:01:14
$dictionary['AOK_KnowledgeBase']['fields']['kb_image_c']['inline_edit']='1';
$dictionary['AOK_KnowledgeBase']['fields']['kb_image_c']['labelValue']='Image';

 
?>