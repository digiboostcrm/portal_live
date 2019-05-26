<?php
namespace Reports\Grid\Cell;
/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Grid.Cell
 */
abstract class Basic {

    /**
     * Returns the html for different types of field
     * @access public
     * @param array_11 field_settings Contents field settings for display cell
     * @param array_11 data_from_db Content retrieved from the database
     * @return string
     * @ParamType field_settings array
     * Contents field settings for display cell
     * @ParamType data_from_db array
     * Content retrieved from the database
     * @ReturnType string
     */
    public abstract function getCelHTML(array $field_settings, array $data_from_db);
}
?>
