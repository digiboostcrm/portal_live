<?php
namespace Reports\Data\Grouping;

/**
 * This class is intended to store extra original functionality
 *   for Months function of grouping
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Data.Grouping
 */
class Months extends Date 
{
    /**
     * Returns the list of months
     * 
     * @access public
     * @return array
     */
    public function getCaptionsOrder() 
    {
        $database  = \DBManagerFactory::getInstance();
        $result    = $database->fetchByAssoc($database->query("
            select CONCAT(
              MONTHNAME('2000-01-01'), '.',
              MONTHNAME('2000-02-01'), '.',
              MONTHNAME('2000-03-01'), '.',
              MONTHNAME('2000-04-01'), '.',
              MONTHNAME('2000-05-01'), '.',
              MONTHNAME('2000-06-01'), '.',
              MONTHNAME('2000-07-01'), '.',
              MONTHNAME('2000-08-01'), '.',
              MONTHNAME('2000-09-01'), '.',
              MONTHNAME('2000-10-01'), '.',
              MONTHNAME('2000-11-01'), '.',
              MONTHNAME('2000-12-01')
            ) as months_list
        "));
        
        return explode('.', $result['months_list']);
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
        $monthes_list   = $this->getCaptionsOrder();
        
        foreach ($monthes_list as $key => $month_name) {
            $result[] = array(
                'additionalWhere' => array(
                    'MONTH(' .$field_name. ') = ' . ($key+1),
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
            'CONCAT(MONTHNAME('. $field_name .'), " ", YEAR('. $field_name .')) AS '. $grouping->getQueriedName($level),
            'YEAR('. $field_name .') as '. $this->getYearFieldname($level),
            'MONTH('. $field_name .') as '. $this->getMonthFieldname($level),
            'QUARTER('. $field_name .') as '. $this->getQuarterFieldname($level),
        ));
        
        return 'MONTHNAME('. $field_name .'), YEAR('. $field_name .')';
    }
    

}
