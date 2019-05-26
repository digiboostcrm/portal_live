<?php
namespace Reports\Chart\Drilldown;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Chart.Drilldown
 */
abstract class Basic 
{

    /**
     * Generate search url for current grouping by date/user etc.
     * @access public
     * @param array $row data from querry
     * @param int groupIndex current index of grouping
     * @return string
     */
    public abstract function getReplaceUrl(array $row, $groupIndex);
}

