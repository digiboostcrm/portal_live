<?php
namespace Reports\Data;

/**
 * @access public
 * @author Richlode Solutions
 * @package Reports.Data
 * 
 * TODO: Design Interface for settings storage - Registry
 * 
 */
class Criterion
{
    /**
     * List of aliases for JOINs.
     * 
     * */
    public $globalAliases = array();
    
    /**
     * Singleton store
     * 
     * @var Criterion
     */
    private static $selfInstance = null;

    
    /**
     * Settings storage
     * 
     * @var array
     * */
    private $settings = array();
    
    /**
     * Additional tmp. Fields for SQL query
     * 
     * @var string
     */
    private $additionalFields = null;
    
    /**
     * @AttributeType array
     * Stores the additional Left Joins for SQL text
     */
    public $additionalLeftJoins = array();
    
    /**
     * @AttributeType array
     * Stores the additional Custom Left Joins for SQL text
     */
    private $additionalCustomLeftJoins = array();
    
    /**
     * @AttributeType array
     * Stores the additional Left Joins for relete fields SQL text
     */
    private $additionalLeftJoinsForReletionField = array();

    /**
     * Additional tmp. Where expression
     * 
     * @var string
     * */
    private $additionalWhere = null;
    
    /**
     * Additional tmp. GROUP BY expression
     * 
     * @var string
     * */
    private $additionalGrouping = null;

    /**
     * Criteria for filters.
     *
     * @var string
     * */
     private $criteria_filter = '';
        
    /**
     * Class constructor.
     * 
     *  For Private ininitalization for Singleton
     * 
     * */
    private function __construct()
    {        
    }
    
    
    /**
     * Singleton caller
     * 
     * @return Criterion
     * */
    public static function getInstance()
    {
        if (self::$selfInstance instanceof self){
            return self::$selfInstance;
        }
        
        self::$selfInstance = new self();
        
        return self::getInstance();
    }
    
    /**
     * Reset class settings to default
     * 
     * */
    public static function resetReport(){
        self::$selfInstance = null;
    }
    
    /**
     * Returns INTERVAL offset for date field according to User's timezone
     *
     * @return string  An SQL interval adjustment, similar to "INTERVAL -6 HOUR" for Toronto
     */
    static public function getUserOffsetByTimezone()
    {
        global $current_user, $timedate;
        $userTZ = $current_user->getPreference('timezone');
        if(empty($userTZ) && !$current_user->is_group && !$current_user->portal_only) {
            $userTZ = $timedate::guessTimezone();
            $current_user->setPreference('timezone', $userTZ);
        }
        $london_tz = new \DateTimeZone('Europe/London');
        $user_tz   = new \DateTimeZone($userTZ);
        $london_dt = new \DateTime('now', $london_tz);
        $user_dt   = new \DateTime('now', $user_tz);
         
        $offset    = $london_tz->getOffset($london_dt) - $user_tz->getOffset($user_dt);
    
        return 'INTERVAL '. ($offset / 3600) .' HOUR';
    }
    
    /**
     * Sets the values for $settings property
     * 
     * @return Criterion
     * */
    public function setSettings($settings)
    {      
        $this->settings = $settings;
        return $this;
    }
    
    /**
     * Returns the values for $settings property
     * 
     * @return Criterion
     * */
    public function getSettings()
    {      
        return $this->settings;
    }
    
    /**
     * Build criterion for SQL conversion
     * 
     * Steps:
     *   1. Generating of criterion reproduced with Filter package.
     *      So every control data will be involved in SQL conversion
     * 
     *   2. To be implemented ...
     *    
     * 
     * TODO: 1. Rename to applyFilters
     *       2. Move to bottom
     *       3. change Docblock contains
     * 
     * @return Criterion
     * */
    public function buildCriteria()
    {
        $criteria = array();
        $DisplayFilters = new \Reports\Settings\WIzard\DisplayFilters();
        $settings = $DisplayFilters->get();

        if (isset($settings['controls'])) {
            $this->applyFiltersJoin();
            foreach ($settings['controls'] as $control){
                if ($criterion_string = $this->getCriterionByControl($control)){
                    $criteria[] = $criterion_string;
                }
            }
            if(!empty($criteria)){
                $this->criteria_filter = ' ( ' . implode(' '. trim($settings['operator']) .' ', $criteria) . ' ) ';
            } else {
                $this->criteria_filter = '';
            }


        }
        return $this;
    }    
    
