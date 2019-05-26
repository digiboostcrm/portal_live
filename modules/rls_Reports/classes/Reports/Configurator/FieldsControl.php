<?php
namespace Reports\Configurator;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class FieldsControl extends Fields 
{

    /**
     *  Class name for td tag with list of fields.
     * */
    public $tdClassName = 'rls_addRow';

    /**
     * Return the list of fields html according to selected module
     * @access public
     * @return string
     * @ReturnType string
     * TODO: add recording errors in the log
     */
    public function getFieldsCode($td_class_name = 'rls_addRow') 
    {
        $this->tdClassName = $td_class_name;
        $fields_list = '';
        $fields_html = '';
        $module_name = $this->getSelectedModuleName($this->getModule(), $this->getReletion());
        if(empty($module_name) || !loadBean($module_name)){
            return $fields_html;
        }
        $fields_list = $this->getModulesFieldList();
        $fields_html = $this->buildModuleFieldListHTML($fields_list);
        return $fields_html;
    }

    /**
     * Build the list of fields html according to selected module
     * @access public
     * @param array fields_list
     * @return string
     * @ReturnType string
     */
    public function buildModuleFieldListHTML(array $fields_list) 
    {
        $html = '';
        $module_name = $this->getSelectedModuleName($this->getModule(), $this->getReletion());
        $module_bean = loadBean($module_name);

        $drilldown = \Reports\Chart\Drilldown::getInstance();
        $settings = \Reports\Settings\Storage::getSettings();
        if ($drilldown->isDrilldown()) {
            if ($this->getReletion()) {
                return 'Disabled for drilldown.';
            }
            $list_available_fields = $drilldown->getAvailableSearchFields($settings['data']['module_of_report']);
            $visible_fields_options = $this->buildDrilldownVisibleFieldsOptions($list_available_fields);
            $this->setVisibleFieldsOptions($visible_fields_options);
        }
        
        foreach ($fields_list as $field) {
          if($this->isShowsField($field)){
              $field = $this->setRelateProperties($field);
              $html.= '<tr><td class="' . $this->tdClassName . '"
                               id="MODULES_FIELDS-' . $this->getModule() . '-' . $field['name'] . '-' . $this->getReletion() . '"
                               alt="'.$module_name.'rls_separator'.$field['name'].'rls_separator' . $this->getReletion() . '">'
                               . \translate( $field['vname'], $module_bean->module_name ) .
                      '</td></tr>';
          }
        }
        return $html;
    }

    /**
     * Build MandatoryOptions in proper format from searchdefs data.
     *
     * @param array $list list of searchdefs fields
     * @return array
     * */
     public function buildDrilldownVisibleFieldsOptions($list = array())
     {
          $visible_fields_options = array(
              'name' => array(),
          );
          foreach ($list as $field_name => $data) {
              $visible_fields_options['name'][] = $data['name'];
          }
          return $visible_fields_options;
     }
    
    
    
}
?>
