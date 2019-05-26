<?php
namespace Reports\Data;

/**
 * @access public
 * @author Richlode Solutions
 * @package Reports.Data
 */
class Grouping extends Operations
{
    /**
     * The prefix for concatenated field wich uses in grouping function
     * 
     * @var string
     */
    protected static $groupingPrefix = 'concatenated_';
    
    /**
     * @var Grouping
     */
    protected static $groupingInstance = null;
    
    /**
     * @var array
     */
    protected static $groupingSettings = array();
 
    /**
     * Singleton caller
     * 
     * @access public
     * @param array $grouping  Set for automatically setup of settings
     * @return Grouping
     */
    public static function load()
    {
        self::$groupingSettings = \Reports\Settings\Storage::getGrouping();        
        
        if (self::$groupingInstance instanceof self){
            return self::$groupingInstance;
        }
        
        self::$groupingInstance = new self();
        
        return self::$groupingInstance;
    }
    
    /**
     * Reset class settings to default
     * 
     * */
    public static function resetReport(){
        self::$groupingInstance = null;
    }


    /**
     * This method sort the list of values regarding field type.
     * 
     * The list of categories for grouping, which basically contains 
     *   keys (names that should be sorted with this method) and values (criteria for SQL conversion),
     *   should be sorted regarding type of grouping field. 
     * Basically, it's need for Funnel type of chart, for example, to represent staging of sales, tracking leads by source. 
     * 
     * TODO: This function must be deprecated, 
     *       because SugarChart class has an ability to sort sections of chart with accordance to order in dom lists. 
     * 
     * @param integer $level  The level of grouping.
     * @param array $categories_list  The list of generated categories by grouping
     * @return array
     */
    public function sortCategoriesList($level, array $categories_list) 
    {
        global $app_list_strings;
        
        $resorted_list     = array();
        $grouping_settings = $this->get($level);       
        $module_bean       = loadBean($grouping_settings['module_of_field']);
        $field_definition  = $module_bean->field_name_map[$grouping_settings['field_name']];

        if (isset($field_definition['options'])) { 
            foreach ($app_list_strings[$field_definition['options']] as $key=>$value) {
                if ($key
                    && isset($categories_list[$key])
                ) {
                    $resorted_list[$key] = $categories_list[$key];
                    unset($categories_list[$key]); // TO_DANIL fix for deleted values of dropdown Bug #1765
                }
            }
            
            // TO_DANIL fix for deleted values of dropdown Bug #1765
            foreach ($categories_list as $key=>$value) {
                $resorted_list[$key] = $categories_list[$key];
            }

            return $resorted_list;
        }
        
        return $categories_list;
    }
    
    /**
     * Returns an object from Reports\Data\Grouping package
     * 
     * (non-PHPdoc)
     * @see classes/Reports/Data/Reports\Data.Operations::getFunctionObject()
     */
    public function getFunctionObject($function_name) 
    {
        $class_name = '\Reports\Data\Grouping\\' . ucfirst(strtolower($function_name));
        return new $class_name();
    }
    
    /**
     * This fmethod will sort the datase collection for chart.
     * 
     * With accordance to grouping the dataset for Chart will be sorted.
     * For example if top grouping is selected by monthes, 
     *  the dataset will be sorted with accordance to chronological sequence.
     * 
     * This function is only for Chart dataset.
     * Could be useless for other purposes.
     * 
     * @param \Reports\Chart\Basic $chart_object  The link to object of chart
     * @return \Reports\Chart\Basic
     */
    public function sortChartDataset(\Reports\Chart\Basic &$chart_object) 
    {
        $chart_group_by = $chart_object->getGroupBy();
        $order_by       = $chart_group_by[0];
        $curr_dataset   = $chart_object->getDataset();
        $new_dataset    = array();
        
        // TODO: think over this logic
        //       need to apply an OOP pattern here
        if (($function = $this->getFunction(0))) {
            $function_obj   = $this->getFunctionObject($function);
            $years          = $function_obj->getYearsFromChartDataset($chart_object);
            $captions_order = $function_obj->getCaptionsOrder();            

            if($function == 'days'){
                foreach ($years as $year) {
                    foreach ($captions_order as $mounth_index=>$caption) {
                        foreach($caption as $days){
                            foreach ($curr_dataset as $key=>$content) {
                                if ($content[$order_by] == $days.' ' . $mounth_index . ' ' .$year) {
                                    $new_dataset[] = $content;
                                    unset($curr_dataset[$key]);
                                }
                            }

                        }
                    }
                }
            } else {
                foreach ($years as $year) {
                    foreach ($captions_order as $caption) {
                        foreach ($curr_dataset as $key=>$content) {
                            if($function == 'weeks'){
                                if ($content[$order_by] == 'Week ' . $caption.' '.$year) {
                                    $new_dataset[] = $content;
                                    unset($curr_dataset[$key]);
                                }
                            }else {
                                if ($content[$order_by] == $caption.' '.$year) {
                                    $new_dataset[] = $content;
                                    unset($curr_dataset[$key]);
                                }
                            }


                            if ($function == 'hours') {
                                if (substr($content[$order_by], 0, strpos($content[$order_by], ',')) == $caption) {
                                    $new_dataset[] = $content;
                                    unset($curr_dataset[$key]);
                                }
                            }
                        }
                    }
                }
            }



            
            $chart_object->setDataset($new_dataset);
        }
        // TODO: think over this logic
        //       need to apply an OOP pattern here, for above
        
        
        return $chart_object;
    }
    