    /**
     * Retrieves criterion string for SQL by Control of filter
     * 
     * @param array $control  Settings for control
     * @return string search criterion
     * 
     * */
    public function getCriterionByControl(array $control)
    {
        $criteria_string = null;
        if (!isset($control['value']) or !$saved_values = $control['value']) { 
            return false;
        }
        if(array_key_exists('field_guide',$control) && array_key_exists('settings_build_criteria',$control)){
            $control['settings_build_criteria']['field_guide'] = $control['field_guide'];
        }

        if($control['settings_build_criteria']['type']=='Skills'){
            $control['settings_build_criteria']['templateSkillsId'] = $control['vardefs']['templateSkillsId'];
            $control['settings_build_criteria']['listEnabledFields'] = $control['vardefs']['listEnabledFields'];
            $control['settings_build_criteria']['listEnabledFields'] = str_replace('{literal}', '', $control['settings_build_criteria']['listEnabledFields']);
            $control['settings_build_criteria']['listEnabledFields'] = str_replace('{/literal}', '', $control['settings_build_criteria']['listEnabledFields']);
            $control['settings_build_criteria']['configSkills'] = $control['vardefs']['configSkills'];
            $control['settings_build_criteria']['module'] = $control['vardefs']['custom_module'];
        }




        if ($field = \Reports\Filter\Factory::loadControl($control['settings_build_criteria'])) {
            $criteria_string = $field->getCriteria($saved_values);
        }
        return $criteria_string;
    }

    public function addSecuritySuiteWhere($beanName, &$where){

        //Check install Security Suite
        if(!method_exists('ACLController', 'requireSecurityGroup')){
            return ;
        }

        $bean = \BeanFactory::getBean($beanName);


        if($bean && $bean->bean_implements('ACL') && \ACLController::requireSecurityGroup($bean->module_dir, 'list') )
        {
            require_once('modules/SecurityGroups/SecurityGroup.php');
            global $current_user;
            $owner_where = $bean->getOwnerWhere($current_user->id);
            $securityGroup =  new \SecurityGroup();
            $group_where = $securityGroup->getGroupWhere($bean->table_name,$bean->module_dir,$current_user->id);
            if(!empty($owner_where)){
                if(empty($where))
                {
                    $where = " (".  $owner_where." or ".$group_where.") ";
                } else {
                    $where .= " AND (".  $owner_where." or ".$group_where.") ";
                }
            } else {

                if(empty($where))
                {
                    $where =  $group_where;;
                } else {
                    $where .= ' AND '.  $group_where;
                }

            }
        }



        return $where;
    }
    
    /**
     * Gets the text of SQL for role
     * 
     * @access private
     * @return string
     */ 
    private function getRole($module_criteria)
    {                        
        global $current_user;
        $role = loadBean('ACLRoles');                
        $user_roles = $role->getUserRoles($current_user->id, FALSE);        
        if(!empty($user_roles)) {
            $user_roles_actions = $role->getRoleActions($user_roles[0]->id);
            switch ($user_roles_actions[ucfirst($module_criteria)]['module']['list']['aclaccess']) {
                case 75:
                    $sql_role = " AND ".$module_criteria.".assigned_user_id='".$current_user->id."'";                
                    break;
                case 80:
                    $sql_role = null;                
                    break;
                case 90:
                    $sql_role = null;                
                    break;
                case '-99':
                    $sql_role = " AND ".$module_criteria.".assigned_user_id='null'";
                    break;
                default:
                    break;
            }  
            return $sql_role;
        } else {
            return $sql_role = null;
        }
    }

