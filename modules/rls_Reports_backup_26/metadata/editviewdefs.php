<?php
$module_name = 'rls_Reports';
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
      'includes' => 
      array (
        array(
          'file' => 'modules/rls_Reports/js/EditView.js'
        ),
      ),
      'useTabs' => true,
    ),
    'panels' => 
    array (

      'lbl_wizard' => 
      array (
        array (
          array (
            'name' => 'wizard',
            'hideLabel' => true,
          ),
        ),
      ),
      'lbl_chart_options' => 
      array (
        array (
          array (
            'name' => 'chart_type',
            'label' => 'LBL_CHART_TYPE',
          )
        ),
      ),
      'lbl_details' => 
      array (
        array (
          'name',
          'assigned_user_name',
        ),
        array (
          'description',
        ),
      ),
    ),
  ),
);
?>
