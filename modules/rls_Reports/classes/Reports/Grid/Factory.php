<?php
namespace Reports\Grid;

/**
 * This class is intended for factory of type of the Spreadsheets
 * 
 * @access public
 * @author Richlode Solutions
 * @package Grid
 */
class Factory 
{
    /**
     * Loading and returning an instance of current type of Spreadsheet
     * 
     * @access public
     * @param string type The type of Spreadsheet
     */
    public function loadGrid($type)
    {
        $classname = '\Reports\Grid\\'.$type;
        $grid = new $classname();
        
        return $grid;        
    }
}