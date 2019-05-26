<?php
namespace Reports\Grid\Cell;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Grid.Cell
 */
class Checkbox extends Basic {

    /**
     * @access public
     * @param array field_settings
     * @param data_from_db
     * @ParamType field_settings array
     * @ParamType data_from_db 
     */
    public function getCelHTML(array $column_settings, array $row_data) 
    {
        $checked = '';
        if($row_data[$column_settings['dataField']]){
            $checked = 'checked="checked"';
        }
        return '<td style="text-align:center"><input type="checkbox" class="checkbox" disabled="true" '.$checked.'></td>';
    }
}
?>
