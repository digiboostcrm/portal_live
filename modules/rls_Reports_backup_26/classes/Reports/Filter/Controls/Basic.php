<?php
namespace Reports\Filter\Controls;

/**
 *  Base class for not dropdown filter controls.
 *
 * */
abstract class Basic implements FilterControl
{
    /**
     * Control settings.
     * 
     * @var array
     * */
    private $settings = array();

    /**
     * Initialization of class.
     * 
     * @param array $settings   Preload settings
     * */
    public function __construct(array $settings)
    {
        $this->setSettings($settings);
    }
    
    /**
     * Sets the settings
     * 
     * @return self
     * @param array $settings  Settings list for control
     * */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        return $this;
    }
    
    /**
     * Gets the settings
     * 
     * @return array
     * */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Get condition html for control.
     * 
     * @param mixed $current_value The value which was saved in control
     * @return string
     * */
    public function getConditionHtml($current_value = null)
    {
        $class_name = get_class($this);
        $parsed_class_name = substr($class_name, strrpos($class_name, '\\')+1);
        $condition = \Reports\Filter\Factory::loadCondition($this->getSettings());
        $html  = $condition->getHtml($current_value);
        return $html;
    }
      
    /**
     * Get html for control.
     * 
     * @param mixed $current_value The value which was saved in control
     * @return string
     * */
    public function getHtml($current_value = null)
    {
        $settings = $this->getSettings();        

        $html  = '<input name="filter_values['.$settings['control_name'] . '_' . $settings['field_guide'] .'][]" id="filter_values-' . $settings['control_name'] . '_' . $settings['field_guide']
                   . '" value="' . (isset($current_value[0])? $current_value[0] : '') . '"> ';
        
        return $html;
    }
   
    /**
     * Returns the criteria for query based on inputed value
     * 
     * @param mixed $saved_value The value which was saved in control
     * @return string
     * */
    public function getCriteria($saved_value)
    {
        $class_name = get_class($this);
        $parsed_class_name = substr($class_name, strrpos($class_name, '\\')+1);
        $condition = \Reports\Filter\Factory::loadCondition($this->getSettings());
        $criteria = $condition->getCriteria($saved_value);
        return $criteria;
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
        if (isset($current_value[0])) {
            $search_field_name = $settings['field_name'] . $drilldown->getSearchPrefix();
            $params[$search_field_name] = $current_value[0];
        }
        
        return $params;
    }
    
}
