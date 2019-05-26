<?php
namespace Reports\Grid\Cell;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Grid.Cell
 */
class Dropdawn extends Basic {

    /**
     * @access public
     * @param array_11 field_settings
     * @param data_from_db
     * @ParamType field_settings array
     * @ParamType data_from_db 
     */
    public function getCelHTML(array $column_settings, array $row_data) 
    {
        global $app_list_strings;
        
        $text = $row_data[$column_settings['dataField']];
        if (!$text) {
            return '<td></td>';
        }
        
        if (
            isset($column_settings['vardefs']['options'])
            && isset($app_list_strings[$column_settings['vardefs']['options']])
            && isset($app_list_strings[$column_settings['vardefs']['options']][$text]) // TO_DANIL fix for deleted values of dropdown Bug #1765
        ) {
            $text = $app_list_strings[$column_settings['vardefs']['options']][$text];
        }

        if (
            isset($column_settings['vardefs']['options'])
            && isset($app_list_strings[$column_settings['vardefs']['options']])
            && empty($text)
        ) {
            // fix Bug #1799
            //$text = 'LABEL DOES NOT EXISTS';
            // fix Bug #2649
            // TODO: change template to "No <fieldname> in <module_name>"
            $text = 'No Value';
        }
        
        return '<td>'.$text.'</td>';
    }
}
?>
