<?php
namespace Reports\Configurator;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class ModulesControl extends Modules 
{

    
    /**
     * Return prepared child modules list according to parent module
     * @access public
     * @param string module_name
     * @return array
     * TODO add recording errors in the log
     */
    public function getModulesCode($module_name) 
    {
        $data_for_return = array();
        if(empty($module_name) || !loadBean($module_name)){
            return array();
        }

        if ($this->relationHistory) {
            $module_name = $this->getSelectedModuleName($this->rootModule, $this->relationHistory);
        } else {
            $module_name = $this->rootModule;
        }
        
        $modules_list = $this->retrieveRelatedModuleList($module_name);
        $data_for_return = $this->buildModuleListHTML($modules_list);
        return $data_for_return;
    }

    /**
     * Prepare child modules list according to parent module
     * @access public
     * @param array modules_array
     * @return string
     */
    public function buildModuleListHTML(array $modules_array) 
    {
        $result = array();
        foreach($modules_array as $m){
            $result[] = array(
                'data' => array(
                    'title' => $m['label'],
                    'attr' => array(
                        //'id'    => 'CHILD_MODULE_PARAMETERS-'. $m['name']. '-' .$m['reletion_name'],
                        'id'    => 'CHILD_MODULE_PARAMETERS-'. $this->rootModule . '-' . $this->relationHistory . $m['reletion_name'],
                        'class' => 'wizard-show-module-fields',
                    )
                ),
                "state" => "closed",
            );
        }
        return $result;
    }
}
?>
