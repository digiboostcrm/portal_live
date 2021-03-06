<?php
$viewdefs ['Cases'] = 
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
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_CASE_INFORMATION' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_AOP_CASE_UPDATES' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_CHAT_DESCRIPTION_C' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_case_information' => 
      array (
	  8 => 
        array (
          0 => 
          array (
            'name' => 'custom_name',
            'customCode' => '<input type="button" onclick = "assign_user(\'{$fields.id.value}\',\'assign\');" value="Assign To Me" title="Assign To Me" name="button"><input type="button" value="Un Assign" onclick="assign_user(\'{$fields.id.value}\',\'un_assighn\');" title="Un Assign" name="button">',
          
          ),
          1 => '',
        ),
        0 => 
        array (
          0 => 
          array (
            'name' => 'case_number',
            'label' => 'LBL_CASE_NUMBER',
          ),
          1 => 'priority',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'state',
            'comment' => 'The state of the case (i.e. open/closed)',
            'label' => 'LBL_STATE',
          ),
          1 => 'status',
        ),
        2 => 
        array (
          0 => 'queue',
          1 => 'account_name',
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_SUBJECT',
          ),
        ),
        4 => 
        array (
          0 => 'description',
        ),
        5 => 
        array (
          0 => 'resolution',
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
		  1 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED_BY_NAME',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'label' => 'LBL_DATE_MODIFIED',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
          ),
        ),
      ),
      'LBL_AOP_CASE_UPDATES' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'aop_case_updates_threaded',
            'studio' => 'visible',
            'label' => 'LBL_AOP_CASE_UPDATES_THREADED',
          ),
          1 => 
          array (
            'name' => 'update_attachment_c',
            'label' => 'LBL_UPDATE_ATTACHMENT',
          ),
        ),
      ),
      'LBL_CHAT_DESCRIPTION_C' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'chat_description_c',
            'label' => 'LBL_CHAT_DESCRIPTION_C',
            'readonly' => true,
            'inline_edit' => false,
          ),
        ),
      ),
    ),
  ),
);
$viewdefs['Cases']['DetailView']['templateMeta'] = array (
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
  'useTabs' => true,
  'tabDefs' => 
  array (
    'LBL_CASE_INFORMATION' => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
    ),
    'LBL_AOP_CASE_UPDATES' => 
    array (
      'newTab' => false,
      'panelDefault' => 'expanded',
    ),
  ),
);
?>
