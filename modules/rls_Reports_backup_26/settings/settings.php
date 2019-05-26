<?php

return array(
    'name'  => $module_name,
    'label' => $GLOBALS['app_list_strings']['moduleList'][$module_name],

    // Chart settings
    'chart' => array(
        //'drilldown' => true,
        'colors' => array(
            "#FF6600", "#FCD202", "#B0DE09", "#0D8ECF", "#2A0CD0", "#CD0D74", "#CC0000", 
            "#00CC00", "#0000CC",/* "#2A0CD0", "#8A0CCF", "#CD0D74", "#754DEB", "#DDDDDD", 
            "#999999", "#333333", "#000000", "#57032A", "#CA9726", "#990000", "#4B0C25" */
        ),
        'datastore' => array(
            'summariesIndex' => 0,
        ),
    ),

    // Data settings
    'data' => array(
        'module_of_report' => $module_name,
        'summaries' => array(
            array(
                'function'  => 'COUNT',
                'module_of_field' => $module_name,
                'field_name' => '*'
            )
        ),
        'criterion' => array(
          'select' => '
              SELECT '.$module->table_name.'.id 
          ',
          'from' => $module->table_name,
          'where' => $module->table_name.'.deleted = "0" ',
          'group' => '',
          'order' => '',              
        ),
        'grouping' => array(
            array(
               'is_default_grouping'=>'true',
               'label' => 'byUsers',
               'optionName' => 'byUsers',
               'fieldList' => array(
                   array(
                      'module_of_field' => 'Users',
                      'field_name' => 'user_name',
                      'reletion_name' => 'assigned_user_link',
                      'is_default_grouping'=>'true'
                   ),
               ),
            ),
        ),
    ),
    
);
