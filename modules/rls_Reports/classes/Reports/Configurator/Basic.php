<?php
namespace Reports\Configurator;
/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class Basic
{
    /**
     * Get module name for last relation from link of relationships
     * from root module to selected module.
     *
     * @param string root_module name root module from tree
     * @param string link list of all relations to selected module
     * @return string name of selected module
     * */
    public function getSelectedModuleName($root_module = '', $link = '')
    {
        $link = html_entity_decode($link); 
        if ($link) {
            $list_history_rel = explode('>', $link);
            $curr_mod_name = $root_module;
            foreach ($list_history_rel as $rel_name) {
                if ($rel_name) {
                    if ($module = loadbean($curr_mod_name)) {
                        $relation = $rel_name;
                        $module->load_relationship($relation);
                        //if (!isset($module->$relation)) return false;
                        $curr_mod_name = $module->$relation->getRelatedModuleName(); // Get module name
                    }
                }
            }
            $module_name = $curr_mod_name;
        } else {
            $module_name = $root_module;
        }
        return $module_name;
    }
    
    /**
     * Get last relation name from link of relationships
     * from root module to selected module.
     *
     * @param string link list of all relations to selected module
     * @return string relation to selected module
     * */
    public function getSelectedRelationName($link = '')
    {
        $relation_name = '';
        if ($link) {
            $list_history_rel = explode('>', $this->relationHistory);
            foreach ($list_history_rel as $rel_name) {
                if ($rel_name) {
                    $relation_name = $rel_name;
                }
            }
        }
        return $relation_name;
    }

    /**
     * Get display link for selected fields in steps of wizard.
     *
     * @param string $module root module in tree of modules
     * @param string $field selected field
     * @param string $relations list of the relationships from root module to selected
     * @return string generated link
     * */
    public function getDisplayLink($module = '', $field = '', $relations = '')
    {
        $link = $module . ' > ';
        if ($relations) {
            $list_history_rel = explode('>', $relations);
            $curr_mod_name = $module;
            foreach ($list_history_rel as $rel_name) {
                if ($rel_name) {
                    if ($module = loadbean($curr_mod_name)) {
                        $relation = $rel_name;
                        $module->load_relationship($relation);
                        //if (!isset($module->$relation)) return false;
                        $relationLabel = $this->getRelationLabel($curr_mod_name, $relation);
                        $curr_mod_name = $module->$relation->getRelatedModuleName(); // Get module name
                        $link .= $relationLabel . ' > ';
                    }
                }
            }
        }        
        return $link . $field;
    }

    /**
     * Get relation label for display.
     *
     * @param string $parentModuleName  
     * @param string $relationName relation name for parent module
     * @return string
     * */
    public function getRelationLabel($parentModuleName, $relationName)
    {
        global $beanList, $app_list_strings;
        
        $label = 'None';
        $parent_module = loadBean($parentModuleName);
        $parent_module->load_relationship($relationName);

        // if relation exist
        if (isset($parent_module->$relationName) ) {
            $properties = $parent_module->field_defs[$relationName];
          
            $child_module = $parent_module->$relationName->getRelatedModuleName();
            $module_bean = loadBean($child_module);
            
            if (isset($app_list_strings['moduleList'][$child_module])
                //and ($parentModuleName != $child_module)
            ) {
                if (isset($properties['module']) and isset($properties['vname'])
                    and translate($properties['vname'], $properties['module']) != $properties['vname']
                ) {
                    $label = translate($properties['vname'], $properties['module']);
                } else {
                    $label = $app_list_strings['moduleList'][$child_module];
                }
            }
        }
        return $label;
    }
    

}
