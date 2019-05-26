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
class Quarters extends Date 
{
    /**
     * Returns the list of quarters
     * 
     * @access public
     * @return array
     */
    public function getCaptionsOrder() 
    {
        return array(
            'Q1', 
            'Q2', 
            'Q3', 
            'Q4',
        );
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
        $quarters   = $this->getCaptionsOrder();
        
        foreach ($quarters as $key => $quarter_name) {
            $result[] = array(                
                'additionalWhere' => array(
                    'QUARTER(' .$field_name. ') = '. ($key+1)
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
            'CONCAT("Q", QUARTER('. $field_name .'), " ", YEAR('. $field_name .')) AS '. $grouping->getQueriedName($level),
            'YEAR('. $field_name .') as '. $this->getYearFieldname($level),
            'MONTH('. $field_name .') as '. $this->getMonthFieldname($level),
            'QUARTER('. $field_name .') as '. $this->getQuarterFieldname($level),
        ));
        
        return 'QUARTER('. $field_name .'), YEAR('. $field_name .')';
    }
}
