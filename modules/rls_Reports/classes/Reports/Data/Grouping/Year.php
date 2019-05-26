<?php
namespace Reports\Data\Grouping;

/**
 * This class is intended to store extra original functionality
 *   for Year function of grouping
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Data.Grouping
 */
class Year extends Date 
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
        for ($i = 0; $i <= 100; $i++){
             $result[$i]=$i+2000;
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
        $year   = $this->getCaptionsOrder();
        
        foreach ($year as $key => $year_number) {
            $result[] = array(                
                'additionalWhere' => array(
                    'YEAR(' .$field_name. ') = '. $year_number
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
        $grouping   = \Reports\Data\Grouping::load();
        $field_name = $grouping->getFieldname($level);
        
        $criterion->setAdditionalFields(array(
            //'CONCAT(YEAR('. $field_name .'), " ", YEAR('. $field_name .')) AS '. $grouping->getQueriedName($level),
            'CONCAT(YEAR('. $field_name .'), " ", YEAR('. $field_name .')) AS '. $grouping->getQueriedName($level),
            'YEAR('. $field_name .') as '. $this->getYearFieldname($level),
            'MONTH('. $field_name .') as '. $this->getMonthFieldname($level),
            'QUARTER('. $field_name .') as '. $this->getQuarterFieldname($level),
        ));
        
        return 'YEAR('. $field_name .'), YEAR('. $field_name .')';
         //return 'YEAR('. $field_name .')';
    }
}
