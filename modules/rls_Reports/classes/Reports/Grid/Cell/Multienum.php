<?php
namespace Reports\Grid\Cell;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Grid.Cell
 */
class Multienum extends Basic {

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

        $data = explode('^,^', $text);
        
        $html = '<ul type="disc" style="margin: 0 0 0 0px;padding: 0 0 0 5px;">';
        foreach ($data as $element) {
            $element = str_replace('^', '', $element);
            $label = '';
            if (
                isset($column_settings['vardefs']['options'])
                && isset($app_list_strings[$column_settings['vardefs']['options']])
                && isset($app_list_strings[$column_settings['vardefs']['options']][$element]) // TO_DANIL fix for deleted values of dropdown Bug #1765
            ) {
                  $label = $app_list_strings[$column_settings['vardefs']['options']][$element];
            }

            if (
                isset($column_settings['vardefs']['options'])
                && isset($app_list_strings[$column_settings['vardefs']['options']])
                && empty($element)
            ) {
                // fix Bug #1799
                //$label = 'LABEL DOES NOT EXISTS';
                // fix Bug #2649
                // TODO: change template to "No <fieldname> in <module_name>"
                $label = 'No Value';
            }
            $html .= '<li style="list-style-type: disc;">' . $label . '</li>';
        }
        $html .= '</ul>';
        
        return '<td>'.$html.'</td>';
    }
}
?>
