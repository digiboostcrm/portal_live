<?php
namespace Reports\Chart\Drilldown;

/**
 * Class extention drilldown for grouping by date.
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Chart.Drilldown
 */
class Date extends Basic
{
    /**
     * Map of search fields on ListView.
     * 
     * @AttributeType array
     */
    private $periodIndexes = array(
        'between' => array(
            'dropdown' => 'date_closed_advanced_range_choice',
            'from' => 'start_range_date_closed_advanced',
            'to'   => 'end_range_date_closed_advanced'
        ),
    );

    /**
     * Generate search url for current grouping by date/user etc.
     * 
     * @param array row data from querry
     * @param int group_index index of current grouping
     * @return string
     */
    public function getReplaceUrl(array $row, $group_index)
    {
        $grouping  = \Reports\Data\Grouping::load();
        
        $function = 'get' . ucfirst(strtolower($grouping->getFunction($group_index))) . 'Period';
        $period = $this->$function($row, $group_index);
        
      
        $url = $this->periodIndexes['between']['dropdown'] . '=between' . '&' .
            $this->periodIndexes['between']['from']  . '=' . $period['from'] . '&' .
            $this->periodIndexes['between']['to']  . '='  . $period['to']
        ;
        return $url;
    }

    /** 
     * Get date period for month.

     * @param array row data from querry
     * @param int group_index index of current grouping
     * @return string
     */
    public function getMonthsPeriod(array $row, $group_index)
    {
        $grouping  = \Reports\Data\Grouping::load();
        $function_name = $grouping->getFunction($group_index);
        $object = $grouping->getFunctionObject($function_name);

        $month =  $object->getMonthValue($group_index, $row);
        $year = $object->getYearValue($group_index, $row);
        
        return array(
            'from' => $year . '-' . $month . '-01',
            'to' => $year . '-' . $month . '-31',
        );
    }

    /**
     * Get date period for quarter.
     * 
     * @param array row data from querry
     * @param int group_index index of current grouping
     * @return string
     */
    public function getQuartersPeriod(array $row, $group_index)
    {
        $grouping  = \Reports\Data\Grouping::load();
        $functionName = $grouping->getFunction($group_index);
        $object = $grouping->getFunctionObject($functionName);

        $quarter =  $object->getQuarterValue($group_index, $row);
        $year = $object->getYearValue($group_index, $row);
        
        return array(
            'from' => $year . '-' . ($quarter*3-2) . '-01',
            'to' => $year . '-' . ($quarter*3) . '-31',
        );
    }
}

