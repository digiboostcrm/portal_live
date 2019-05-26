<?php
namespace Reports\Settings\WIzard;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Settings.WIzard
 */
class DisplaySummaries extends Basic
{
  
    /**
     * Step name 
     * @var string
     * */
    protected $stepName = 'DisplaySummaries';


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
        $summaries = array();
        foreach($request_param as $field_name => $field_values){
            $settings = $this->prepareCommonSettings($field_values);
            $summaries[] = $settings;
        }

        return array(
            'summaries'=> $summaries,
        );
    }
    
}

