<?php
namespace Reports\Settings;

/**
 * This class is intended for factory of type of the PDF documents
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Settings
 */
class Factory {

    /**
     * Load and return the Instance for type of PDF document
     * @access public
     * @param string type The type of PDF document
     * @return classes.Reports.PDF.Basic
     * @static
     * @ParamType type string
     * The type of PDF document
     * @ReturnType classes.Reports.PDF.Basic
     */
    public static function load($type) {
        if (!$type) {
            $type = 'DefaultType';
        }

        $classname = '\Reports\Settings\WIzard\\'.$type;
        $pars_class = null;
        if (class_exists($classname)) {
            $pars_class = new $classname();
        }
        
        return $pars_class;
    }
}
?>
