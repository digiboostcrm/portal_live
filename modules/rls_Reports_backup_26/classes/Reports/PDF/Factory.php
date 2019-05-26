<?php
namespace Reports\PDF;

/**
 * This class is intended for factory of type of the PDF documents
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.PDF
 */
class Factory 
{
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
        
        $classname = '\Reports\PDF\\'.$type;
        $pdf_doc = new $classname();
        
        return $pdf_doc;
    }
}