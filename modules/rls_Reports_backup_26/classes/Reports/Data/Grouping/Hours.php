<?php
namespace Reports\Data\Grouping;

use Reports\Data\Criterion;

/**
 * This class is intended to store extra original functionality
 *   for Hours function of grouping
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Data.Grouping
 */
class Hours extends Date 
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
            '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', 
            '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', 
            '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', 
            '18:00', '19:00', '20:00', '21:00', '22:00', '23:00', 
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
                    'HOUR(' .$field_name. ') = '. $key
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
            'DATE_FORMAT('. $field_name .' - '. Criterion::getUserOffsetByTimezone() .', "%H:00, %h %p") AS '. $grouping->getQueriedName($level),
            'HOUR('. $field_name .') as '. $this->getHourFieldname($level),
            'YEAR('. $field_name .') as '. $this->getYearFieldname($level),
            'MONTH('. $field_name .') as '. $this->getMonthFieldname($level),
            'QUARTER('. $field_name .') as '. $this->getQuarterFieldname($level),
        ));
        
        return 'HOUR('. $field_name .'), YEAR('. $field_name .')';
    }
}
