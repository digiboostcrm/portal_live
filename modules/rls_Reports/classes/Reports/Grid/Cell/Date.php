<?php
namespace Reports\Grid\Cell;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Grid.Cell
 */
class Date extends Basic {

    /**
     * @access public
     * @param array_11 field_settings
     * @param data_from_db
     * @ParamType field_settings array
     * @ParamType data_from_db 
     */
    public function getCelHTML(array $column_settings, array $row_data) 
    {
        global $timedate;
        $date = $timedate->to_display_date($row_data[$column_settings['dataField']],false);
        return '<td>'.$date.'</td>';
    }
}
?>
