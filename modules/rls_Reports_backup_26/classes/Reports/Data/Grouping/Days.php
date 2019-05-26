<?php
namespace Reports\Data\Grouping;

/**
 * This class is intended to store extra original functionality
 *   for Days function of grouping
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Data.Grouping
 */
class Days extends Date 
{
    /**
     * Returns the list of quarters
     * 
     * @access public
     * @return array
     */
    public function getCaptionsOrder() 
    {
        $result="";
        for($m = 1; $m<13; $m++){
            for ($i = 0; $i <= 30; $i++){
                $result[$m][$i]=$i+1;
            }
        }

             
        return $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see classes/Reports/Data/Grouping/Reports\Data\Grouping.Basic::getCategories()
     */
    public function getCategories($index)
    {
        $result         = array();
        $grouping       = \Reports\Data\Grouping::load();
        $field_name     = $grouping->getFieldname($index);
        $days   = $this->getCaptionsOrder();
        //exit('123');
        foreach ($days as $m_name => $day_list) {
            foreach($day_list as $day_index=>$day_val){
                $result[] = array(
                    'additionalWhere' => array(
                        'MONTH(' .$field_name. ') = ' . $m_name . ' AND DAYOFMONTH(' .$field_name. ') = '. $day_val
                    ),
                );

            }

        }
        return $result;
    }

    /**
     * (non-PHPdoc)
     * @see classes/Reports/Data/Grouping/Reports\Data\Grouping.Basic::getGroupingConversion()
     */
    public function getGroupingConversion($level, \Reports\Data\Criterion &$criterion) 
    {
        $grouping   = \Reports\Data\Grouping::load();
        $field_name = $grouping->getFieldname($level);
        
        $criterion->setAdditionalFields(array(
            'CONCAT(DAYOFMONTH('. $field_name .'), " ",  MONTH('. $field_name .'), " ",  YEAR('. $field_name .')) AS '. $grouping->getQueriedName($level),
            'YEAR('. $field_name .') as '. $this->getYearFieldname($level),
            'MONTH('. $field_name .') as '. $this->getMonthFieldname($level),
            'QUARTER('. $field_name .') as '. $this->getQuarterFieldname($level),
            'DAYOFMONTH('. $field_name .') as '. $this->getDaysFieldname($level),
        ));
        
        return 'DAYOFMONTH('. $field_name .'), MONTH('. $field_name .'), YEAR('. $field_name .')';
    }
}
