<?php
namespace Reports\Data;

/**
 * @access public
 * @author Richlode Solutions
 * @package Reports.Data
 */
class Collection
{
    /**
     * @var array
     */
    private $rows = array();
    
    /**
     * @var string
     */
    private $lastSql = null;
        
    /**
     * Retrieves a collection of arrays from DB
     * 
     * This method recieves Criterion object and gets sql text for fetch
     * 
     * @return array
     * @param Criterion $criterion      The Criterion object 
     * @access public
     */
    public function getRows(Criterion $criterion)
    {
        $this->rows    = array();
        $this->lastSql = $criterion->getSql();
        $database  = \DBManagerFactory::getInstance();
        $resourse  = $database->query($this->lastSql);
        
        // TO_DMITRY: 1. This function is only requires for developers.
        //            So if Sugar provides DB errors tracking by UI, then it's makes sense.
        //            In other case it shold be removed, or enabled when Sugar has Developer mode is enabled.
        //            
        //            2. Invalid code formatting
        //
        //            3. Move this action in separate method
        //
        //Checks the error response
        //$database->checkError('',true);
		if ($_REQUEST['module'] != 'Home') {
            if($database->checkError()){
                print_r($this->lastSql); 
                print_r("<br>MySQL error ".$database->getDatabase()->errno.": ".$database->getDatabase()->error);exit;
            }
		}
            
        while ($row = $database->fetchByAssoc($resourse)){
            $row_test = array();
            foreach($row as $index=>$value){
                $row_test[$index] = addslashes(html_entity_decode($value,ENT_QUOTES));
            }
            //$this->rows[] = $row;
            $this->rows[] = $row_test;
        }
        
        return $this->rows;
    }
    
}

