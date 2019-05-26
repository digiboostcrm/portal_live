<?php

namespace Reports\Filter\Controls;

/**
 * 
 * */
class Number extends Basic
{
    /**
     * Get html for control.
     * 
     * @param mixed $current_value The value which was saved in control
     *
     * @return string
     * */
    public function getHtml($current_value = null)
    {
        global $app_strings;

        $settings = $this->getSettings();
        $control_name = $settings['control_name'];
        $field_guide = $settings['field_guide'];
        $func_key = rand(90000, 900000);

        $html = '';
        ob_start();
        require dirname(__FILE__).'/../tpls/Number.php';
        $data_template = ob_get_contents();
        ob_end_clean();

        return $html.$data_template;
    }

    /**
     * Get url search params for control.
     * 
     * @param mixed $current_value The value which was saved in control
     *
     * @return array
     * */
    public function getUrlParams($current_value = null)
    {
        $settings = $this->getSettings();
        $params = array();

        $drilldown = \Reports\Chart\Drilldown::getInstance();
        if (is_array($current_value)) {
            if ($settings['field_name_for_drilldown'] == 'amount') {
                $settings['field_name_for_drilldown'] = 'range_'.$settings['field_name_for_drilldown'];
            }

            $search_field_name = $settings['field_name_for_drilldown'].$drilldown->getSearchPrefix();
            if (isset($current_value[0]) // for currency
                and $current_value[0]
                and $settings['field_name_for_drilldown'] == 'range_amount'
            ) {
                $params['amount_advanced_range_choice'] = '';
                $params[$search_field_name] = $current_value[0];
            } else { // another numbers
                foreach ($current_value as $key => $value) {
                    $params[$search_field_name] = $value;
                }
            }
        }

        return $params;
    }
}
