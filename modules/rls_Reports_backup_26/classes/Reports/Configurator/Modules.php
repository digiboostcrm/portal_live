<?php
namespace Reports\Configurator;
/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class Modules extends Basic
{
    /**
     * Root module in JS tree.
     * @var string
     * */
    public $rootModule = '';

    /**
     * Link of relationships
     * from root module to selected module.
     * @var string
     * */
    public $relationHistory = '';

    /**
     * Set variable @rootModule
     * @return self
     * */
    public function setRootModule($rootModule)
    {
        $this->rootModule = $rootModule;
        return $this;
    }
    
    /**
     * Set variable @relationHistory
     * @return self
     * */
    public function setRelationName($relationHistory)
    {
        if ($relationHistory) {
            $this->relationHistory = html_entity_decode($relationHistory) . '>';
        }
        return $this;
    }

    /**
     * Get a list of modules in the format
     * @access public
     * @param string module_name
     * @return array
     * @ParamType module_name string
     * @ReturnType array
     * TODO: add recording errors in the log
     */
    public function retrieveRelatedModuleList($module_name) {
        global $beanList, $app_list_strings;
        
        $modules_array = array();
        $hide_relationships = array(
            //'products',
            'project_resource',
            'email_addresses',
            'oauth_tokens',
            //'campaigns',
            'campaign_link',
        );
        if(empty($module_name) || !loadBean($module_name)){
            return array();
        }
        $parent_module = loadBean($module_name);
        $linked_fields=$parent_module->get_linked_fields();
        foreach($linked_fields as $name=>$properties){
            
            //print_r($linked_fields); exit;
            if(in_array($name, $hide_relationships) ){
                continue; 
            }
            $parent_module->load_relationship($name);
            if (isset($parent_module->$name) ) {
              
                $child_module = $parent_module->$name->getRelatedModuleName();
                
                if (isset($app_list_strings['moduleList'][$child_module])
                    and (!isset($properties['reportable'])) // for hide fields as relation
                    //and ($module_name != $child_module)
                ) {
                    $label = $this->getRelationLabel($module_name, $relation_name = $name);
                  
                    $modules_array[$child_module . '-' . $name] = array(
                        'name'=>$child_module,
                        'label'=>$label,
                        'reletion_name' => $name
                    );
                }
            }
        }
        return $modules_array;
    }

}










