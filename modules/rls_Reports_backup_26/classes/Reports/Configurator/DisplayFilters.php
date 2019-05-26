<?php
namespace Reports\Configurator;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class DisplayFilters extends Fields 
{
    /**
     * Display only runtime filters.
     * 
     * */
    protected $onlyRuntime = false;
    /**
     * Map association type of field filter to name of filter object.
     * */
    private $mapFilters = array(
        'String' => array(
            array(
                'type' => 'name',
            ),
            array(
                'type' => 'text',
            ),
            array(
                'type' => 'varchar',
            ),
            array(
                'type' => 'phone',
            ),
            array(
                'type' => 'user_name',
            ),
        ),

        'Relate' => array(
            array(
                //'name' => 'id',
                'type' => 'id',
            ),
        ),

        'Periods' => array(
            array(
                'type' => 'date',
            ),
            array(
                'type' => 'datetime',
            ),
            array(
                'type' => 'datetimecombo',
            ),
        ),

        'Dropdown' => array(
            array(
                'type' => 'enum',
            ),
            array(
                'type' => 'radio',
            ),
            array(
                'type' => 'dynamicenum',
            ),
            array(
                'type' => 'radioenum',
            ),
        ),

        'Multiselect' => array(
            array(
                'type' => 'multienum',
            ),
        ),

        'Number' => array(
            array(
                'type' => 'currency',
            ),
            array(
                'type' => 'int',
            ),
            array(
                'type' => 'float',
            ),
            array(
                'type' => 'decimal',
            ),
        ),

        'Bool' => array(
            array(
                'type' => 'bool',
            ),
        ),

        'Skills' => array(
            array(
                'type' => 'skills',
            ),
        ),

        'Users' => array(
            array(
                'name' => 'assigned_user_id',
                'type' => 'relate',
            ),
        ),/**/
    );

    /**
     * 
     * */

    /**
     * Get filter object name by field type.
     *
     * @param string $field_settings field settings
     * @return string filter object name
     * */
    public function getObjNamebyType($field_settings = '')
    {
        $object_name = '';
        foreach ($this->mapFilters as $object_name => $list_types) {
            foreach ($list_types as $value_name => $data) {
                if (is_array($data)) {
                    $all_check = true;
                    foreach ($data as $property_name => $property_value) {
                        if ((isset($field_settings[$property_name])
                            and $property_value != $field_settings[$property_name])
                            or !isset($field_settings[$property_name])
                        ) {
                            $all_check = false;
                        }
                    }
                    if ($all_check) {
                        return $object_name;
                    }
                } 
            }
        }
        return false;
    }
    
    /**
     * Get settings for display filter by field settings.
     *
     * @param array $field_settings array of field filter
     * @return array settings for filter
     * */
    public function getSettings($field_settings)
    {
        $settings = array(
            'control_name' => $this->getModule() . '.' . $this->getReletion() . '.' . $field_settings['name'],
            'type' => $this->getObjNamebyType($field_settings),
            'field_guide'=>$field_settings['field_guide'],
            'templateSkillsId'=>$field_settings['templateSkillsId'],
            'listEnabledFields'=>$field_settings['listEnabledFields'],
            'configSkills'=>$field_settings['configSkills'],
        );
        if (isset($field_settings['options'])) {
            $settings['dropdownName'] = $field_settings['options'];
        }

        // set module for relate fields
        if (isset($field_settings['module'])) {
            $settings['module'] = $field_settings['module'];
        } else {
            $settings['module'] = $this->getSelectedModuleName($this->getModule(), $this->getReletion());// TO_DO: in future take this name from settings
        }
        return $settings;
    }

    /**
     * Get controll object by field type.
     *
     * @param string $field_settings settings of field from vardef
     * @return Filter\Controls\Basic
     * */
    public function getControllbyField($field_settings)
    {
        $object = null;
        if ($this->getObjNamebyType($field_settings)) {
            $object = \Reports\Filter\Factory::loadControl(
                $this->getSettings($field_settings)
            );
        } else {
            exit('<tr><td></td><td colspan="3">Filter "' .
                    $field_settings['type']
                . '" isn\'t implemented.</td></tr>'
            );
        }
        return $object;
    }
    
