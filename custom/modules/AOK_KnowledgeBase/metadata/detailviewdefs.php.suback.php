<?php
$module_name = 'AOK_KnowledgeBase';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'revision',
            'label' => 'LBL_REVISION',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
            'customCode' => '{$fields.description.value}',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'additional_info',
            'comment' => 'Full text of the note',
            'studio' => 'visible',
            'label' => 'LBL_ADDITIONAL_INFO',
          ),
          1 => 
          array (
            'name' => 'video_link',
            'label' => 'LBL_VIDEO_LINK',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'author',
            'studio' => 'visible',
            'label' => 'LBL_AUTHOR',
          ),
          1 => 
          array (
            'name' => 'kb_image_c',
            'studio' => 'visible',
            'label' => 'LBL_KB_IMAGE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'approver',
            'studio' => 'visible',
            'label' => 'LBL_APPROVER',
          ),
          1 => 
          array (
            'name' => 'aok_knowledge_base_sub_cat_aok_knowledgebase_1_name',
          ),
        ),
      ),
    ),
  ),
);
$viewdefs['AOK_KnowledgeBase']['DetailView']['templateMeta'] = array (
  'form' => 
  array (
    'buttons' => 
    array (
      0 => 'EDIT',
      1 => 'DUPLICATE',
      2 => 'DELETE',
      3 => 'FIND_DUPLICATES',
    ),
  ),
  'maxColumns' => '2',
  'widths' => 
  array (
    0 => 
    array (
      'label' => '10',
      'field' => '30',
    ),
    1 => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
  'useTabs' => false,
  'tabDefs' => 
  array (
    'DEFAULT' => 
    array (
      'newTab' => false,
      'panelDefault' => 'expanded',
    ),
  ),
  'syncDetailEditViews' => true,
);
?>
