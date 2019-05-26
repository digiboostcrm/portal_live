<?php
namespace Reports\Settings;
/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Settings
 */
class Joins {
    /**
     * Singletone of object.
     * */
    protected static $instance = null;
    
    /**
     * Get singletone of object.
     *
     * return object
     * */
    public static function getInstance() 
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    /**
     * Reset class settings to default
     * 
     * */
    public static function resetReport(){
        self::$instance = null;
    }

    /**
     * Build alias for table of selected field as "oldRelation_PreviousModule_RelationToModuleOfField"
     * 
     * @param array $field_settings settings for field
     * @return mixed (alias for table of field/false)
     * */
    public function getTableAliaceForField($field_settings)
    {
        $report_settings = \Reports\Settings\Storage::getSettings();
        $curr_mod_name = $report_settings['data']['module_of_report'];
        $module_bean = loadbean($curr_mod_name);
        $old_relation = '';
        $relation = '';
        $alias = $module_bean->table_name;
        if(isset($field_settings['reletion_name'])
            && $field_settings['reletion_name']
        ) {
            $list_history_rel = explode('>', html_entity_decode($field_settings['reletion_name']));
            foreach ($list_history_rel as $index => $rel_name) {
                $alias = $old_relation .'_'. $module_bean->object_name . '_' . $relation;
                if ($rel_name) {
                    $module_bean = loadbean($curr_mod_name);
                    $relation = $rel_name;
                    $module_bean->load_relationship($relation);
                    if (!isset($module_bean->$relation)) {
                        return false;
                    }
                    $curr_mod_name = $module_bean->$relation->getRelatedModuleName(); // Get module name
                    $alias = $old_relation .'_'. $module_bean->object_name . '_' . $relation;
                    $old_relation = $relation;
                }
            }
        }
        return $alias;
    }
}
?>
