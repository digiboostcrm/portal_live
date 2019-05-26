<?php
namespace Reports\Settings\WIzard;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Settings.WIzard
 */
class DisplayFields extends Basic
{
  
    /**
     * Step name 
     * @var string
     * */
    protected $stepName = 'DisplayFields';

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
        $columns = array();
        foreach ($request_param as $field_name => $field_values) {

            $settings = $this->prepareCommonSettings($field_values);
            $column_settings = $this->prepareFieldsArrayToReturn($settings); 
            
            $table_alias = \Reports\Settings\Joins::getInstance()->getTableAliaceForField($field_values);

            
            if (array_key_exists('type',$column_settings['vardefs']) && $column_settings['vardefs']['type'] == 'relate') {

                if ($column_settings['related_module'] instanceof \Person) {
                    $data_field = 'last_name';
                } else {
                    $data_field = 'name';
                }

                $field_key = 'grid_display_' . str_replace('>', '', $column_settings['reletion_name']) . $column_settings['related_module'] . '_'.$data_field;
                $columns[$field_key] = array(
                                    'field_name'=>$data_field,
                                    'dataField'=>'grid_display_' . $column_settings['reletion_name'] . $column_settings['related_module'] . '_'. $data_field,
                                    'module_of_report' => $column_settings['module_of_report'],
                                    'module_of_field'  => $column_settings['related_module'],
                                    'label' => $column_settings['label'],
                                    'type' => 'relate',
                                    'id_name'=> $column_settings['vardefs']['id_name'],
                                    'visible_at_ew'=>false,
                                    'visible'=>true,
                                    'its_releted_field'=>true,
                                );
                                
                $columns[$field_key]['link'] = $this->setLink($columns[$field_key]);

            } else {
                if (array_key_exists('link',$column_settings)) {   //если в настрйоках колонки сущесвует параметр link то правим праметр dataField и name и добавляем дополнительное поле
                    
                    $columns['grid_display_' . str_replace('>', '', $column_settings['reletion_name']) . $column_settings['module_of_field'] . '_id'] = array(
                                  'field_name' => 'id',
                                  'dataField' => 'grid_display_' . str_replace('>', '', $column_settings['reletion_name']) . $column_settings['module_of_field'] . '_id',
                                  'module_of_report' => $column_settings['module_of_report'],
                                  'module_of_field' => $column_settings['module_of_field'],
                                  'visible' => false,
                                  'visible_at_ew' => false,
                                  'table_alias' => $table_alias,
                              );

                }
            }
            
            $columns[] = $column_settings;

        }

        //print_r($columns); exit;

        return array(
            'type' => 'Grouped',
            'columns'=>$columns
        );
    }
    
    /**
     *
     * */
    public function prepareFieldsArrayToReturn(array $fields_request_param)
    {
        $module_bean = $this->get_module_obj($fields_request_param['module_of_report'], $fields_request_param['reletion_name']);
            
        $field_array_to_return = $fields_request_param;
        $field_array_to_return['dataField'] = 'grid_display_' . str_replace('>', '', $field_array_to_return['reletion_name']) . $module_bean->module_name . '_' . $fields_request_param['vardefs']['name'];

        $field_array_to_return['source'] = $fields_request_param['vardefs']['source'];
        $field_array_to_return['type'] = $fields_request_param['vardefs']['type'];
        $field_array_to_return['visible'] = true;        //visible at detail view
        $field_array_to_return['visible_at_ew'] = true;  //visible at edit view
        
        $field_array_to_return['table_alias'] = \Reports\Settings\Joins::getInstance()->getTableAliaceForField($fields_request_param);

        // set 'related_module' for relate field
        if(array_key_exists('module',$fields_request_param['vardefs']) && !array_key_exists('related_module', $fields_request_param['vardefs'])){
            $related_module = $fields_request_param['vardefs']['module'];
        }elseif(array_key_exists('related_module',$fields_request_param['vardefs'])){
            $related_module = $fields_request_param['vardefs']['related_module'];
        }else{
            $related_module = '';
        }
        $field_array_to_return['related_module'] = $related_module;

        // set 'id_name' and 'source'
        if(array_key_exists('id_name',$fields_request_param['vardefs']) && !empty($fields_request_param['vardefs']['id_name'])){
            $id_name = $fields_request_param['vardefs']['id_name'];
            if($module_bean->field_defs[ $fields_request_param['vardefs']['id_name'] ]['source'] == 'custom_fields'){
                $field_array_to_return['source'] = 'custom_fields';
            } 
        }else{
            $id_name = '';
        }
        $field_array_to_return['id_name'] = $id_name;
        
        // set link 
        if(
              $fields_request_param['vardefs']['name'] == 'first_name' 
              || $fields_request_param['vardefs']['name'] == 'last_name'
              || $fields_request_param['vardefs']['name'] == 'name'
        ) {
              $field_array_to_return['link'] = $this->setLink($field_array_to_return);
        }

        // hide relate field
        if (array_key_exists('type',$fields_request_param['vardefs']) && $fields_request_param['vardefs']['type'] == 'relate') {
            $field_array_to_return['dataField'] = 'grid_display_' . str_replace('>', '', $field_array_to_return['reletion_name']) . $field_array_to_return['related_module'] . '_' . $fields_request_param['vardefs']['id_name'];
            $field_array_to_return['visible'] = false;
        }
        return $field_array_to_return;
    }

    /***
     * Set link for grid.
     * 
     * TODO: insert in to parse function
     * */
    public function setLink(array $fieldsParam) 
    {
        if (array_key_exists('type',$fieldsParam) && $fieldsParam['type'] == 'relate')
        {
            return array(
                  'parameters' => array(
                      'module' => $fieldsParam['module_of_field'],
                      'action' => 'DetailView',
                      'record' => '[grid_display_' . str_replace('>', '', $fieldsParam['reletion_name']) . $fieldsParam['module_of_field'] . '_' . $fieldsParam['id_name'] . ']',
                  )
            ); 
        }
        
        return array(
            'parameters' => array(
                'module' => $fieldsParam['module_of_field'],
                'action' => 'DetailView',
                'record' => '[grid_display_' . str_replace('>', '', $fieldsParam['reletion_name']) . $fieldsParam['module_of_field'] . '_id]',
            )
        ); 
    }
    
    /**
     *
     * */
    public function get_module_obj($module, $reletion_name)
    {
        $FieldsControl = new \Reports\Configurator\FieldsControl;
        $module_name = $FieldsControl->getSelectedModuleName($module, $reletion_name);
        $module_bean = loadbean($module_name);
        return $module_bean;
    }
    
}