    /**
     * Sort dataset for Grouped spreadsheet.
     * 
     * This original algorith only for Grouped Spreadsheet. 
     * Could be useless for other purposes
     * 
     * @param array $dataset    The link to dataset for spreadsheet
     * @return array
     **/
    public function sortSpreadsheetDataset(array &$dataset)
    {
        if (!$function = $this->getFunction(0)) {
            return false;
        }
        
        if ($function == 'hours') {
            ksort($dataset);
            return true;
        }
        
        $years = array();
        $new_dataset  = array();
        $dataset_keys = array_keys($dataset);
        $function_obj = $this->getFunctionObject($function);
        
        
        // TODO: think over this logic
        //       need to apply an OOP pattern here
        foreach ($dataset_keys as $value) {
            $years[substr($value, -4)] = '';
        }
        $years = array_keys($years);
        sort($years);


        if($function == 'days'){
            foreach ($years as $year) {
                foreach ($function_obj->getCaptionsOrder() as $mounth_index=>$caption) {
                    foreach($caption as $index_c=>$value_c){
                        $key = $value_c . ' ' . $mounth_index .' '. $year;
                        if (isset($dataset[$key])){
                            $new_dataset[$key] = $dataset[$key];
                        }
                    }

                }
            }
        } else {
            foreach ($years as $year) {
                foreach ($function_obj->getCaptionsOrder() as $caption) {
                    if($function == 'weeks'){
                        $key = 'Week ' . $caption .' '. $year;
                    } else {
                        $key = $caption .' '. $year;
                    }

                    if (isset($dataset[$key])){
                        $new_dataset[$key] = $dataset[$key];
                    }
                }
            }
        }

        // TODO: think over this logic
        //       need to apply an OOP pattern here, for above
        
        
        $dataset = $new_dataset;
    }
    
    /**
     * Get title by value.
     * 
     * Options:
     *   1. If the grouping field is dropdown it will be retrieved from $app_list_strings.
     *   2. To be implemented ...
     * 
     * @param integer $level  The level of grouping.
     * @param string $value  Value for field
     * @return string retrived title 
     * */
    public function getTitleByValue($level, $value)
    {
        global $app_list_strings;
        
        $level_bean = $this->getBean($level);
        $field_definition  = $level_bean->field_name_map[$this->getFieldname($level, false)];
        
        if ($function = $this->getFunction($level)) {
            return $value;
        }

        if (
            isset($field_definition['options'])            
            && isset($app_list_strings[$field_definition['options']][$value])
        ) {
            $value = $app_list_strings[$field_definition['options']][$value];
        }
        
        // fix Bug #1799
        if (empty($value)) {
            //$value = 'LABEL DOES NOT EXISTS';
            // fix Bug #2649
            $value = 'No ' . $field_definition['name'] . ' in ' . $app_list_strings['moduleList'][$level_bean->module_name];
        }

        return $value;
    }
    
    /**
     * Return conversion string for SQL text for Year.
     * 
     * Using in system functions
     * 
     * @return string
     * @param integer $level  The level of grouping
     * @param array $row   The row of data from DB
     * */
    public function getCriteriaForYear($level, array $row) 
    {
        if ($function = $this->getFunction($level)) {
            $year = 
                $this
                    ->getFunctionObject($function)
                    ->getYearValue($level, $row);
            
            return array('YEAR('. $this->getFieldname($level) .') = "'. $year .'"');
        }
        
        return array();
    }
    
    /**
     * Returns the caption for group.
     * 
     * This caption could be dependent from function of grouping (quarters, monthes, weeks etc.)
     * 
     * @param integer $level  The level of grouping.
     * @param array $row  The data for current row of data from DB
     * @return string
     */
    public function getCaptionForGroup($level, array $row) 
    {
        $title = null;
        
        if ($this->getFunction($level)) {
            $title = stripcslashes($row[$this->getQueriedName($level)]);
        } else {
            $title = stripcslashes($this->getTitleByValue($level, $row[$this->getQueriedName($level)]));
        }

        // fix Bug #1799
        if (empty($title)) {
            $title = 'LABEL DOES NOT EXISTS';
        }
        
        return $title;
    }
}

