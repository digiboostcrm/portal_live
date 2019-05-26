<?php
namespace Reports\Data\Grouping;

/**
 * This class is intended to store extra original functionality
 *   for Quarters function of grouping
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Data.Grouping
 */
class Weeks extends Date 
{
    /**
     * Returns the list of quarters
     * 
     * @access public
     * @return array
     */
    public function getCaptionsOrder() 
    {
        $result=array();
        for ($i = 0; $i <= 52; $i++){
            $result[$i]=$i+1;
        }
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see classes/Reports/Data/Grouping/Reports\Data\Grouping.Basic::getCategories()
     */
    public function getCategories($index)
    {
        global $current_user;
        $week_start = $current_user->getPreference('fdow');
        if($week_start != '1'){
            $week_start = '0';
        }
        $result         = array();
        $grouping       = \Reports\Data\Grouping::load();
        $field_name     = $grouping->getFieldname($index);
        $weeks   = $this->getCaptionsOrder();
        foreach ($weeks as $key => $week_num) {
            $result[] = array(                
                'additionalWhere' => array(
                    'WEEK(' .$field_name. ',\''.$week_start.'\') = '. ($week_num)
                ),
            );
        }
        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see classes/Reports/Data/Grouping/Reports\Data\Grouping.Basic::getGroupingConversion()
     */
    public function getGroupingConversion($level, \Reports\Data\Criterion &$criterion) 
    {
        global $current_user;
        $week_start = $current_user->getPreference('fdow');
        if($week_start != '1'){
            $week_start = '0';
        }
        $grouping   = \Reports\Data\Grouping::load();
        $field_name = $grouping->getFieldname($level);
        
        $criterion->setAdditionalFields(array(
            'CONCAT("Week ", WEEK('. $field_name .',\''.$week_start.'\'), " ", YEAR('. $field_name .')) AS '. $grouping->getQueriedName($level),
            'YEAR('. $field_name .') as '. $this->getYearFieldname($level),
            'MONTH('. $field_name .') as '. $this->getMonthFieldname($level),
            'QUARTER('. $field_name .') as '. $this->getQuarterFieldname($level),
            'WEEK('. $field_name .') as '. $this->getWeeksFieldname($level),
        ));
        
        return 'WEEK('. $field_name .'), YEAR('. $field_name .')';
    }
}
