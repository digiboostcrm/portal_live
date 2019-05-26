<?php
namespace Reports\Configurator;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class DisplaySummaries extends Fields 
{
    /**
     *  Get html data of summaries for function.
     * 
     * @param array field_settings
     * @return string
     * */
    public function getFunction($field_settings = array())
    {
        global $mod_strings;
        $field_defs = $this->getFieldDefs($field_settings);
        $row_name = $this->getModule().'.'.$this->getReletion().'.'.$field_defs['name'];
        
        $typesForSummaries = array(
                'currency',
                'int',
                'float',
                'decimal',
        );
        if (in_array($field_defs['type'], $typesForSummaries)) {
            $list = array(
                'SUM' => 'SUM',
                'MAX' => 'MAX',
                'MIN' => 'MIN',
                'AVG' => 'AVG',
            );
        } else {
            $list = array(
                'COUNT' => 'COUNT',
            );
        }
        $html  = $mod_strings['LBL_FUNCTION'] . ': <select name="wizard[DisplaySummaries]['. $row_name .'][function]" id="'. $row_name .'-function">';
        $html .= get_select_options_with_id($list, (isset($field_settings['function']) ? $field_settings['function'] : ''));
        $html .= '</select>';
        return $html;
    }

     
    /**
     * Get html of selected field
     * @access public
     * @param field_name
     * @return string
     */
    public function getFieldHTML($field_name) 
    {
        $fields_html = '';
        $fields_list = $this->getModulesFieldList();
        foreach ($fields_list as $field) {
            if ($field['name'] == $field_name) {
                $fields_html = $this->buildSelectedFieldHtml($field);
                break;
            }
        }
        return $fields_html;
    }

    /**
     * Build html for one selected row.
     * 
     * @param array fields_settings vardefs(after select field) or saved values(after reload page)
     * @return string
     * */
    public function buildSelectedFieldHtml(array $field_settings) 
    {
        global $mod_strings;
        //echo '<pre>'; print_r($field_settings); exit;
        $field_defs = $this->getFieldDefs($field_settings);

        $row_name = $this->getModule().'.'.$this->getReletion().'.'.$field_defs['name'];
        $summaries_function = $this->getFunction($field_settings);

        $html = '';
        ob_start();
            require(dirname(__FILE__).'/tpls/DisplaySummaries.php');
            $data_template = ob_get_contents();
        ob_end_clean();
        
        return $html . $data_template;
    }
    
    /**
     * Build html for selected filter

     * @param array fields_array
     * @return string
     */
    public function buildSelectedFields(array $fields_array) 
    {
        $html = '';
        if(empty($fields_array)){
            return $html;
        }

        $drilldown = \Reports\Chart\Drilldown::getInstance();
        $settings = \Reports\Settings\Storage::getSettings();
        
        foreach ($fields_array as $field) {
            $html .= $this->setModule($field['module_of_report'])
                        ->setReletion($field['reletion_name'])
                        ->setFieldName($field['field_name'])
                        ->buildSelectedFieldHtml($field);
        }
        return $html;
    }
    
    /**
     * Get html for selected filters
     * 
     * @param bool $only_runtime display only runtime filters
     * @return string html
     */
    public function display() 
    {
        $wizard = new \Reports\Settings\WIzard\DisplaySummaries();
        $settings = $wizard->get();
        $html = $this->buildSelectedFields(
            isset($settings['summaries']) ? $settings['summaries'] : array()
        );
        return $html;
    }
    
}

