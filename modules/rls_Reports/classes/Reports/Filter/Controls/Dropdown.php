<?php
namespace Reports\Filter\Controls;

/**
 * 
 * 
 * */
class Dropdown extends Basic
{
    /**
     * The list of options
     * 
     * @var array
     * */
    protected $optionsList = array();

    /**
     * Sets the options list for dropdown
     * 
     * @return self
     * @param array $options    The list of options
     * */
    public function setOptions(array $options)
    {
        $this->optionsList = $options;
        return $this;
    }
    
    /**
     * Gets the options list for dropdown
     * 
     * @return array
     * */
    public function getOptions()
    {
        return $this->optionsList;
    }
    
    /**
     * Get html for control.
     * 
     * @param mixed $current_value The value which was saved in control
     * @return string
     * */
    public function getHtml($current_value = null)
    {
        global $app_list_strings;
        $settings = $this->getSettings();
        $list     = $this->getOptions();
        $html = null;

        if (isset($settings['dropdownName'])) {
            $dropdown_name = $settings['dropdownName'];
            if (isset($app_list_strings[$dropdown_name])) {
                $size = (count($app_list_strings[$dropdown_name]) > 10 ? 10 : count($app_list_strings[$dropdown_name]));
                $html  = '<select name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'] .'][value][]" id="filter_values-'.$settings['control_name'] . '_' . $settings['field_guide'] .'"
                                  multiple="multiple" size="'.$size.'">';
                $html .= get_select_options_with_id($app_list_strings[$dropdown_name], $current_value);
                $html .= '</select>';
            }
        } else {
            $size = (count($list) > 10 ? 10 : count($list));
            $html  = '<select name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'].'][value][]" id="filter_values-'.
                $settings['control_name'] . '_' . $settings['field_guide'].'" multiple="multiple" size="'.$size.'">';
            $html .= get_select_options_with_id($list, $current_value);
            $html .= '</select>';
        }
        
        return $html;
    }

    /**
     * Get url search params for control.
     * 
     * @param mixed $current_value The value which was saved in control
     * @return array
     * */
    public function getUrlParams($current_value = null)
    {
        $settings = $this->getSettings();
        $params = array();

        $drilldown = \Reports\Chart\Drilldown::getInstance();
        if (is_array($current_value)) {
            $search_field_name = $settings['field_name_for_drilldown'] . $drilldown->getSearchPrefix();
            foreach ($current_value as $key => $value) {
                $params[$search_field_name.'['.$key.']'] = $value;
            }
        }
        
        return $params;
    }
    
}
