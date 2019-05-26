<?php
namespace Reports\Data\Grouping;

/**
 * Stores the rules an common methods for grouping functions
 * 
 * TODO: Multilang Captions. Using  "SET @@lc_time_names='ru_RU';"  for MySQL
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Data.Grouping
 */
abstract class Date 
{
    /**
     * Returns the list of ordered captions regarding type of grouping
     * 
     * @access public
     * @return array
     */
    public abstract function getCaptionsOrder();
    
    /**
     * This method returns a list of categories for current grouping function.
     * 
     * The returned data will include conversions parts for SQL text according to field of grouping.
     * 
     * @access public
     * @param integer $index  The index of grouping
     * @return array
     */
    public abstract function getCategories($index);
    
    /**
     * Returns SQL conversion for grouping by Function
     * 
     * @param integer $level    The level for grouping
     * @param \Reports\Data\Criterion  $criterion       The link to Criterion object - for SQL conversion
     * @return string
     */
    public abstract function getGroupingConversion($level, \Reports\Data\Criterion &$criterion);
    
    /**
     * Returns the list of years from built dataset of Chart
     *  
     * @return array
     * @param \Reports\Chart\Basic $chart_object  The link to object of chart
     **/
    public function getYearsFromChartDataset(\Reports\Chart\Basic &$chart_object) 
    {
        $chart_group_by = $chart_object->getGroupBy();
        $order_by       = $chart_group_by[0];
        $years          = array();
        
        foreach ($chart_object->getDataset() as $content) {
            $years[substr($content[$order_by], -4)] = '';
        }
        
        $years = array_keys($years);
        sort($years);
        
        return $years;
    }
    
    /**
     * Return the value of the year from row according to grouped field
     * 
     * @access public
     * @param int level The level of the grouping
     * @param array row The data for row from DB
     * @return int
     */
    public function getYearValue($level, array $row) 
    {
        $year = null;
        $year_fieldname = $this->getYearFieldname($level);
        
        if (isset($row[$year_fieldname])) {
            $year = $row[$year_fieldname];
        }
        
        return (int)$year;
    }
    
    /**
     * Return the value of the Quarter from row according to grouped field
     * 
     * @access public
     * @param int level The level of the grouping
     * @param array row The data for row from DB
     * @return int
     */
    public function getQuarterValue($level, array $row) 
    {
        $quarter = null;
        $quarter_fieldname = $this->getQuarterFieldname($level);
        
        if (isset($row[$quarter_fieldname])) {
            $quarter = $row[$quarter_fieldname];
        }
        
        return (int)$quarter;
    }

    public function getDaysValue($level, array $row)
    {
        $quarter = null;
        $quarter_fieldname = $this->getDaysFieldname($level);

        if (isset($row[$quarter_fieldname])) {
            $quarter = $row[$quarter_fieldname];
        }

        return (int)$quarter;
    }

    public function getWeeksValue($level, array $row)
    {
        $quarter = null;
        $quarter_fieldname = $this->getWeeksFieldname($level);

        if (isset($row[$quarter_fieldname])) {
            $quarter = $row[$quarter_fieldname];
        }

        return (int)$quarter;
    }
    
    /**
     * Return the value of the Month from row according to grouped field
     * 
     * @access public
     * @param int level The level of the grouping
     * @param array row The data for row from DB
     * @return string
     */
    public function getMonthValue($level, array $row) 
    {
        $month = null;
        $month_fieldname = $this->getMonthFieldname($level);
        
        if (isset($row[$month_fieldname])) {
            $month = $row[$month_fieldname];
        }
        
        return $month;
    }

    /**
     * Returns the name of field for year definition according to grouping field
     * 
     * @access public
     * @param level The level of grouping
     * @return string
     */
    public function getYearFieldname($level) 
    {
        $grouping = \Reports\Data\Grouping::load();
        return 'year_'. $grouping->getQueriedName($level) .'___level'. $level;
    }

    /**
     * Returns the name of field for MOnth definition according to grouping field
     * 
     * @access public
     * @param level The level of grouping
     * @return string
     */
    public function getMonthFieldname($level) 
    {
        $grouping = \Reports\Data\Grouping::load();
        return 'month_'. $grouping->getQueriedName($level) .'___level'. $level;
    }

    /**
     * Returns the name of field for Hour definition according to grouping field
     * 
     * @access public
     * @param level The level of grouping
     * @return string
     */
    public function getHourFieldname($level) 
    {
        $grouping = \Reports\Data\Grouping::load();
        return 'hour_'. $grouping->getQueriedName($level) .'___level'. $level;
    }

    /**
     * Returns the name of field for Quarter definition according to grouping field
     * 
     * @access public
     * @param level The level of grouping
     * @return string
     */
    public function getQuarterFieldname($level) 
    {
        $grouping = \Reports\Data\Grouping::load();
        return 'quarter_'. $grouping->getQueriedName($level) .'___level'. $level;
    }



    public function getDaysFieldname($level)
    {
        $grouping = \Reports\Data\Grouping::load();
        return 'days_'. $grouping->getQueriedName($level) .'___level'. $level;
    }

    public function getWeeksFieldname($level)
    {
        $grouping = \Reports\Data\Grouping::load();
        return 'weeks_'. $grouping->getQueriedName($level) .'___level'. $level;
    }
}
