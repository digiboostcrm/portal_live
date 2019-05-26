<?php
namespace Reports\Chart;

/**
 * This class is intended for factory for the Charts
 * 
 * @access public
 * @author Richlode Solutions
 * @package Chart
 */
class Factory
{
    /**
     * Loading and returning an instance of current type of Spreadsheet
     * 
     * @access public
     * @param string type
     * @ParamType type string
     */
    public function loadChart($type) 
    {
        if (!$type) {
            $type = 'Columns';
        }
        $classname = '\Reports\Chart\\'.$type;
        $chart = new $classname();
        
        return $chart;
    }

    /**
     * Loading and returning an instance of extention for Drilldown grouping.
     * 
     * @access public
     * @param string type
     * @ParamType type string
     */
    public function loadDrilldown($type) 
    {
        $classname = '\Reports\Chart\Drilldown\\'.$type;
        $chart = new $classname();
        
        return $chart;
    }
}
