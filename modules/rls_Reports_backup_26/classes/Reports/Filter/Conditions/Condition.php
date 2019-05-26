<?php
namespace Reports\Filter\Conditions;

/**
 *  Base class for conditions.
 *
 * */
class Condition extends \Reports\Filter\Controls\Dropdown
{
    /**
     * Initialization of class.
     * 
     * @param array $settings   Preload settings
     * */
    public function __construct(array $settings)
    {
        global $mod_strings;
        
        parent::__construct($settings);
        foreach ($this->optionsList as $keyOption => $option) {
           $label = 'LBL_' . trim(strtoupper($option));
           if (isset($mod_strings[$label])) {
               $this->optionsList[$keyOption] = $mod_strings[$label];
           }
        }
        
    }

    /**
     * Get condition html.
     *
     * @param array $current_value selected data
     * @return string html for dropdown condition
     * */
    public function getHtml($current_value = null)
    {
        $settings = $this->getSettings();
        $list     = $this->getOptions();
        $drilldown = \Reports\Chart\Drilldown::getInstance();
        $is_drilldown = $drilldown->isDrilldown();

        $html  = '<select
                      name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'] .'][condition][]"
                      id="filter_values-'.$settings['control_name'] . '_' . $settings['field_guide'] .'"
                  ' . ($is_drilldown ? 'style="display:none;"' : '') . '
                  >';
        $html .= get_select_options_with_id($list, ($is_drilldown ? '' : $current_value));
        $html .= '</select>';

        if ($is_drilldown) {
            $html .= '<select
                          name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'] .'][condition][]"
                          id="filter_values-'.$settings['control_name'] . '_' . $settings['field_guide'].'-for_drilldown"
                          disabled="disabled"
                      >';
            $html .= get_select_options_with_id($list, '');
            $html .= '</select>';
        }
        
        return $html;
    }
    
}
