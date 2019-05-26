<?php
namespace Reports\Settings;

/**
 * The storage for settings of entire Report
 * 
 * @access public
 * @author Richlode Solutions
 * @package Reports.Settings
 */
class Storage 
{
    /**
     * The array of entire settings
     * 
     * @AttributeType array
     */
    private static $settings = array();
    
    /**
     * The path for configuration (metadata) file
     * 
     * @AttributeType string
     */
    private static $configPath = 'modules/rls_Reports/settings/';
    
    /**
     * The focus of SugarCRM.
     * 
     * This is SugarBean class
     * 
     * @var SugarBean
     * */
    private static $focus = null;
    
    /**
     * Sets the SugarBean focus to Storage
     * 
     * @param SugarBean $focus   The SugarBean of Reports record From DB
     * */
    public static function setFocus(\SugarBean $focus)
    {
        self::$focus = $focus;
    }

    /**
     * Reset class settings to default
     * 
     * */
    public static function resetReport(){
        self::$focus = null;
        self::$settings = null;
    }
    
    /**
     * Returns the SugarBean focus
     * 
     * @return SugarBean
     * */
    public static function getFocus()
    {
        return self::$focus;
    }

    /**
     * Load settings for report into self.
     * 
     * @access public
     * @param string report_type   The type of the Report
     * @return Reports.Settings.Storage
     * @ParamType report_type string
     * @ReturnType Reports.Settings.Storage
     */
    public static function load($force_type = null)
    {
        $module_name = ($force_type?$force_type:self::$focus->type);
        $module = loadbean($module_name);
        
        //TODO: Exception if bean was not loaded or it's not exists. 
        //      It needs to improve developer feedback indication 
        if (file_exists($conf_path = self::$configPath . '/settings.php')) {
            self::setSettings(require($conf_path));
        }
        
        // FIXME: it should be encapsulated into separate method
        if (array_key_exists('authenticated_user_language', $_SESSION) && $_SESSION['authenticated_user_language'] == 'ru_ru') {
            $GLOBALS['db']->query("SET @@lc_time_names='ru_RU'");
        }
    }

    /**
     * @access public
     * @param array settings
     * @ParamType settings array
     */
    public static function setSettings(array $settings)
    {
        //Users module does not have a assigned_user_link and we replace it to user_name field
        if($settings['data']['module_of_report'] == 'Users' && array_key_exists('is_default_grouping', $settings['data']['grouping'][0])){
            $settings['data']['grouping'][0]['fieldList'][0] = array(
                'is_default_grouping'=>'true',
                'reletion_name'=>'',
                'field_name'=>'user_name',
                'module_of_report'=>'Users',
                'function'=>'',
                'module_of_field'=>'Users',
                'vardefs'=>array(
                    'name'=>'user_name',
                    'vname'=>'LBL_USER_NAME',
                    'type'=>'user_name',
                    'dbType'=>'varchar',
                    'len'=>'60',
                    'importable'=>'required',
                    'required'=>'1',
                    'source'=>'',
                ),
            );
        }
        $wizard_displayfields = new \Reports\Settings\WIzard\DisplayFields();
        $settings['grid'] = $wizard_displayfields->get();
        if(array_key_exists('columns',$settings['grid'])){
            foreach($settings['grid']['columns'] as $value)
            {
                if(isset($value['radio_btn']) && $value['radio_btn']=='on'){
                    if(array_key_exists('source',$value) && $value['source']=='custom_fields'){
                        $curr_mod_name = $value['module_of_field'];
                        $module_bean = loadbean($curr_mod_name);
                        $settings['data']['criterion']['order']=$module_bean->table_name.'_cstm.'.$value['field_name'];
                    } else {
                        $settings['data']['criterion']['order']=$value['table_alias'].'.'.$value['field_name'];
                    }
                    $settings['data']['criterion']['order_value']=$value['orderBy'];
                }
            }
        }
        $settings['chart']['drilldown'] = (isset(self::getFocus()->drill_down) ? self::getFocus()->drill_down : '');

        $wizard_displayfields = new \Reports\Settings\WIzard\DisplayGroupBy();
        $grouping = $wizard_displayfields->get();
        if (isset($grouping['grouping']) and $grouping['grouping']) {
            $settings['data']['grouping'] = array();
            $settings['data']['grouping'][]['fieldList'] = (isset($grouping['grouping']) ? ($grouping['grouping']) : '');
        }
        
        $wizard_displayfields = new \Reports\Settings\WIzard\DisplaySummaries();
        $summaries = $wizard_displayfields->get();
        if (isset($summaries['summaries']) and $summaries['summaries']) {
            $settings['data']['summaries'] = $summaries['summaries'];
        }
        self::$settings = $settings;
    }

    /**
     * @access public
     * @return array
     * @ReturnType array
     */
    public static function getSettings()
    {
        return self::$settings;
    }
    
    /**
     * Return grouping settings
     * 
     * @return array
     * */
    public static function getGrouping()
    {
        $grouping = array();
        foreach (self::$settings['data']['grouping'] as $option) {
            $grouping = $option['fieldList'];
            break;
        }
        
        return $grouping;
    }
    
    /**
     * Retrieves saved values of ReportsSettings
     * 
     * @return array
     * */
    public static function getReportsSettings() 
    {
        $reports_settings = unserialize(
            base64_decode(
                html_entity_decode((self::getFocus()->reports_settings))
            )
        );
        if(empty($reports_settings)){
            $reports_settings = unserialize(
                html_entity_decode((self::getFocus()->reports_settings))
            );
        }
          
        global $db;   
        global $current_user;        
        if(
            !(
                array_key_exists('DisplayFilters',$reports_settings)
                && array_key_exists('controls',$reports_settings['DisplayFilters'])
            )
        ){
            return $reports_settings;
        }
        foreach ($reports_settings['DisplayFilters']['controls'] as $key => $value) {
            $settings_array = explode(".", $key);
            $end_settings_array = end($settings_array);
            $end_settings_array = str_replace('_'.$value['field_guide'],'',$end_settings_array);
            if ($end_settings_array == 'user_name') {
                
                if($value['value'][0] == 'current_user') {                    
                    $reports_settings['DisplayFilters']['controls'][$key]['value'][0] = $current_user->user_name;
                } else {
                    $query_users = "SELECT user_name FROM users WHERE deleted=0 AND id='{$value['value'][0]}'";
                    $result_select = $db->query($query_users);
                    $row = $db->fetchByAssoc($result_select);                
                    $reports_settings['DisplayFilters']['controls'][$key]['value'][0] = $row['user_name'];   
                }
            }
        }           
        return $reports_settings;
    }
    
}

