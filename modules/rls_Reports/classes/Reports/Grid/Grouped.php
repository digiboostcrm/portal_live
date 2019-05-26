<?php
namespace Reports\Grid;

/**
 * This class is intended for representation of Grouped type of spreadsheet.
 * 
 * 
 * @access public
 * @author Richlode Solutions
 * @package Grid
 */

class Grouped extends Sheet 
{
    /**
     * Containers list
     * 
     * @var array
     */
    private $containers = array();
    
    private $row_containers = array();
    
    /**
     * Return an array which contains a groups with grouping criteria values
     * 
     * @access public
     * @param integer $level  An integer value that specifies the level of grouping
     * @return array
     */
    public function getListOfGroups($level, $criteria = array())
    {
        $groups   = array();
        $grouping = \Reports\Data\Grouping::load();
        $store    = new \Reports\Data\Collection();

        $criterion = \Reports\Data\Criterion::getInstance()
            ->setSettings(
                \Reports\Settings\Storage::getSettings()
            )            
            ->buildCriteria();
        
        $table_cryterion = $criterion->getSettings();
            $_SESSION['name_currency_column'] = array();
            $_SESSION['name_summaried_field'] = array();
        foreach($table_cryterion['grid']['columns'] as $column_index=>$column_value){
            if(array_key_exists('type',$table_cryterion['grid']['columns'][$column_index])
                && $table_cryterion['grid']['columns'][$column_index]['type'] == 'currency'
            ){
                $_SESSION['name_currency_column'][] = $table_cryterion['grid']['columns'][$column_index]['dataField'];
            }
        }
        foreach($table_cryterion['data']['summaries'] as $summaries_index=>$summaries_value){
            if(array_key_exists('vardefs',$table_cryterion['data']['summaries'][$summaries_index])
                && array_key_exists('type',$table_cryterion['data']['summaries'][$summaries_index]['vardefs'])
                && $table_cryterion['data']['summaries'][$summaries_index]['vardefs']['type'] == 'currency'
            ){
                $_SESSION['name_summaried_field'][] = $table_cryterion['data']['summaries'][$summaries_index]['field_name'];
            }
        }
        $_SESSION['name_currency_column'] = array_unique($_SESSION['name_currency_column']);
        $_SESSION['name_summaried_field'] = array_unique($_SESSION['name_summaried_field']);

        if ($grouping_categories = $criterion->getCategoriesByGrouping($grouping, $level)) {
            foreach ($grouping_categories as $category_info) {
                $collection = $store->getRows(
                    $criterion
                        ->applySummaries()
                        ->setAdditionalGrouping(array($level))
                        ->setAdditionalWhere(array_merge($criteria, $category_info['additionalWhere']))
                );
                foreach ($collection as $row) {
                    $groups = array_merge(
                        $groups,
                        array(
                            $row[$grouping->getQueriedName($level)] => array(
                                'categoryInfo' => $category_info,
                                'row' => $row,
                            )
                        )
                    );
                }
            }
            
            if ($level == 0) {
                $grouping->sortSpreadsheetDataset($groups);
            }
        }

        return $groups;
    }

    /**
     * Return the list of data from DB.
     * 
     * @access public
     * @param array criteria Array with criteria for \Reports\Data\Criterion class
     * @return array
     */
    public function getDataForGroup(array $criteria)
    {
        $store     = new \Reports\Data\Collection();
        $criterion = \Reports\Data\Criterion::getInstance()
            ->setSettings(
                \Reports\Settings\Storage::getSettings()
            )
            ->applyDisplayFields()        
            ->buildCriteria()
            ->setAdditionalWhere($criteria);

        return $store->getRows($criterion); 
    }
    
    /**
     * Return the title for a group.
     * 
     * @access public
     * @param integer $level  The level of grouping
     * @param array $data  Array with data of group
     * @return array
     */
    public function getGroupTitle($level, array $data)
    {
        global $mod_strings;
        $title       = null;
        $grouping    = \Reports\Data\Grouping::load();
        $summarizing = \Reports\Data\Summarizing::load();        

        
        $title = (($function = $grouping->getFunction($level)) ? (isset($mod_strings['LBL_' . strtoupper($function)])
                                                                  ? $mod_strings['LBL_' . strtoupper($function)]
                                                                  : ucfirst($function)) . ': '
                                                                : null)
            . $grouping->getFieldLabel($level) .' = '
            . $grouping->getCaptionForGroup($level, $data);
        
        foreach ($summarizing->get() as $sum_level=>$sum_settings) {
        	(!empty($_SESSION['name_summaried_field'])) ? ($format_sum_level = currency_format_number($data[$summarizing->getQueriedName($sum_level)])) : ($format_sum_level = $data[$summarizing->getQueriedName($sum_level)]);
            $title .= 
                ', '.$mod_strings['LBL_SUM'].': ' 
                . $summarizing->getFieldLabel($sum_level) 
                .' = '. $format_sum_level;
        }
            
        return $title;
    }

