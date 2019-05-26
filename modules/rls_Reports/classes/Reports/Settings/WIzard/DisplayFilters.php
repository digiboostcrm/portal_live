<?php
namespace Reports\Settings\WIzard;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Settings.WIzard
 */
class DisplayFilters extends Basic
{
  
    /**
     * Step name 
     * @var string
     * */
    protected $stepName = 'DisplayFilters';

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
        $operator = 'AND';
        if(array_key_exists('operator',$request_param)){
            $operator = $request_param['operator'];
            unset($request_param['operator']);
        }
        $controls = array();
        foreach($request_param as $field_name => $field_values){
            $settings = $this->prepareCommonSettings($field_values);
            
            // set settings for init filter object and build search url of chart
            $settings['settings_search_url'] = array(
                'type' => $DisplayFilters->getObjNamebyType($settings['vardefs']), // set filter object name
                'field_name_for_drilldown' => $field_values['field_name'],
            );

            // set settings for init filter object and build criteria string
            $settings['settings_build_criteria'] = array(
                'control_name' => $settings['module_of_report'].'.'.$settings['reletion_name'].'.'.$settings['field_name'],
                'type' => $DisplayFilters->getObjNamebyType($settings['vardefs']), // set filter object name
                'condition' => $field_values['condition'][0],
                'field_name' => $this->getFieldNameAlias($settings), // table alias + field name for criteria string
                'module' => '',
            );

            // convert for date type
            if (isset($settings['value']['range_date'])) {
                foreach ($settings['value']['range_date'] as $fieldName =>$fieldValue) {
                    $settings['value']['range_date'][$fieldName] = $timedate->to_db_date($settings['value']['range_date'][$fieldName], false);
                }
            }
            
            $controls[$field_name] = $settings;
        }

        return array(
            'operator' => $operator,
            //'operator' => 'AND',
            //'operator' => 'OR',
            'controls' => $controls,
        );
    }

    /**
     * Get table alias and field name for sql querry in criteria.
     *
     * @param array $settings field settings
     * @return string
     * */
    public function getFieldNameAlias($settings)
    {
        $module = loadbean($settings['module_of_field']);
        // check if field is custom and set table alias
        if ($settings['vardefs']['source'] == 'custom_fields') {
            $field_name_alias = $module->table_name . '_cstm.' . $settings['field_name'];
        } else {
            $table_alias = \Reports\Settings\Joins::getInstance()->getTableAliaceForField($settings);
            $field_name_alias = $table_alias . '.' . $settings['field_name'];
        }

        return $field_name_alias;
    }
    
}

