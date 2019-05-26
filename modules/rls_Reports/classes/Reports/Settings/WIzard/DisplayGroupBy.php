<?php
namespace Reports\Settings\WIzard;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Settings.WIzard
 */
class DisplayGroupBy extends Basic
{
  
    /**
     * Step name 
     * @var string
     * */
    protected $stepName = 'DisplayGroupBy';


    /**
     * Prepare settings array to save in to bean
     * @access public
     * @param array requestParam
     * @return array
     * @ParamType requestParam array
     * @ReturnType array
     */
    public function parse(array $request_param)
    {
        global $timedate;
        $DisplayFilters = new \Reports\Configurator\DisplayFilters;
        
        $grouping = array();
        foreach($request_param as $field_name => $field_values){
            $settings = $this->prepareCommonSettings($field_values);
            $grouping[] = $settings;
        }

        return array(
            'grouping'=> $grouping,
        );
    }
    
}

