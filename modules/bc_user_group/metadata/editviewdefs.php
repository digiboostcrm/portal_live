<?php
$module_name = 'bc_user_group';
$viewdefs [$module_name] = 
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
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 
          array (
            'customCode' => '<input type="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" name="button" value="Save" id="SAVE_HEADER" 
onclick="return portal_accessible(\'EditView\');" class="button" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}" title="{$APP.LBL_SAVE_BUTTON_TITLE}"/>',
          ),
          1 => 'CANCEL',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/bc_user_group/js/check_portal_accessible_group.js',
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
            'name' => 'is_portal_accessible_group',
            'studio' => 'visible',
            'label' => 'LBL_IS_PORTAL_ACCESSIBLE_GROUP',
          ),
          1 => '',
        ),
        2 => 
        array (
          0 => 'description',
          1 => '',
        ),
        3 => 
        array (
          0 => '',
          ),
        ),
      ),
    ),
);
?>