    /**
     * Return HTML for title of the Group
     * 
     * @return string
     * @param string $title  The string of the title
     */
    public function getGroupTitleHtml($title)
    {
        return '<a href="#"><span class="expanded"></span>'. $title .'</a>';
    }
    
    /**
     * Return the HTML for grandtotals
     * 
     * @return string
     */
    public function getGrandTotalsCode()
    {
        global $mod_strings;
        $code_list = array();
        
        $store         = new \Reports\Data\Collection();
        $settings      = \Reports\Settings\Storage::getSettings();
        $summarizing   = \Reports\Data\Summarizing::load();
        $summary_field = $summarizing->getQueriedName(0);
        $criterion     = \Reports\Data\Criterion::getInstance()
            ->setSettings($settings)
            ->buildCriteria();
                    
        if (
            $result = $store->getRows(
                $criterion->applySummaries()
            )
        ){
            $result = $result[0];
            foreach ($summarizing->get() as $index=>$sum_info) {
            	(!empty($_SESSION['name_summaried_field'])) ? ($format_sum_field = currency_format_number($result[$summary_field])) : ($format_sum_field = $result[$summary_field]);
                $summary_field = $summarizing->getQueriedName($index);
                $code_list[] = '<div>'.translate('LBL_SUM', 'rls_Reports').': '. $summarizing->getFieldLabel($index) .' '.$format_sum_field.'</div>';
            }
        }
        
        if (!$code_list) {
            return false;
        }
        
        $code = '
            <div class="reports-grid-gt">
                <h1><b>'.translate('LBL_GRAND_TOTALS', 'rls_Reports').'</b></h1>
                '. implode("\n", $code_list) .'
            </div>
        ';
        
        return $code;
    }
    
    /**
     * Adds a HTML container to stack of code.
     * 
     * @access public
     * @param string $header Header content
     * @param string $content Container content
     */
    public function setHtmlContainer($header, $content)
    {
        $content = '
            <div class="reports-grid-group">
                <h1>'. $header .'</h1>
                '. $content .'
            </div>
        ';
        
        $this->containers[] = $content;
        return $this;
    }
    
    /**
     * Return the array of containers
     * 
     * @return array
     * @param boolean $clear    Clearing stack of containers
     */
    public function getContainers($clear = false) 
    {
        $containers = $this->containers;
        if ($clear) {
            $this->containers = array();
        }
        
        return $containers;
    }

    /**
     * This method builds the HTML table for according to group data
     * 
     * @access public
     * @param array data The data for group from DB
     */
    public function addTableForGroupData(array $data)
    { 
        foreach ($data as $row_data) {
            $this->addRow($row_data);
        }
        
        return $this->getSheet();
    }
    
    /**
     * Parsing of all grouping levels.
     * 
     * @return self
     * @param array $data_tree  The array of composed data for the group
     */
    private function parseGroupingLevels(array $data_tree = array()) 
    {
        $data_tree = $data_tree ? $data_tree : $this->getDataTree();
        $grouped_instance = new self;
                
        foreach ($data_tree as $group_info) {
            $title = $this->getGroupTitleHtml($group_info['groupTitle']);
            
            if (isset($group_info['children'])) {
                $container = $this->parseGroupingLevels($group_info['children']);
            } else {
                $container = $this->addTableForGroupData($group_info['rows']);
            }
            
            $grouped_instance->setHtmlContainer(
                $title,
                $container
            );
        }
        
        return '<div>'. implode("\n", $grouped_instance->getContainers(true)) .'</div>';
    }
    
