<?php
namespace Reports\Grid;

/**
 * This class is basis for all types of Spreadsheets
 * 
 * @access public
 * @author Richlode Solutions
 * @package Grid
 */
abstract class Sheet
{
    /**
     * This property contain HTML for Spreadsheet
     * 
     * @AttributeType string
     */
    private $html = null;
    
    /**
     * The HTML for TR of table
     * 
     * @AttributeType string
     */
    private $tableRows = array();

    /**
     * The main display function
     * 
     * @access public
     */
    abstract public function display();
    
    /**
     * Return the code for the href link.
     * 
     * @access public
     * @param array row  The data for Row
     * @param array column  The column settings
     */
    private function getHrefLink(array $row, array $column)
    {
        $output     = array();
        $parameters = $column['link']['parameters'];
        
        foreach ($parameters as $url_name=>$url_value){
            foreach ($row as $fieldname=>$field_value){
                if (strstr('['.$fieldname.']', $url_value)){
                    $parameters[$url_name] = str_replace('['.$fieldname.']', $field_value, $url_value);
                }
            }
            
            $output[] = $url_name.'='.$parameters[$url_name];
        }
        return '<a href="'. (isset($_SERVER['HTTPS'])?'https':'http') .'://'.$_SERVER['HTTP_HOST'].'/index.php?'.implode('&', $output).'">'
                  . ($row[$column['dataField']]?$row[$column['dataField']]:'  ') .
               '</a>';
    }

    /**
     * @access public
     * @param string html
     * @ParamType html string
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @access public
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Adds a row of a table
     * 
     * @access public
     * @param array row_data The data of row from DB
     * @return Grid.Sheet
     */
    public function addRow(array $row_data)
    {
        $settings = \Reports\Settings\Storage::getSettings();        
        $cells = array();
        
        foreach ($settings['grid']['columns'] as $column_settings) {
            if($column_settings['visible']){
                $build_class = \Reports\Grid\Cell\Factory::load($column_settings);
                $cells[] = $build_class->getCelHTML($column_settings, $row_data);
            }
        }
        
        $this->tableRows[] = '
            <tr>
              '. implode("\n", $cells) .'
            </tr>
        ';
        
        return $this;
    }
    
    /**
     * Returns the content for the cell.
     * 
     * @return string
     * @param array $row_data  The data for row
     * @param array $column_settings    The setting for column
     */
    private function getCellContent(array $row_data, array $column_settings) 
    {        
        $text = $this->getCellText($row_data, $column_settings);
        
        if (isset($column_settings['link'])) {
            $text = $this->getHrefLink($row_data, $column_settings);
        }
        
        $output = '<td>'. $text .'</td>';
        return $output;
    }

    /**
     * Returns the text for the cell.
     * 
     * @return string
     * @param array $row_data  The data for row
     * @param array $column_settings    The setting for column
     */
    public function getCellText(array $row_data, array $column_settings)
    {
        global $app_list_strings;
        
        $text = $row_data[$column_settings['dataField']];
        
        if (
            isset($column_settings['dropdownName'])
            && isset($app_list_strings[$column_settings['dropdownName']])
            && isset($app_list_strings[$column_settings['dropdownName']][$text]) // TO_DANIL fix for deleted values of dropdown Bug #1765
        ) {
            $text = $app_list_strings[$column_settings['dropdownName']][$text];
        }

        if (
            isset($column_settings['dropdownName'])
            && isset($app_list_strings[$column_settings['dropdownName']])
            && empty($text)
        ) {
            // fix Bug #1799
            //$text = 'LABEL DOES NOT EXISTS';
            // fix Bug #2649
            // TODO: change template to "No <fieldname> in <module_name>"
            $text = 'No Value';
        }
        
        return $text;
    }
    
    /**
     * Return the code for current collection of rows.
     * 
     * @return string
     */
    protected function getSheet()
    {
        $this->buildHeadingRow();
        
        $output = '
          <div class="reports-sheet-container">
            <table class="reports-sheet" width="100%">
                '. implode("\n", $this->tableRows) .'
            </table>
          </div>
        ';
        
        $this->tableRows = array();
        
        return $output;
    }
    
    /**
     * Returns row for heading
     * 
     * @return string
     */
    private function buildHeadingRow()
    {
        if($GLOBALS['action'] == 'downloadPDF' || array_key_exists('sendRportsAction',$GLOBALS)){
            $settings = \Reports\Settings\Storage::getSettings();
        $cells = array();
        
        foreach ($settings['grid']['columns'] as $column_settings) {
            if($column_settings['visible']){
                $cells[] = '<th width="'. (int)(100/count($settings['grid']['columns'])) .'%">'. $column_settings['label'] .'</th>';
            }
        } 
        $this->tableRows = array_merge(
            array('<tr>'.implode("\n", $cells).'</tr>'),
            $this->tableRows            
        );       
        return $this;         
        } else {   
        $settings = \Reports\Settings\Storage::getSettings();
        $cells = array();
        $report_bean = \Reports\Settings\Storage::getFocus();

        foreach ($settings['grid']['columns'] as $column_settings) {
            if($column_settings['visible']){
                if(array_key_exists('radio_btn',$column_settings)){
                    if($column_settings['orderBy'] == 'a'){
                        $img = '<img src="themes/default/images/arrow_up.gif?v=fsEYo73lWjaXax0rsDPoBw" align="absmiddle" border="0" alt="Sorted Ascending">';
                    } else {
                        $img = '<img src="themes/default/images/arrow_down.gif?v=GSC-rAUVI3nbePppRfim7Q" align="absmiddle" border="0" alt="Sorted Descending">';
                    }
                } else {
                    $img = '<img src="themes/default/images/arrow.gif?v=GSC-rAUVI3nbePppRfim7Q" align="absmiddle" border="0" alt="Sort">';
                }
                $cells[] = '<th width="'. (int)(100/count($settings['grid']['columns'])) .'%">
                                <a href="/index.php?module=rls_Reports&action=setOrderByForReport&record=' . $report_bean->id .'&order_by=' . $column_settings['dataField'] . '" style="text-decoration:none">'. $column_settings['label'] .'
                                &nbsp;&nbsp;' . $img . '
                                </a>
                            </th>';
            }
        }      
        $this->tableRows = array_merge(
            array('<tr>'.implode("\n", $cells).'</tr>'),
            $this->tableRows            
        );
        
        return $this;
    }
    
                }
}
