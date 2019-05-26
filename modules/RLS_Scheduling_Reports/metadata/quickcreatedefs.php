<?php
$module_name = 'RLS_Scheduling_Reports';
$viewdefs [$module_name] = 
array (
  'QuickCreate' => 
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
          1 => 'assigned_user_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'last_run',
            'label' => 'LBL_LAST_RUN',
          ),
          1 => 
          array (
            'name' => 'next_run',
            'label' => 'LBL_NEXT_RUN',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'subscribe_active',
            'label' => 'LBL_SUBSCRIBE_ACTIVE',
          ),
          1 => 
          array (
            'name' => 'periodicity',
            'studio' => 'visible',
            'label' => 'LBL_PERIODICITY',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'single_launch',
            'label' => 'LBL_SINGLE_LAUNCH',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
?>
