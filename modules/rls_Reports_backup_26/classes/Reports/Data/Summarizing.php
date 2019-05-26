<?php
namespace Reports\Data;

/**
 * This class is intended for handling summaries settings
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Data
 */
class Summarizing extends Operations
{
    /**
     * The prefix for summarizable field
     * 
     * @var string
     */
    protected static $summarizingPrefix = 'summarized_';
    
    /**
     * Self instance onbject
     * 
     * @var Grouping
     */
    protected static $summarizingInstance = null;
    
    /**
     * The setting for summaries
     * 
     * @var array
     */
    protected static $summarizingSettings = array();
        
    /**
     * Singleton caller
     * 
     * @access public
     * @return Grouping
     */
    public static function load()
    {
        if (self::$summarizingInstance instanceof self){
            return self::$summarizingInstance;
        }
        
        $settings  = \Reports\Settings\Storage::getSettings();
        self::$summarizingSettings = $settings['data']['summaries'];
        
        return self::$summarizingInstance = new self();
    }
    
    
    /**
     * Reset class settings to default
     * 
     * */
    public static function resetReport(){
        self::$summarizingInstance = null;
    }
    
    /**
     * (non-PHPdoc)
     * @see classes/Reports/Data/Reports\Data.Operations::getFunctionObject()
     */
    public function getFunctionObject($function_name) 
    {
        //Not yet implemented ...
    }
    
    /**
     * Returns the summary string for prefix
     * 
     * @access public
     * @return string
     * @ReturnType string
     */
    public function getSummaryPrefix() 
    {
        return $this->summaryPrefix;
    }
    
    /**
     * Returns composed function as SQL text.      
     * 
     * @return string
     * @param integer $level  The level of settings.
     * */
    public function getFunction($level = 0)
    {
        $level_settings = $this->get($level);
        if(
            array_key_exists('vardefs',$level_settings)
            && array_key_exists('type',$level_settings['vardefs'])
            && $level_settings['vardefs']['type'] == 'currency'
        ){
            ////need to properly calculate the amount of currency
			//7203
            $function = 'ROUND(' . parent::getFunction($level). '('. $this->getFieldname($level) .'/ IF( currencies_for_relate_field.conversion_rate IS NULL , 1, currencies_for_relate_field.conversion_rate)) , 2)';
            //$function = parent::getFunction($level). '('. $this->getFieldname($level) .'/ IF( currencies_for_relate_field.conversion_rate IS NULL , 1, currencies_for_relate_field.conversion_rate))';
        }else {
            $function = parent::getFunction($level). '('. $this->getFieldname($level) .')';
        }
        //$function = parent::getFunction($level). '('. $this->getFieldname($level) .')';
        //echo $function;
        return $function;
    }
    
    /**
     * Returns composed fieldname for SQL text conversion.      
     * 
     * @return string
     * @param integer $level  The level of settings.
     * */
    public function getQueriedName($level = 0)
    {
        $name = parent::getQueriedName($level);
        
        if (parent::getFunction($level) == 'COUNT') {
            return str_replace('*', 'count', $name);
        }
        
        return $name;
    }
    
    /**
     * Post parsing for summarizing function
     * 
     * @see classes/Reports/Data/Reports\Data.Operations::getFieldname()
     */
    public function getFieldname($level, $for_sql = true) 
    {
        $fieldname = parent::getFieldname($level, $for_sql);
        if (parent::getFunction($level) == 'COUNT') {
            return '*';
        }
        
        return $fieldname;
    }
}