    /**
     * Gets the text of SQL
     * 
     * @access public
     * @return string
     */
    public function getSql()
    {
        $criterion = $this->settings['data']['criterion'];
        $this->applyGroupsJoin();
        $can_use_order = false;
        if(empty($criterion['group']) && empty($this->additionalGrouping)
            && !(
                    preg_match('/COUNT/',$this->additionalFields)
                    || preg_match('/SUM/',$this->additionalFields)
                    || preg_match('/MAX/',$this->additionalFields)
                    || preg_match('/MIN/',$this->additionalFields)
                    || preg_match('/AVG/',$this->additionalFields)
            )){
            $can_use_order = true;
        }

        $order_val='ASC';
        if(array_key_exists('order_value',$criterion) && $criterion['order_value']=='d'){ $order_val='DESC';}

            $this->addSecuritySuiteWhere($this->settings['name'],$this->criteria_filter);

          $sql = 
          $criterion['select'] 
          . $this->getAdditionalFields()
          . ' ' 
          . (isset($criterion['from']) && $criterion['from']?' FROM '. $criterion['from']:null)
          . $this->getLeftJoins()
          . (isset($criterion['where']) && $criterion['where']?' WHERE '. $criterion['where']:null) 
          . ($this->criteria_filter ? (isset($criterion['where']) && $criterion['where'] ? ' AND ' .$this->criteria_filter : ' WHERE '.$this->criteria_filter) : null)          
          . $this->getAdditionalWhere()
          . $this->getRole($criterion['from'])
          . (isset($criterion['group']) && $criterion['group'] ? ' GROUP BY '.$criterion['group'] : null)
          . $this->getAdditionalGrouping()
          //. (isset($criterion['order']) && $criterion['order'] ? ' ORDER BY '.$criterion['order'].' '.$order_val : null)
          ;

        $substr=$criterion['order'];

        if(!empty($substr) && $can_use_order){
            $result=strpos($sql,$substr);
            if($result>0)
            {
                $sql .= (isset($criterion['order']) && $criterion['order'] ? ' ORDER BY '.$criterion['order'].' '.$order_val : null);
            }
        }


        return $sql;
    }
    
    /**
     * Returns the part of SQL code for added to SELECT fields 
     * 
     * @return string
     * */
    public function getAdditionalFields()
    {
        if (!$this->additionalFields){
            return false;
        }
        
        $additional_fields = ', '. $this->additionalFields;        
        $this->additionalFields = null;
        
        return $additional_fields;
    }
    
    /**
     * Returns the part of SQL code for added to LEFT JOIN items - tables
     * @access public
     * @return string
     * @ReturnType string
     */
    public function getLeftJoins() {
        if (!$this->additionalLeftJoins and !$this->additionalCustomLeftJoins and !$this->additionalLeftJoinsForReletionField){
            return false;                                  
        }                                                  

        $additionalLeftJoins = ' '. implode(' ', $this->additionalLeftJoins);
        $additionalLeftJoins .= ' '. implode(' ', $this->additionalCustomLeftJoins);
        $additionalLeftJoins .= ' '. implode(' ', $this->additionalLeftJoinsForReletionField);
        
        return $additionalLeftJoins;                         
    }
    
    /**
     * Return the part of SQL code for additional WHERE expr.
     * 
     * @return string
     * */
    public function getAdditionalWhere()
    {
        if (!$this->additionalWhere){
            return false;
        }
        
        $criterion = $this->settings['data']['criterion'];
        
        $additional_where  = (isset($criterion['where']) && $criterion['where']) || $this->criteria_filter ? ' AND ' : ' WHERE ';
        $additional_where .= $this->additionalWhere;
        
        $this->additionalWhere = null;
        
        return $additional_where;
    }
    