    /**
     * Get html of selected field
     * @access public
     * @param field_name
     * @return string
     */
    public function getFieldHTML($field_name) 
    {
        $fields_html = '';
        $fields_list = $this->getModulesFieldList();
        foreach ($fields_list as $field) {
            if ($field['name'] == $field_name) {
                $fields_html = $this->buildSelectedFieldHtml($field);
                break;
            }
        }
        return $fields_html;
    }

    /**
     * Build html for one filter
     * 
     * @param array field_settings vardefs(after select field) or saved values(after reload page)
     * @return string
     * */
    public function buildSelectedFieldHtml(array $field_settings) 
    {
        global $mod_strings;

        $field_settings['vardefs'] = $this->setRelateProperties($this->getFieldDefs($field_settings));
        if(array_key_exists('field_guide',$field_settings)){
            $field_guide = $field_settings['field_guide'];
        } else {
            $field_guide = create_guid();
        }

        $field_settings['vardefs']['field_guide'] = $field_guide;
        $condition_html = $this
                          ->getControllbyField($field_settings['vardefs'])
                          ->getConditionHtml(
                              (isset($field_settings['condition'][0])
                              ? $field_settings['condition'][0]
                              : '')
                          );

                          
        $filter_html = $this
                          ->getControllbyField($field_settings['vardefs'])
                          ->getHtml(
                              (isset($field_settings['value'])
                              ? $field_settings['value']
                              : array())
                          );

        $filter_name = $this->getModule().'.'. $this->getReletion() . '.' . $field_settings['vardefs']['name']. '_' . $field_guide;

        $html = '';
        ob_start();
            require(dirname(__FILE__).'/tpls/DisplayFilters.php');
            $data_template = ob_get_contents();
        ob_end_clean();
        
        return $html . $data_template;
    }
    
    /**
     * Build html for selected filter

     * @param array fields_array
     * @return string
     */
    public function buildSelectedFieldsFilter(array $fields_array) 
    {
        $html = '';
        if(empty($fields_array)){
            return $html;
        }

        $drilldown = \Reports\Chart\Drilldown::getInstance();
        $settings = \Reports\Settings\Storage::getSettings();
        
        foreach ($fields_array as $field) {
              // check Runtime and Drilldown settings before display filter
              if ((($this->onlyRuntime and $field['run_time'])
                  or !$this->onlyRuntime)
                      and
                  (($drilldown->isDrilldown()
                  and ($field['module_of_report'] == $settings['data']['module_of_report'])
                  and ($drilldown->isFieldAvailableForDrilldown(
                                      $settings['data']['module_of_report'],
                                      $field['field_name']
                                  )))
                  or !$drilldown->isDrilldown())
              ) {
                  $html.=$this->setModule($field['module_of_report'])
                              ->setReletion($field['reletion_name'])
                              ->setFieldName($field['field_name'])
                              ->buildSelectedFieldHtml($field);
              }
        }

        return $html;
    }

    public function getOperatorDropdawnHTML($value = 'AND'){
        $html_to_return = '';
        $operator_array = array('AND','OR');
        foreach($operator_array as $operator){
            $selected = '';
            if($operator == $value){
                $selected = 'selected';
            }
            $html_to_return.= '<option value="' . $operator . '" ' . $selected . '>' . $operator . '</option>';
        }
        $html_to_return = '<tr class="rls_selected_field">
                               <td width="3%">&nbsp;</td>
                               <td width="25%"><b>Operator</b></td>
                               <td width="14%">
                                   <select name="wizard[DisplayFilters][operator]" id="operator">
                                       ' . $html_to_return . '
                                   </select>
                               </td>
                               <td colspan="3">&nbsp;</td>
                           </tr>';
        return $html_to_return;
    }
    
    /**
     * Get html for selected filters
     * 
     * @param bool $only_runtime display only runtime filters
     * @return string html
     */
    public function display($only_runtime = false) 
    {
        $this->onlyRuntime = $only_runtime;
        $wizard = new \Reports\Settings\WIzard\DisplayFilters();
        $settings = $wizard->get();
        $html_operator = $this->getOperatorDropdawnHTML(
            isset($settings['operator']) ? $settings['operator'] : 'AND'
        );
        $html = $this->buildSelectedFieldsFilter(
            isset($settings['controls']) ? $settings['controls'] : array()
        );

        if(!empty($html)){
            $html = $html_operator.$html;
        }
        return $html;
    }
    
}

