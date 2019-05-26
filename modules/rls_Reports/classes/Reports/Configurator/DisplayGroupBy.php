<?php
namespace Reports\Configurator;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class DisplayGroupBy extends Fields 
{
    
    /**
     *  Get html data of groupBy for function.
     * 
     * @param array field_settings 
     * @return string
     * */
    public function getGroupByFunction($field_settings = array())
    {
        global $mod_strings;
        $field_defs = $this->getFieldDefs($field_settings);
        $row_name = $this->getModule().'.'.$this->getReletion().'.'.$field_defs['name'];
        
        // FIXME: This must be put into a certain OOP pattern! 
        if ($field_defs['type'] == 'date'
            or $field_defs['type'] == 'datetime'
            or $field_defs['type'] == 'datetimecombo'
        ) {
            // set dropdown options
            $list = array(
                '' => $mod_strings['LBL_NONE'],
                'hours' => $mod_strings['LBL_HOURS'],
                'days' => $mod_strings['LBL_DAYS'],
                'weeks'=> $mod_strings['LBL_WEEKS'],
                'months' => $mod_strings['LBL_MONTHS'],
                'quarters' => $mod_strings['LBL_QUARTERS'],
                'year' => $mod_strings['LBL_YEAR'], 
            );
            $html  = $mod_strings['LBL_FUNCTION'] . ': <select name="wizard[DisplayGroupBy]['. $row_name .'][function]" id="'. $row_name .'-function">';
            $html .= get_select_options_with_id($list, (isset($field_settings['function']) ? $field_settings['function'] : ''));
            $html .= '</select>';
            return $html;
        } else {
            return '<input type="hidden" name="wizard[DisplayGroupBy]['. $row_name .'][function]" id="'. $row_name .'-function">';
        }
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
     * Build html for one filter from vardefs.
     * 
     * @param array fields_settings vardefs(after select field) or saved values(after reload page)
     * @return string
     * */
    public function buildSelectedFieldHtml(array $field_settings) 
    {
        global $mod_strings;
        
        $field_defs = $this->getFieldDefs($field_settings);

        $row_name = $this->getModule().'.'.$this->getReletion().'.'.$field_defs['name'];
        
        $groupby_function = $this->getGroupByFunction($field_settings);

        $html = '';
        ob_start();
            require(dirname(__FILE__).'/tpls/DisplayGroupBy.php');
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
              // check Drilldown settings before display groupings
              if ((($drilldown->isDrilldown()
                  and ($field['module_of_report'] == $settings['data']['module_of_report'])
                  and ($drilldown->isFieldAvailableForDrilldown(
                                      $settings['data']['module_of_report'],
                                      $field['field_name']
                                  )))
                  or !$drilldown->isDrilldown())
              ) {
                  $html .= $this->setModule($field['module_of_report'])
                              ->setReletion($field['reletion_name'])
                              ->setFieldName($field['field_name'])
                              ->buildSelectedFieldHtml($field);
              }
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
        $wizard = new \Reports\Settings\WIzard\DisplayGroupBy();
        $settings = $wizard->get();
        $html = $this->buildSelectedFields(
            isset($settings['grouping']) ? $settings['grouping'] : array()
        );
        return $html;
    }
    
}

