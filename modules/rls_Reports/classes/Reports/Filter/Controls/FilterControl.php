<?php
namespace Reports\Filter\Controls;

/**
 * Provides interface for filter control element
 * 
 * */
interface FilterControl
{
    /**
     * Constructor for settings definition
     * 
     * @param array $settings   Settings list for control
     * */
    public function __construct(array $settings);
    
    /**
     * Sets the settings
     * 
     * @return self
     * @param array $settings  Settings list for control
     * */
    public function setSettings(array $settings);
    
    /**
     * Gets the settings
     * 
     * @return array
     * */
    public function getSettings();
    
    /**
     * Provides method for retriving HTML code for control
     * 
     * @param mixed $current_value    Current value for Control
     * */
    public function getHtml($current_value = null);
    
    /**
     * Returns the criteria for quary baed on inputed value
     * 
     * param mixed $saved_value   The value which was saved in control
     * */
    public function getCriteria($saved_value);
}
