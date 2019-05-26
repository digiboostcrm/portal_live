<?php
namespace Reports\PDF;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.PDF
 */
interface Content
{
    /**
     * The rule for generating of content of PDF
     * @access public
     */
    public function generateContent();
}
