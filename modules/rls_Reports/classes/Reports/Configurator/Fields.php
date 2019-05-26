<?php
namespace Reports\Configurator;
/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class Fields extends Basic
{
    /**
     * The current child module
     * @var string
     * */
    private $module = null;

    /**
     * The current field Name
     * @var string
     * */
    private $fieldName = null;
    
    /**
     * The current reletion between child and parent module
     * @var string
     * */
    private $reletion_name = null;
    
    /**
     * Contains forbidden parameters
     * @var array
     * */
    private $forbiddenOptions = array();
    
    /**
     * Contains mandatory parameters
     * @var array
     * */
    private $mandatoryOptions = array();
    
    /**
     * Contains list of visible fields
     * @var array
     * */
    private $visibleFieldsOptions = array();

    /**
     * Step name from wizard.
     * @var string
     * */
    protected $stepName = '';

    /**
     * Get fields defs of current module
     * @access public
     * @return array
     * @ReturnType array
     */
    public function getModulesFieldList($module_name = '') 
    {
        if (!$module_name) {
            $module_name = $this->getSelectedModuleName($this->getModule(), $this->getReletion());
        }
        $moduleBean = loadBean($module_name);
        return $moduleBean->field_defs;
    }

    /**
     *  Get fielddefs from field settings.
     *
     * @param array field_settings 
     * @return array fielddefs from field_settings
     * */
    public function getFieldDefs($field_settings = array())
    {
        $field_defs = (isset($field_settings['vardefs']) ? $field_settings['vardefs'] : $field_settings);
        //$field_defs['reletion_name'] = $field_settings['reletion_name'];
        
        return $field_defs;
    }

    /**
     *  Set stepName property.
     *
     * @param string $name step name from wizard
     * @return $this
     * */
    public function setStepName($name = '')
    {
        if ($name == 'WizardSummaries') {
            $visible_fields_options = array(
                'type' => array(
                    'id',
                    'currency',
                    'int',
                    'float',
                    'decimal',
                ),
            );
            $this->setVisibleFieldsOptions($visible_fields_options);
        }
        
        $this->stepName = $name;
        return $this;
    }

    /**
     *  Get stepName property.
     *
     * @return string step name from wizard
     * */
    public function getStepName($name = '')
    {
        return $this->stepName;
    }

    /**
     * Sets the current module
     * @access public
     * @param string module The name of module
     * @return classes.Reports.Configurator.Fields
     */
    public function setModule($module) 
    {
        $this->module = $module;
        return $this;
    }

    /**
     * Return the name of current module
     * @access public
     * @return string
     * @ReturnType string
     */
    public function getModule() 
    {
        return $this->module;
    }
    
    /**
     * Sets the current field Name
     * @access public
     * @param string module The name of module
     * @return classes.Reports.Configurator.Fields
     */
    public function setFieldName($field_name) 
    {
        $this->fieldName = $field_name;
        return $this;
    }

    /**
     * Return the name of current field Name
     * @access public
     * @return string
     * @ReturnType string
     */
    public function getFieldName() 
    {
        return $this->fieldName;
    }
    
    /**
     * Sets current reletion between child and parent module
     * @access public
     * @param string module The name of module
     * @return classes.Reports.Configurator.Fields
     */
    public function setReletion($reletion_name) 
    {
        $this->reletion_name = html_entity_decode($reletion_name);
        return $this;
    }

    /**
     * Return the name of current reletion
     * @access public
     * @return string
     * @ReturnType string
     */
    public function getReletion() 
    {
        return $this->reletion_name;
    }
    
    
    /**
     * Check fields array
     * @access public
     * @param array field
     * @return boolean
     * */
    public function isShowsField(array $field)
    {
        //$field = (isset($field['vardefs']) ? $field['vardefs'] : $field); // TO_DO fix it global
        $forbiden_options = array(
            'source' => array(
                'non-db'
            )
        );
        $mandatory_options = array(
            'vname' => array()
        );
        
        if(
            $this
                ->setForbiddenOptions($forbiden_options)
                ->checkForbbidenOptions($field)
            ||
            !$this
                ->setMandatoryOptions($mandatory_options) 
                ->checkMandatoryOptions($field)
            ||
            !$this
                ->checkVisibleFieldsOptions($field)
            ||  // hide relate fields-relation
                ($field['type'] == 'id' and $field['name'] != 'id' and isset($field['rname'])) // TODO: maybe move into function*/
            ||  // hide users field (assign, created, modified)
                ($field['name'] == 'assigned_user_id' || $field['type'] == 'assigned_user_name') 
            ||  // hide relate id-fields for all tabs except Filters
                ($field['type'] == 'id' and $field['name'] != 'id' and $_REQUEST['td_class_name'] != 'rls_addFilter') // TODO: maybe move into function*/
        ) {
            if(
                  (

                       $field['type'] == 'relate'
                       && array_key_exists('id_name',$field) 
                       && array_key_exists('module',$field) 
                       && !array_key_exists('link',$field)
                       && $field['name'] != 'assigned_user_id'
                       && ($_REQUEST['td_class_name'] == 'rls_addRow'
                           || $_REQUEST['td_class_name'] == 'rls_addGroupBy'
                            )// show only for DisplayFields tab 6048
                  )
            ) {
                return true;
            } else {
                return false;
            }
            
        } else {
            return true;
        }
    }
    
    
    /**
     * Set forbidden Options
     * @access public
     * @param array options
     * @return classes.Reports.Configurator.Fields
     * forbiddenOptions = array(
     *                        'param_name'=>array(
     *                                  available_param
     *                              )
     *                          )
     * */
    public function setForbiddenOptions(array $options)
    {
        $this->forbiddenOptions = $options;
        
        return $this;
    }
    
    /**
     * Get forbidden options
     * @access public
     * @return array
     * */
    public function getForbiddenOtions()
    {
        return $this->forbiddenOptions;
    }
    
    /**
     * Check whether there is a forbidden configuration parameters
     * @access public
     * @param array field
     * @return boolean
     * */
    public function checkForbbidenOptions(array $field){
        $forbidden_options = $this->getForbiddenOtions();
        if(empty($forbidden_options)){
            return false;
        }
        foreach($forbidden_options as $name=>$available_param){
            if(array_key_exists($name,$field)){
                if(empty($available_param)){
                    break;
                }
                foreach($available_param as $param){
                    if($field[$name] == $param){
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
    
    /**
     * Set mandatory Options
     * @access public
     * @param array options
     * @return classes.Reports.Configurator.Fields
     * mandatoryOptions = array(
     *                        'param_name'=>array(
     *                                  available_param
     *                              )
     *                          )
     * */
    public function setMandatoryOptions(array $options)
    {
        $this->mandatoryOptions = $options;
        return $this;
    }
    
    /**
     * Get forbidden options
     * @access public
     * @return array
     * */
    public function getMandatoryOptions()
    {
        return $this->mandatoryOptions;
    }
    
    /**
     * Check whether there is a mandatory configuration parameters
     * @access public
     * @param array field
     * @return boolean
     * */
    public function checkMandatoryOptions(array $field){
        $mandatory_options = $this->getMandatoryOptions();
        if(empty($mandatory_options)){
            return true;
        }
        
        foreach($mandatory_options as $name=>$available_param){
            if(array_key_exists($name,$field)){
                foreach($available_param as $param){
                    if($field[$name] == $param){
                        break;
                    }
                    return false;
                }
            }else{
                return false;
            }
        }
        return true;
    }

    /**
     * Set visible Fields Options
     * @access public
     * @param array options
     * @return classes.Reports.Configurator.Fields
     * mandatoryOptions = array(
     *                        'name'=>array(
     *                                  field_name1,
     *                                  field_name2,
     *                              )
     *                          )
     * */
    public function setVisibleFieldsOptions(array $options)
    {
        $this->visibleFieldsOptions = $options;
        return $this;
    }
    
    /**
     * Get visible Fields options
     * @access public
     * @return array
     * */
    public function getVisibleFieldsOptions()
    {
        return $this->visibleFieldsOptions;
    }
    
    /**
     * Check whether there is a visible fields (for drilldown)
     * @access public
     * @param array field
     * @return boolean
     * */
    public function checkVisibleFieldsOptions(array $field){
        $fields_options = $this->getVisibleFieldsOptions();
        if(empty($fields_options)){
            return true;
        }

        $available = false;
        foreach($fields_options as $name=>$available_param){
            if(array_key_exists($name,$field)){
                foreach($available_param as $param){
                    if($field[$name] == $param){
                        $available = true;
                    }
                }
            }
        }
        return $available;
    }

    /**
     * set Label and Module name For Relate Field (now use for filters settings)
     * @access public
     * @param field_settings
     * @return array
     */
    public function setRelateProperties($field_settings) 
    {
        //$field_settings = (isset($field_settings['vardefs']) ? $field_settings['vardefs'] : $field_settings); // TO_DO fix it global
        
        if ($field_settings['type'] == 'id'
            and $field_settings['name'] != 'id'
        ) {
            $fields_list = $this->getModulesFieldList();
            foreach ($fields_list as $field) {
                if (isset($field['id_name'])
                    and $field['id_name'] == $field_settings['name']
                ) {
                    $field_settings['vname'] = $field['vname'];
                    $field_settings['module'] = $field['module'];
                    
                    break;
                }
            }
            
        }
        return $field_settings;
    }
    
    

}