    /**
     * Return the part of SQL code for GROUP BY criteria
     * 
     * @return string
     * */
    public function getAdditionalGrouping()
    {
        if (!$this->additionalGrouping){
            return false;
        }

        $criterion = $this->settings['data']['criterion'];
        
        $additional_grouping  = isset($criterion['group']) && $criterion['group'] ? ', ' : ' GROUP BY ';
        $additional_grouping .= $this->additionalGrouping;
        
        $this->additionalGrouping = null;
        return $additional_grouping;
    }
    
    /**
     * Returns categories list using fields in values
     * 
     * FIXME: Take it to closest meeting.
     *        Needs to reduce code of this function - it's too complicated.
     * 
     * @return array
     * @param Grouping $grouping  The object of grouping settings
     * @param integer $index   The number of index of grouping settings
     * */
    public function getCategoriesByGrouping(Grouping $grouping, $index = 0)
    {
        if (!$grouping) {
            return false;
        }
        
        $field_name  = $grouping->getFieldname($index);
        $function = $grouping->getFunction($index);
        $result      = array();
        
        //TODO: Take this into closest meeting. 
        //      Investigate the reasons of it.
        $replaced_field_name = $grouping->getQueriedName($index);
        
        if ($function){ 
            return $grouping
                ->getFunctionObject($function)
                ->getCategories($index);
        } else { 
            $store = new \Reports\Data\Collection();
            $this->setAdditionalGrouping(array(
                $index
            ));
            
            if ($collection = $store->getRows($this)) {
                foreach ($collection as $row){
                    if ($row[$replaced_field_name]) {
                        $result[$row[$replaced_field_name]] = array(
                            'additionalWhere' => array(
                                $field_name. ' = "'.$row[$replaced_field_name].'"'
                            ),
                        );
                    } else {
                        /**
                         * This exception for behaviour when data in field of grouping is NULL.
                         * Without it we'll lose record in datastore.
                         * Example you can see in newOpportunity - Report by Leads, group by Source.
                         * */
                        $result[$row[$replaced_field_name]] = array(
                            'additionalWhere' => array(
                                '(' .$field_name. ' = "'.$row[$replaced_field_name].'" OR '.$field_name.' iS NULL)'
                            ),
                        );                        
                    }
                }
                
                return $grouping->sortCategoriesList($index, $result);
            }
            
            return false;
        }
    }
    
    /**
     * Sets additional fields for SQL expression
     * 
     * @return Criterion
     * @param array $sql  The SQL fields list
     * */
    public function setAdditionalFields(array $fields)
    {
        if ($fields) {
            $separator = ",\n\t\t";
            
            $this->additionalFields = 
                $this->additionalFields 
                    ? $this->additionalFields
                        . $separator
                        . implode($separator, $fields) 
                    : implode($separator, $fields);
        }
        
        return $this;
    }
    
    /**
     * Sets additional where SQL expression
     * 
     * @return Criterion
     * @param string $where  The SQL where expression
     * */
    public function setAdditionalWhere($where)
    {
        if (!is_array($where)) {
            $where = array($where);
        }
        
        $parsed_where = implode(' AND ', $where);        
        $this->additionalWhere = $this->additionalWhere ? $this->additionalWhere. ' AND ' . $parsed_where : $parsed_where;
        
        return $this;
    }
    
    /**
     * Sets additional GROUP BY SQL expression
     * 
     * @return Criterion
     * @param array $sql  The list of additional field for grouping
     * */
    public function setAdditionalGrouping(array $grouping_parameters)
    {
        $group_by  = array();
        $grouping  = \Reports\Data\Grouping::load();

        foreach ($grouping_parameters as $key=>$value) {
            if (is_int($value)) {
                $index =& $value;
                $field_name = $grouping->getFieldname($index);
                
                if ($function   = $grouping->getFunction($index)) {
                    $group_by[] = 
                        $grouping
                            ->getFunctionObject($function)
                            ->getGroupingConversion($index, $this);
                } else {
                    $group_by[] = $field_name;

                    $this->setAdditionalFields(array(
                        $field_name. ' AS ' .$grouping->getQueriedName($index)
                    ));
                }
            }         
        }
        
        $this->additionalGrouping = implode(', ', $group_by); 
        return $this;
    }
    
