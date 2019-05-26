<?php
namespace Reports\Filter;

/**
 * The factory class for access to Filter objects
 * 
 * @access public
 * @author Richlode Solutions
 * @package Controls
 */
class Factory
{
    /**
     * Returns class by type
     * 
     * @access public
     * @param array control  Settings for control
     * @ParamType control array
     */
    public function loadControl(array $control) 
    {
        $class = null;
        if (class_exists('\Reports\Filter\Controls\\'.ucfirst($control['type']))) {
            $classname = '\Reports\Filter\Controls\\'.ucfirst($control['type']);
            $class = new $classname($control);
        }
        return $class;
    }

    /**
     * Returns condition by type
     * 
     * @access public
     * @param array control  Settings for control
     * @ParamType control array
     */
    public function loadCondition(array $control) 
    {
        $class = null;
        if (class_exists('\Reports\Filter\Controls\\'.ucfirst($control['type']))) {
            $classname = '\Reports\Filter\Conditions\\'.ucfirst($control['type']);
            $class = new $classname($control);
        }
        return $class;
    }
    
}
