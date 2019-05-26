<?php
$viewdefs ['Cases'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
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
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'include/javascript/bindWithDelay.js',
        ),
        1 => 
        array (
          'file' => 'modules/AOK_KnowledgeBase/AOK_KnowledgeBase_SuggestionBox.js',
        ),
        2 => 
        array (
          'file' => 'include/javascript/qtip/jquery.qtip.min.js',
        ),
        3 => 
        array (
          'file' => 'custom/modules/Cases/js/validation_state.js',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CASE_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'form' => 
      array (
        'enctype' => 'multipart/form-data',
		'buttons' => 
        array (
          0 => array('customCode' => '<input title="Save" accesskey="a" class="button primary" onclick="var _form = document.getElementById(\'EditView\'); _form.action.value=\'Save\'; if( check_form(\'EditView\'),  check_state(\'{$fields.start_date.value}\' , \'{$fields.end_date.value}\' , \'{$fields.id.value}\'))SUGAR.ajaxUI.submitForm(_form);return false;" type="submit" name="button" value="Save" id="SAVE">'),
          1 => 'CANCEL',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'lbl_case_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
			'name' => 'case_number',
            'type' => 'readonly',

            array (
            ),
          ),
          1 => 'account_name',
        ), 

        1 => 
        array (
          0 => 
          array (
            'name' => 'subject',
          ),
          1 => 'priority',
        ),
        2 => 
        array (
          0 => 
          array (
			'name' => 'category',
            'label' => 'LBL_CATEGORY',
          ),
          1 => 'status',
        ),
        3 => 
        array (
          0 => 
			  array (
				'name' => 'start_date',
				'studio' => true,
				'label' => 'LBL_START_DATE',
			  ),
          1 => 
			  array (
				'name' => 'end_date',
				'studio' => true,
				'label' => 'LBL_DUE_DATE',
			  ),
		  
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'description',
          ),
        ), 
        5 => 
        array (
		  0 => 
          array (
            'name' => 'custom_attachment_c',
            'label' => 'LBL_CUSTOM_ATTACHMENT',
          ),
        ),
		6 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
          ),
          1 => 
          array (
            'name' => 'created_by_name',
          ),
        ), 
		7 => 
        array (
          0 => 
          array (
            'name' => 'spent_hours',
          ),
          1 => 
          array (
            'name' => 'total_spent_minuts',
          ),
        ),
		8 => 
        array (
          0 => 
          array (
            'name' => 'time_category',
          ),
        ), 

      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'internal',
            'studio' => 'visible',
            'label' => 'LBL_INTERNAL',
          ),

        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'update_text',
            'studio' => 'visible',
            'label' => 'LBL_UPDATE_TEXT',
          ),
        ),
        2 => 
        array (
		  0 => 
          array (
            'name' => 'update_attachment_c',
            'label' => 'LBL_UPDATE_ATTACHMENT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'aop_case_updates_threaded',
            'studio' => 'visible',
            'label' => 'LBL_AOP_CASE_UPDATES_THREADED',
          ),
        ),
      ),
	  /*
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'resolution',
            'nl2br' => true,
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'start_date',
            'studio' => true,
            'label' => 'LBL_START_DATE',
          ),
          1 => 
          array (
            'name' => 'end_date',
            'studio' => true,
            'label' => 'LBL_DUE_DATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'spent_hours',
          ),
          1 => '',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'chat_description_c',
            'studio' => 'visible',
            'label' => 'LBL_CHAT_DESCRIPTION_C',
          ),
        ),
      ),
	  */
    ),
  ),
);
;
?>
