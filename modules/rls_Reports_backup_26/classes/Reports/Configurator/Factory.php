<?php
namespace Reports\Configurator;

/**
 * This class is intended for factory of type of the configurator
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class Factory
{
    /**
     * Load and return the Instance for type of configurator
     * 
     * @param string type The type of configurator
     * @ReturnType classes.Reports.Configurator.Fields
     */
    public static function load($type)
    {
        $classname = '\Reports\Configurator\\'.$type;
        $pars_class = new $classname();
        
        return $pars_class;
    }
}