    /**
     * This method aplies all summaries for query 
     * 
     * @return Criterion
     */
    public function applySummaries() 
    {
        $this->applySummariesJoin();
      
        $summaries = \Reports\Data\Summarizing::load();
        
        if ($summaries_list = $summaries->get()) {
            $fields = array();
            foreach ($summaries_list as $index => $summarizing_item) {
                $fields[] = $summaries->getFunction($index). ' AS '. $summaries->getQueriedName($index);
            }
            $this->setAdditionalFields($fields);
        }
        
        return $this;
    }
    
    
    /**
     * Set Joins from Fields settings.
     *
     * @param array $settings array of fields data for querry
     * @return self
     */
    private function setLeftJoinsFromFieldsSettings(array $settings) {
        $reletion_array = array();
        $joins_for_reletion_fields = array();
        $report_settings = \Reports\Settings\Storage::getSettings();
        
        foreach($settings as $field){

            if(array_key_exists('type',$field) && $field['type'] == 'relate'
                && array_key_exists('id_name',$field) && !empty($field['id_name'])
                && !array_key_exists('vardefs',$field)
                && array_key_exists('module_of_report',$field) && !empty($field['module_of_report'])
            ){
                $module_of_rel_report = loadBean($field['module_of_report']);
                $rel_field_def = $module_of_rel_report->field_defs[$field['id_name']];
                $field['vardefs']=$rel_field_def;
            }

            if(array_key_exists('vardefs',$field)){
                $vardef_cstm = $field['vardefs'];
                if(array_key_exists('type',$vardef_cstm)
                    && $vardef_cstm['type'] == 'relate'
                ){
                    $module_of_rel_report = loadBean($field['module_of_report']);
                    $rel_field_def = $module_of_rel_report->field_defs[$vardef_cstm['id_name']];
                    $field['vardefs']=$rel_field_def;
                }
            }




            if(isset($field['reletion_name'])
                && $field['reletion_name']
            ) {
                $reletion_array[] = $field;
            } elseif (isset($field['vardefs']['source']) and $field['vardefs']['source'] == 'custom_fields') { // for custom_fields in root module without relations
                  $curr_mod_name = $report_settings['data']['module_of_report'];
                  $module_bean = loadbean($curr_mod_name);
                  $this->additionalCustomLeftJoins[$curr_mod_name . '-c'] = ' LEFT JOIN ' . $module_bean->table_name . '_cstm' . 
                                  ' ON ' . $module_bean->table_name . '.id=' . $module_bean->table_name . '_cstm' . '.id_c 
                                    AND ' . $module_bean->table_name . '.deleted=0 ';
            }
        }
        
        foreach ($reletion_array as $rel) {
            $list_history_rel = explode('>', $rel['reletion_name']);
            $curr_mod_name = $report_settings['data']['module_of_report'];
            $old_left_join_table_alias = '';
            $old_relation = '';
            
            foreach ($list_history_rel as $index => $rel_name) {
                if ($rel_name) {
                    $module_bean = loadbean($curr_mod_name);
                    $relation = $rel_name;
                    $module_bean->load_relationship($relation);
                    //if (!isset($module_bean->$relation)) break;
                    $curr_mod_name = $module_bean->$relation->getRelatedModuleName(); // Get module name
                    
                    $alias = $old_relation .'_'. $module_bean->object_name . '_' . $relation;

                    if (!isset($this->globalAliases[$alias])) {
                        $this->globalAliases[$alias]['side_join_table_alias'] = $old_left_join_table_alias;
                    } else {
                        $old_left_join_table_alias = $this->globalAliases[$alias]['side_join_table_alias'];
                    }
                    
                    $join = 
                              $module_bean
                                  ->$relation
                                  ->getJoin(array(
                                      'join_type'=>' LEFT JOIN ',
                                      'join_table_alias'=> $alias,
                                      'left_join_table_alias'=> $old_left_join_table_alias,
                                      'right_join_table_alias'=> $old_left_join_table_alias,
                                  ), true);


                    $old_left_join_table_alias = $alias;
                    $old_relation = $relation;

                    // add alias for relation table
                    if (count($join['join_tables']) > 1) {
                        $new_table_alias = $join['join_tables'][0].rand(10,9000);
                        $this->additionalLeftJoins[$alias] = str_replace(
                            array(' ' . $join['join_tables'][0] . ' ',
                                  $join['join_tables'][0] . '.'
                            ),
                            array($join['join_tables'][0] . ' ' . $new_table_alias . ' ',
                                  $new_table_alias . '.', 
                            ),
                            $join['join']
                        );
                    } else {
                        $this->additionalLeftJoins[$alias] = $join['join'];
                    }

                    // check for selected module with custom field
                    if (count($list_history_rel) == ($index+1)
                        and isset($rel['vardefs']['source'])
                        and $rel['vardefs']['source'] == 'custom_fields'
                    ) {
                        $module_bean = loadbean($curr_mod_name);
                        $this->additionalCustomLeftJoins[$curr_mod_name .'-'. $relation. '-c'] =
                            ' LEFT JOIN ' . $module_bean->table_name . '_cstm' . 
                            ' ON ' . $alias . '.id=' . $module_bean->table_name . '_cstm' . '.id_c 
                              AND ' . $alias . '.deleted=0 ';
                    }

                    if (count($list_history_rel) == ($index+1)){
                        $module_of_field = loadBean($rel['module_of_field']);
                        if(array_key_exists('vardefs',$rel)){
                            $field_vardef = $module_of_field->field_defs[$rel['vardefs']['name']];
                            if(isset($field_vardef['source'])
                                and $field_vardef['source'] == 'custom_fields'
                            ){
                                $alias_cstm = $alias . '_cstm ';
                                $module_bean = loadbean($curr_mod_name);
                                $this->additionalCustomLeftJoins[$curr_mod_name .'-'. $relation. '-c'] =
                                    ' LEFT JOIN ' . $module_bean->table_name . '_cstm' .
                                    ' ON ' . $alias . '.id=' . $module_bean->table_name . '_cstm' . '.id_c
                                AND ' . $alias . '.deleted=0 ';

                            }
                        }


                        $rel_vardef = $module_of_field->field_defs[$rel['field_name']];
                        if($rel_vardef['type']=='relate'){
                            $module_bean = loadbean($curr_mod_name);
                            $FieldsControl = new \Reports\Configurator\FieldsControl;
                            $m_name = $FieldsControl->getSelectedModuleName($rel_vardef['module'], '');
                            $m_bean = loadBean($m_name);
                            $left_join = ' LEFT JOIN ' . $m_bean->table_name . ' as ' . $m_bean->table_name . '_for_relate_field' .
                                ' ON ' . $module_bean->table_name . '_cstm'  .'.'. $rel_vardef['id_name'] . '=' . $m_bean->table_name . '_for_relate_field' . '.id' .
                                ' AND ' . $alias . '.deleted=0 ';
                            $joins_for_reletion_fields[$m_bean->table_name . '_for_relate_field'] = $left_join;
                            $this->additionalLeftJoinsForReletionField[$m_bean->table_name . '_for_relate_field'] = $left_join;
                        }
                    }




                }
            }
        }
        
        //add left joins for custome fields
        foreach($settings as $field){
            if (array_key_exists('module_of_report',$field) && array_key_exists('field_name',$field)) {
                $FieldsControl = new \Reports\Configurator\FieldsControl;
                if(array_key_exists('reletion_name',$field) && !empty($field['reletion_name'])){
                    $module_name = $FieldsControl->getSelectedModuleName($report_settings['data']['module_of_report'], $field['reletion_name']);
                } elseif(array_key_exists('module_of_field',$field)) {
                    $module_name = $field['module_of_field'];
                } else {
                    $module_name = $report_settings['data']['module_of_report'];
                }
                
                $module = loadbean($module_name);
                $field_def = $module->field_defs[$field['field_name']];
                if(
                    (array_key_exists('reletion_name',$field) &&  $field['reletion_name'] == '')
                    && $field_def['type'] == 'relate'
                    && array_key_exists('id_name',$field_def)
                    && array_key_exists('module',$field_def)
                    && !array_key_exists('link',$field_def)
                    && $field_def['name'] != 'assigned_user_id'
                    || (
                         $field_def['type'] == 'currency'

                       )
                ) {
                    if($field_def['type'] == 'currency'){ //need to properly calculate the amount of currency
                        $field_def['module'] = 'Currencies';
                        $field_def['id_name'] = 'currency_id';
                    }
                    $table = $module->table_name;
                    if(
                        (array_key_exists('source',$field_def)
                            && ($field_def['source'] == 'custom_fields'
                                //|| $field_def['source'] == 'non-db'
                            )

                        )
                        ||
                        (array_key_exists('source',$field)
                            && ($field['source'] == 'custom_fields'
                                //|| $field['source'] == 'non-db'
                            )

                        )
                        ||
                        array_key_exists('custom_module',$field_def)

                    ){
                        $table = $table.'_cstm';
                        if($field_def['id_name'] == 'currency_id' && array_key_exists('currency_id',$module->field_defs)){
                            if(array_key_exists('source',$module->field_defs['currency_id']) && $module->field_defs['currency_id']['source'] == 'custom_fields'){
                                $table = $module->table_name.'_cstm';
                            } else {
                                $table = $module->table_name;
                            }
                        }
                    }




                    $m_name = $FieldsControl->getSelectedModuleName($field_def['module'], '');
                    $m_bean = loadBean($m_name);
                    $left_join = ' LEFT JOIN ' . $m_bean->table_name . ' as ' . $m_bean->table_name . '_for_relate_field' .
                                 ' ON ' . $table .'.'. $field_def['id_name'] . '=' . $m_bean->table_name . '_for_relate_field' . '.id' .
                                 ' AND ' . $module->table_name . '.deleted=0 ';
                    $joins_for_reletion_fields[$m_bean->table_name . '_for_relate_field'] = $left_join;
                    $this->additionalLeftJoinsForReletionField[$m_bean->table_name . '_for_relate_field'] = $left_join;
                    
                }
            }
        }
        return $this;
    }

