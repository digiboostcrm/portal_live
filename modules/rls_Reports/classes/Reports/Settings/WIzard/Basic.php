<?php
namespace Reports\Settings\WIzard;
/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Settings.WIzard
 */
abstract class Basic {
    /**
     * User settings
     */
    private $userSettings;
    
    /**
     * Step Name
     * */
    protected $stepName;

    /**
     * Prepare settings array to save in to bean
     * @access public
     * @param array requestParam
     * @return array
     * @ParamType requestParam array
     * @ReturnType array
     */
    public abstract function parse(array $requestParam);

    /**
     * Return DisplayFields step settings
     * @access public
     * @return array
     * @ReturnType array
     */
    public function get()
    {
        $focus = \Reports\Settings\Storage::getFocus();
        
        if (!$focus or !$focus->reports_settings) {
            return array();
        }

        $reports_settings = \Reports\Settings\Storage::getReportsSettings();
        if (!isset($reports_settings[$this->stepName])) {
            return array();
        }

        return $reports_settings[$this->stepName];
    }

    private function checkSettings($reports_settings)
    {
        foreach ($reports_settings as $key => $data)
        {
            if (isset($data['reletion_name'])
                and isset($data['module_of_report'])
                and ($data['module_of_report'])
                and ($data['reletion_name'])
            ) {
                $list_history_rel = explode('>', $data['reletion_name']);
                $curr_mod_name = $data['module_of_report'];
                foreach ($list_history_rel as $rel_name) {
                    if ($rel_name) {
                        if ($module = loadbean($curr_mod_name)) {
                            $relation = $rel_name;
                            $module->load_relationship($relation);
                            if (!isset($module->$relation)) {
                                unset($reports_settings[$key]);
                                break;
                            }
                            $curr_mod_name = $module->$relation->getRelatedModuleName(); // Get module name
                        }
                    }
                }
            }
            
        }
        return $reports_settings;
    }
    
    /**
     * Set common settings for field in wizard.
     *
     * @param array $request_field_data field data from request
     * @return array
     */
    public function prepareCommonSettings($request_field_data = array())
    {
        $FieldsControl = new \Reports\Configurator\FieldsControl;
        $module_name = $FieldsControl->getSelectedModuleName(
            $request_field_data['module_of_report'],
            $request_field_data['reletion_name']
        );
        $module = loadbean($module_name);

        // set data from request for field
        $settings = $request_field_data;
        $settings['reletion_name'] = html_entity_decode($settings['reletion_name']);

        // set vardefs of field
        $settings['vardefs'] = $module->field_name_map[$request_field_data['field_name']];
        
        // set module of selected field
        $settings['module_of_field'] = $module_name;

        // set key of custom fields if not exist
        if (!isset($settings['vardefs']['source'])) {
            $settings['vardefs']['source'] = '';
        }
        
        return $settings;
    }

}

