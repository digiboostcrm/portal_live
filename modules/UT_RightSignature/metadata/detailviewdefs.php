<?php
$module_name = 'UT_RightSignature';
$_object_name = 'ut_rightsignature';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'maxColumns' => '2',
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'DELETE',
          1 => 
          array (
            'customCode' => '<input title="{$MOD.LBL_UPDATE_STATUS_BUTTON}"   class="button"  onclick="update_rs_status(\'{$fields.id.value}\');" id="update_rs_status" name="update_rs_status"  value="{$MOD.LBL_UPDATE_STATUS_BUTTON}"  type="submit">',
          ),
        ),
      ),
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
      'includes' => 
      array (
          array(
            'file' => 'modules/UT_RightSignature/js/detailview.js'
            ),
      ),
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
            'customCode' => '{$sStateHTML}',
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
            //'label' => 'LBL_THUMBNAIL_URL',
            'customCode' => '{$sThumbNailURL}',
          ),
          1 => 
          array (
            'name' => 'size',
            'label' => 'LBL_SIZE',
          ),
        ),
        11 => 
        array (
          0 => 'assigned_user_name',
        ),
        12 => 
        array (
          0 => 'active_date',
          1 => NULL
        ),
        13 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
?>
