<?php
$module_name = 'RLS_Scheduling_Reports';
$searchdefs [$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'subscribe_active' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_SUBSCRIBE_ACTIVE',
        'width' => '10%',
        'name' => 'subscribe_active',
      ),
      'periodicity' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_PERIODICITY',
        'width' => '10%',
        'name' => 'periodicity',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'last_run' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_LAST_RUN',
        'width' => '10%',
        'default' => true,
        'name' => 'last_run',
      ),
      'next_run' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_NEXT_RUN',
        'width' => '10%',
        'default' => true,
        'name' => 'next_run',
      ),
      'subscribe_active' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_SUBSCRIBE_ACTIVE',
        'width' => '10%',
        'name' => 'subscribe_active',
      ),
      'single_launch' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_SINGLE_LAUNCH',
        'width' => '10%',
        'name' => 'single_launch',
      ),
      'periodicity' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_PERIODICITY',
        'width' => '10%',
        'name' => 'periodicity',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>