    /**
     *  Insert new array data before old with symbols keys.
     *
     * @param array $insert data for insert
     * @param array $insert_to insert before thess data
     * @return array merged data
     * */
    public function arrayUnshift($insert = array(), $insert_to = array())
    {
        // delete old data with the same keys
        /*foreach ($insert as $key => $value) {
            unset($insert_to[$key]);
        }

        // build new merged data
        foreach ($insert_to as $key => $value) {
            $insert[$key] = $insert_to[$key];
        }*/

        /*foreach ($insert as $key => $value) {
            $insert_to[$key] = $insert[$key];
        }

        return $insert_to;*/
    }
    
    /**
     * Generate and return an array contains Fields for Display Fields.
     * 
     * This method will get settings for Displayable Fields that were configured by User and stores in Bean of Report record, and will generate an array with additional Fields for SELECT of text of SQL.
     * 
     * These additional fields could be used in:
     *  - Spreadsheet building,
     *  - .. to be omplemented
     * @access private
     * @param array_10 settings
     * @return array_10
     * @ParamType settings array
     * @ReturnType array
     */
    private function getFieldsListForDisplayFields(array $settings) {
        $fields_array = array();
        foreach($settings as $field){
            $module_bean = loadBean($field['module_of_field']);
            $field_def = $module_bean->field_defs[$field['field_name']];
            if (!isset($field['its_releted_field'])) {
                $table = $field['table_alias'];
            } else {
                $table = $module_bean->table_name;
            }
            
            if(
                 $field_def['type'] == 'relate' 
                 && array_key_exists('id_name',$field_def) 
                 && array_key_exists('module',$field_def) 
                 && !array_key_exists('link',$field_def)
                 && $field_def['name'] != 'assigned_user_id'
            ) {
              
                $field['field_name'] = $field['id_name'];
                $field['dataField'] = 'grid_display_' . $field['related_module'] . '_' . $field['id_name'];
            }
            
            if (array_key_exists('source',$field) 
               && $field['source'] == 'custom_fields'
            ) {
                if(array_key_exists('reletion_name',$field) && !empty($field['reletion_name'])){
                    $table = $module_bean->table_name.'_cstm';//for custom fields from related modules not isset alias why? line 642

                } else{
                    $table = $table.'_cstm';
                }

            }
            
            if (array_key_exists('its_releted_field',$field)) {
                $table = $table . '_for_relate_field';
            }

            if($module_bean instanceof \Person && $field['field_name'] == 'name'){
                $field['field_name'] = 'last_name';
            }

            $fields_array[] = $table.'.'.$field['field_name'].' as '.$field['dataField'];
        }
        return $fields_array;
    }
    
    
    
