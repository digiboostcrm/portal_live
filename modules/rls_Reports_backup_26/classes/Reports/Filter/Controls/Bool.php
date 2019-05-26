<?php
namespace Reports\Filter\Controls;

/**
 * 
 * 
 * */
class Bool extends Basic
{
    
    /**
     * Get html for control.
     * 
     * @param mixed $current_value The value which was saved in control
     * @return string
     * */
    public function getHtml($current_value = null)
    {
        $settings = $this->getSettings();        

        $html  = '
           <input type="hidden"
                  name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'].'][value][0]"
                  value="0" >
           <input type="checkbox"
                  value="1"
                  name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'].'][value][0]"
                  ' . ((isset($current_value[0]) and $current_value[0]) ? ' checked="checked" ' : '') . ' >
        ';
        
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
                $params[$search_field_name] = (int)$value;
            }
        }
        return $params;
    }
    
}
