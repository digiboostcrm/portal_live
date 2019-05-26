<?php
$module_name = 'UT_RightSignature';
$viewdefs [$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'enctype' => 'multipart/form-data',
        'hidden' => 
        array (
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
      'javascript' => '{sugar_getscript file="include/javascript/popup_parent_helper.js"}
	{sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
	{sugar_getscript file="modules/Documents/documents.js"}',
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
          0 => 'document_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'sending_type',
            'studio' => 'visible',
            'label' => 'LBL_SENDING_TYPE',
          ),
          1 => 
          array (
            'name' => 'rightsignature_templates',
            'studio' => 'visible',
            'label' => 'LBL_RIGHTSIGNATURE_TEMPLATES',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'rs_doc_id',
            'label' => 'LBL_RS_DOC_ID',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'subject',
            'label' => 'LBL_SUBJECT',
          ),
          1 => 
          array (
            'name' => 'message',
            'studio' => 'visible',
            'label' => 'LBL_MESSAGE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'state',
            'studio' => 'visible',
            'label' => 'LBL_STATE',
          ),
          1 => 
          array (
            'name' => 'processing_state',
            'studio' => 'visible',
            'label' => 'LBL_PROCESSING_STATE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'rs_created_at',
            'label' => 'LBL_RS_CREATED_AT',
          ),
          1 => 
          array (
            'name' => 'rs_completed_at',
            'label' => 'LBL_RS_COMPLETED_AT',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'rs_expires_on',
            'label' => 'LBL_RS_EXPIRES_ON',
          ),
          1 => '',
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'original_file',
            'studio' => 'visible',
            'label' => 'LBL_ORIGINAL_FILE',
          ),
          1 => 
          array (
            'name' => 'signed_file',
            'studio' => 'visible',
            'label' => 'LBL_SIGNED_FILE',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'original_url',
            'label' => 'LBL_ORIGINAL_URL',
          ),
          1 => 
          array (
            'name' => 'pdf_url',
            'label' => 'LBL_PDF_URL',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'signed_pdf_url',
            'label' => 'LBL_SIGNED_PDF_URL',
          ),
          1 => '',
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'thumbnail_url',
            'label' => 'LBL_THUMBNAIL_URL',
          ),
          1 => 
          array (
            'name' => 'size',
            'label' => 'LBL_SIZE',
          ),
        ),
        11 => 
        array (
          0 => 'category_id',
          1 => 'subcategory_id',
        ),
        12 => 
        array (
          0 => 'assigned_user_name',
        ),
        13 => 
        array (
          0 => 'active_date',
          1 => 'exp_date',
        ),
        14 => 
        array (
          0 => 'status_id',
        ),
        15 => 
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ),
      ),
    ),
  ),
);
?>
