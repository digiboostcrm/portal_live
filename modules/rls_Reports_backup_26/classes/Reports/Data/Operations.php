<?php
namespace Reports\Data;

/**
 * This class is intended for holding basic operations for conversion result from DB
 * 
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Data
 */
abstract class Operations 
{
    /**
     * Singleton caller
     * 
     * @access public
     * @return classes.Reports.Data.Grouping
     */
    abstract public static function load();
    
    /**
     * Returns an object for extra functionality regarding extra function of operation.
     * 
     * For example, if grouping for field was created w/ function "monthes"
     *  then \Reports\Data\Grouping\Monthes object will be instantinated and returned
     * 
     * @param string $function_name  The name of function
     */
    abstract public function getFunctionObject($function_name);
    
    /**
     * Returns loaded settings of summaries
     * 
     * @return array
     * @param integer $level    If it exist, then only index will returned
     * */
    public function get($level = null)
    {
        list(, , $child_classname) = explode('\\', $full_classname = get_called_class());
        $parameter_name  = strtolower($child_classname).'Settings';
        
        if (is_int($level)
            && isset($full_classname::${$parameter_name}[$level])
        ) {
            return $full_classname::${$parameter_name}[$level];
        } else if (is_int($level)) {
            return false;
        }
        
        return $full_classname::${$parameter_name};
    }
    
    /**
     * Returns the prefix for operation
     * 
     * @return string
     */
    public function getPrefix() 
    {
        list(, , $child_classname) = explode('\\', $full_classname = get_called_class());
        $parameter_name  = strtolower($child_classname).'Prefix';
        
        return $full_classname::${$parameter_name};
    }
    
    /**
     * Getting requested name of the setting group. 
     * 
     * This method is intended for geting name of field which was added to SELECT operator.
     * By default it will top index of settings.
     * 
     * @return string
     * @param  integer $index The index of current settings.
     */
    public function getQueriedName($level = 0) 
    {
        $fieldname = $this->getFieldname($level);
        return $this->getPrefix() . str_replace('.', '_', $fieldname);
    }

    /**
     * Returns only fieldname.
     * 
     * This function returns the name of field of DB Table, which involved in operation function.
     * The list of field coulb be more than one, all of them stored in array and listed based on zero index.
     * So, by specifying a first parameter, number of index, you will get a name of the field from that list.      
     * 
     * @return string
     * @param integer $level  The level of settings.
     * @param boolean $for_sql  Set false to get clean name of field
     * TODO: Test it
     * */
    public function getFieldname($level = 0, $for_sql = true)
    {
        $level_settings = $this->get($level);
        if ($for_sql) {
            $table_alias = \Reports\Settings\Joins::getInstance()->getTableAliaceForField($level_settings);
            $level_bean = $this->getBean($level);
            $field_name = (
							(
								(isset($level_settings['source']) and ($level_settings['source'] == 'custom_fields'))
								OR
								(isset($level_settings['vardefs']) AND isset($level_settings['vardefs']['source']) AND ($level_settings['vardefs']['source'] == 'custom_fields'))
							)
                                                    ? $level_bean->table_name . '_cstm'
                                                    : $table_alias) .
                          '.' . $level_settings['field_name'];
            if(array_key_exists('vardefs',$level_settings)){
                $vardef = $level_settings['vardefs'];
                if($vardef['type']=='relate'){
                    $module_bean1 = loadbean($vardef['ext2']);
                    $table_alias = $module_bean1->table_name.'_for_relate_field';
                    if($level_bean instanceof \Person){
                        $field_name = $table_alias.'.last_name';
                    } else {
                        $field_name = $table_alias.'.name';
                    }

                }
            }
        } else {
            $field_name = $level_settings['field_name'];
        }
        return $field_name;
    }
    
    /**
     * Returns the SugarBean instance of level.
     * 
     * @param integer $level   The level of settings.
     * @return SugarBean
     */
    public function getBean($level = 0)
    {
        $level_settings = $this->get($level);
        //echo $level_settings['module'] . '<-';
        if (!$level_bean = loadBean($level_settings['module_of_field'])) {
            throw new \Exception('Bean <b>'. $level_settings['module_of_field'] .'</b> does not exist in Sugar namespace. Level: '.($level+1).'. Check the report configuration.', 10);
        }
                
        return $level_bean;
    }
    
    /**
     * Returns additional function name from setting.
     * 
     * Of course if t exists, if no, then FALSE will be  returned.
     * By specifying a first parameter, number of index, you will get a name of additional function of the field.      
     * 
     * @return string
     * @param integer $level  The level of settings.
     * */
    public function getFunction($level = 0)
    {
        $lvl_settings = $this->get($level);
        $function = isset($lvl_settings['function']) ? $lvl_settings['function'] : null;

        return $function;
    }

    /**
     * Return the name of module.
     * 
     * This function will return the name of module which will be involved in operation function.      
     * 
     * @return string
     * @param integer $level  The level for settings.
     * */
    public function getModule($level = 0)
    {
        $lvl_settings = $this->get($level);
        return $lvl_settings['module_of_field'];
    }
    
    /**
     * Returns the label for field
     * 
     * @return string
     * @param string $index     The index for level of grouping  
     */
    public function getFieldLabel($level) 
    {
        $bean      = $this->getBean($level);
        $fieldname = $this->getFieldname($level, false);
        $module    = $this->getModule($level);
        
        if ($fieldname == '*') {
            return 'Count';
        }
        
        if (!isset($bean->field_name_map[$fieldname])) {
            return false;
        }
        
        return translate($bean->field_name_map[$fieldname]['vname'], $module);
    }
}