    /**
     * Sets additional LEFT JOIN definitions for SQL expression
     * @access public
     * @param array_10 list The SQL LEFT JOINS list
     * @return classes.Reports.Data.Criterion
     * @ParamType list array
     * The SQL LEFT JOINS list
     * @ReturnType classes.Reports.Data.Criterion
     */
    /*public function setAdditionalLeftJoins(array $list) {
        if ($list) {
            // add new joins to up of list additionalLeftJoins
            foreach ($this->additionalLeftJoins as $key => $value) {
                $list[$key] = $value;
            }
            $this->additionalLeftJoins = $list;
        }
        return $this;
    }*/
    
    
    
    
    /**
     * This method will include additional fields for Display Fields, configured by User.
     * 
     * Also it include additional Left Joins for additional tables that uses for storing configured Fields.
     * @access public
     * @return classes.Reports.Data.Criterion
     * @ReturnType classes.Reports.Data.Criterion
     */
    public function applyDisplayFields() {
        $report_settings = \Reports\Settings\Storage::getSettings();
        $this->setLeftJoinsFromFieldsSettings(
            ($report_settings['grid']['columns'])
        );

        $this->setAdditionalFields(
            $this->getFIeldsListForDisplayFields($report_settings['grid']['columns'])
        );
        
        return $this;
    }

    /**
     * This method set additional joins for filters.

     * @return self
     * */
    public function applyFiltersJoin() {
        $settings = '';
        $DisplayFilters = new \Reports\Settings\WIzard\DisplayFilters();
        $settings = $DisplayFilters->get();
        $this->setLeftJoinsFromFieldsSettings(
            ($settings['controls'])
        );
        return $this;
    }

    /**
     * This method set additional joins for groupings.

     * @return self
     * */
    public function applyGroupsJoin() {
        $report_settings = \Reports\Settings\Storage::getSettings();
        if (isset($report_settings['data']['grouping'][0]['fieldList'])
            and $report_settings['data']['grouping'][0]['fieldList']
        ) {
            $this->setLeftJoinsFromFieldsSettings(
                ($report_settings['data']['grouping'][0]['fieldList'])
                
            );
        }
        return $this;
    }

    /**
     * This method set additional joins for summaries.

     * @return self
     * */
    public function applySummariesJoin() {
        $report_settings = \Reports\Settings\Storage::getSettings();
        $this->setLeftJoinsFromFieldsSettings(
            ($report_settings['data']['summaries'])
        );
        return $this;
    }
}

