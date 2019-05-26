<?php
namespace Reports\Filter\Controls;

/**
 * This class is intended for handling Control of Periods type
 * 
 * FIXME: This control should be retrieved by stnadard agregates of Sugar
 * 
 * @package Reports.Filter.Controls
 * */
class Periods extends Dropdown
{

    /**
     * The list of filter data
     * 
     * @var array
     * */
    private $filterValues = array();

    /**
     * The list of ranges.
     *
     * @var array
     * */
     private $ranges = array();


    /**
     * Class construct.
     *
     * @param array $settings
     * */
    public function __construct(array $settings)
    {
        parent::__construct($settings);
        $this->ranges = $this->getRangesByName($settings['control_name'].'_'.$settings['field_guide']);
    }

    
    /**
     * Get ranges for control by control name.
     * 
     * @param string $control_name control name for getting values of range
     * @return array
     * */
    public function getRangesByName($control_name = '')
    {
        $ranges = array(
            'range_date_closed' => $this->getRangeValue($control_name.'-range_date_closed'),
            'start_range_date_closed' => $this->getRangeValue($control_name.'-start_range_date_closed'), 
            'end_range_date_closed' => $this->getRangeValue($control_name.'-end_range_date_closed'), 
        );
        return $ranges;
    }

    /**
     *  Get range value by name of range.
     *
     * @param string $range_name name for getting value
     * @return array
     * */
    public function getRangeValue($range_name = '')
    {
        global $timedate;

        $to_db = (isset($this->filterValues['range_date'][$range_name])
                  ? $this->filterValues['range_date'][$range_name]
                  : date('Y-m-d'));
        $to_display = (isset($this->filterValues['range_date'][$range_name])
                  ? $timedate->to_display_date($this->filterValues['range_date'][$range_name], false)
                  : $timedate->to_display_date(date('Y-m-d'), false));
        $value = array(
            'to_db' => $to_db,
            'to_display' => $to_display,
        );
        return $value;
    }
    
    /**
     * Retrieves criterion string for SQL by control of filter
     * 
     * @param mixed $saved_value Saved value for control
     * @return string search criterion
     * 
     * */
    public function getCriteria($saved_value)
    {
        $settings      = $this->getSettings();
        $this->filterValues['range_date'] = $saved_value['range_date'];
        $this->ranges = $this->getRangesByName(($settings['control_name'].'_'.$settings['field_guide']));
      
        $class_name = get_class($this);
        $parsed_class_name = substr($class_name, strrpos($class_name, '\\')+1);
        $condition = \Reports\Filter\Factory::loadCondition($this->getSettings());
        $criteria = $condition->getCriteria($this->ranges);

        return $criteria;
    }
    
    /**
     * Get HTML for DateType field.
     *
     * @param string $current_value selected value in dropdown
     * @return string
     * */
    public function getHtml($current_value = null)
    {
        global $timedate, $app_strings;
        $settings      = $this->getSettings();
        $control_name = $settings['control_name'];
        $field_guide = $settings['field_guide'];
        $func_key = rand(90000, 900000);

        $this->filterValues['range_date'] = $current_value['range_date'];
        $this->ranges = $this->getRangesByName($settings['control_name'].'_'.$settings['field_guide']);
        
        $html = '';
        ob_start();
            require(dirname(__FILE__).'/../tpls/Periods.php');
            $data_template = ob_get_contents();
        ob_end_clean();

        return $html . $data_template;
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

        $field_name = $settings['field_name_for_drilldown'];
        if (is_array($current_value) and $current_value) {
            $params = array(
                $field_name . '_advanced_range_choice' => $current_value[0],
                'range_' . $field_name . '_advanced' => '[' . $current_value[0] . ']',
            );

            switch ($current_value[0]) {
                case '=':
                    $params[$field_name . '_advanced_range_choice'] = '';
                    break;
                case 'tp_today':
                    $params[$field_name . '_advanced_range_choice'] = '';
                    $params['range_' . $field_name . '_advanced'] = date('Y-m-d');
                    break;
                case 'tp_yesterday':
                    $params['' . $field_name . '_advanced_range_choice'] = '';
                    $params['range_' . $field_name . '_advanced'] = date('Y-m-d', time() - 60*60*24);
                    break;
                case 'tp_tomorrow':
                    $params['' . $field_name . '_advanced_range_choice'] = '';
                    $params['range_' . $field_name . '_advanced'] = date('Y-m-d', time() + 60*60*24);
                    break;
            }
            
            if ($current_value[0] == 'between') {
                unset($params['range_' . $field_name . '_advanced']);
                $params['start_range_' . $field_name . '_advanced'] = $this->ranges['start_range_date_closed']['to_db'];
                $params['end_range_' . $field_name . '_advanced'] = $this->ranges['end_range_date_closed']['to_db'];
            } elseif ($current_value[0] == '='
                or $current_value[0] == 'not_equal'
                or $current_value[0] == 'greater_than'
                or $current_value[0] == 'less_than'
            ) {
                $params['range_' . $field_name . '_advanced'] = $this->ranges['range_date_closed']['to_db'];
            }
            
        }
        
        return $params;
    }
    
}