    /**
     * This function will compose the array for groups definition.
     * 
     * @return array
     * @param integer $level  The level of the grouing
     * @param array $criteria  Extra criteria for additionalWhere
     * 
     */
    public function getDataTree($level = 0, array $criteria = array())
    {
        $data_tree = array();
        $grouping  = \Reports\Data\Grouping::load();
        
        foreach ($this->getListOfGroups($level, $criteria) as $category_data) {
            $node = array(
                'groupTitle' => $this->getGroupTitle($level, $category_data['row']) 
            );
            
            if ($grouping->get($level+1)) {
                $node['children'] = $this->getDataTree(
                    $level+1, 
                    array_merge(
                        $criteria,
                        $grouping->getCriteriaForYear($level, $category_data['row']),
                        $category_data['categoryInfo']['additionalWhere']
                    )
                );
            } else {
                $node['rows']     = $this->getDataForGroup(
                    array_merge(
                        $criteria,
                        $category_data['categoryInfo']['additionalWhere']
                    )
                );
            }
            
            $data_tree[] = $node;
        }
        
        /*for ($i = 0; $i < count($data_tree); $i++){
            for($j = 0; $j < count($data_tree[$i]['rows']); $j++){
                for ($k = 0; $k < count($_SESSION['name_currency_column']); $k++){
                    $name_currency_column = $_SESSION['name_currency_column'][$k];
                    $data_tree[$i]['rows'][$j][$name_currency_column] = currency_format_number($data_tree[$i]['rows'][$j][$name_currency_column]);
                }
            }
        }*/
        //TODO: Transform under the unlimited nesting
        foreach($data_tree as $data_tree_index=>$data_tree_value){
            if(array_key_exists('rows',$data_tree_value)){
                foreach($data_tree_value['rows'] as $row_id=>$row){
                    foreach($_SESSION['name_currency_column'] as $name_currency_column){
                        $data_tree[$data_tree_index]['rows'][$row_id][$name_currency_column] = currency_format_number($data_tree[$data_tree_index]['rows'][$row_id][$name_currency_column]);
                    }
                }
            }
            if(array_key_exists('children',$data_tree_value)){
                foreach($data_tree_value['children'] as $children_index=>$children_value){
                    if(array_key_exists('rows',$children_value)){
                        foreach($children_value['rows'] as $children_row_id=>$children_row){
                            foreach($_SESSION['name_currency_column'] as $name_currency_column){
                                //TODO: 7202
                                //$data_tree[$data_tree_index]['children'][$children_index]['rows'][$row_id][$name_currency_column] = currency_format_number($data_tree[$data_tree_index]['children'][$children_index]['rows'][$row_id][$name_currency_column]);
                            }
                        }
                    }
                }
            }
        }

        return $data_tree;
    }
    
    /**
     * Returns the html code of spreadsheet.
     * 
     * Entry point for generating grouped spreadsheet.
     * @access public
     */
    public function display()
    {
        $grouping  = \Reports\Data\Grouping::load();
        
        if ($grouping->get()) {
            $this->setHtml(
                $this->parseGroupingLevels()
              . $this->getGrandTotalsCode()
            );            
        } else {
            
        }
        
        return $this->getHtml();
    }
    
    //
    public function getRecordsContent()
    {
        $content='';
        $data_tree = $data_tree ? $data_tree : $this->getDataTree();
        $settings = \Reports\Settings\Storage::getSettings();
        $content.=$this->getHeader($settings);
        $content.=$this->getRows($settings,$data_tree);      
        return $content;
    }
    //
    function getHeader($settings){
        $header_line='';
        foreach ($settings['grid']['columns'] as $column_settings) {
            if($column_settings['visible']){
                $line = $column_settings['field_name'];
                $line = "\"" . $line;   
                $line .= "\",";
                $header_line.=$line;
            }           
        }
        $header_line=substr($header_line,0,-1);
        $header_line .= "\r\n";
        return $header_line;    
    }
    
    /**
     * Returns report table rows.           
     */
    function getRows($settings,$data_tree){
        $rows = '';        
        foreach ($data_tree as $group_info) {
            
            if(is_array($group_info)){
                $this->row_containers = array();
                $rows_arrays = $this->getRecursive($group_info);                
            }
            
            foreach ($rows_arrays as $rows_array){
                foreach ($rows_array as $row_data) {
                    $rows.=$this->getRow($settings,$row_data);                 
                }
            }
        }
        return $rows;
    }
    
    /**
     * Find report table rows.           
     */
    function getRecursive($_element){        
        if (is_array($_element)) {
            foreach ($_element as $key => $value) {
                if($key === 'rows'){
                    $this->row_containers[] = $value;
                }
                $this->getRecursive($value);
            }
        }            
        return $this->row_containers;                
    }
    
    //
    function getRow($settings,$row_data){
        $concant_line='';
        foreach ($settings['grid']['columns'] as $column_settings) {
            if($column_settings['visible']){
                $line = $row_data[$column_settings['dataField']];
                $line = "\"" . $line;   
                $line .= "\",";
                $concant_line.=$line;
            }           
        }
        $concant_line=substr($concant_line,0,-1);
        $concant_line .= "\r\n";
        return $concant_line;
    }
    //
    public function getRecordsId()
    {
        $data_tree = $data_tree ? $data_tree : $this->getDataTree();
        $settings = \Reports\Settings\Storage::getSettings();             
        return $this->getRowsId($settings,$data_tree);
    }
    //
    function getRowsId($settings,$data_tree){
        $rows=array();
        foreach ($data_tree as $group_info) {
            foreach ($group_info['rows'] as $row_data) {
                   $rows[]=$row_data['id'];     
            }    
        }
        return $rows;
    }
    
    
    
}
