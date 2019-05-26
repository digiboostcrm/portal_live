<?php
namespace Reports\Configurator;

/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Configurator
 */
class DisplayColumns extends Fields 
{

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
        foreach($fields_list as $field){
            if($field['name'] == $field_name){
                $fields_html = $this->buildSelectedFieldHtml($field);
                break;
            }
        }
        return $fields_html;
    }

    /**
     * Build html for selected fields greed
     * @access public
     * @param array fields_array
     * @return string
     */
    public function buildSelectedFieldsGrid(array $fields_array) 
    {
        $html = '';
        if(empty($fields_array)){
            return $html;
        }
        foreach($fields_array as $field){
            if (!empty($field['module_of_field']) 
                && loadBean($field['module_of_field']) 
                && array_key_exists('visible',$field)
                && $field['visible_at_ew']
            ) {
                $html.=$this->setModule($field['module_of_report'])->setReletion($field['reletion_name'])->buildSelectedFieldHtml($field);
            }
            
        }
        return $html;
    }
    
    
    
    
    /**
     * Build html for one field
     * 
     * @param array fields_settings vardefs(after select field) or saved values(after reload page)
     * @return string
     * TODO: move building html to other function(possibly other class)?
     * */
    public function buildSelectedFieldHtml(array $field_settings) 
    {
      
        global $mod_strings;
        
        $field_settings['vardefs'] = $this->getFieldDefs($field_settings);
        $module_name = $this->getSelectedModuleName($this->getModule(), $this->getReletion());// TO_DO: in future take this name from settings
        $field_name = $this->getModule().'.'. $this->getReletion() . '.' . $field_settings['vardefs']['name'];

        // set field label
        
        $module_bean = loadBean($module_name);
        if (array_key_exists('label',$field_settings)) {
          $checked='';
          $selectedA='';
          $selectedD='';
          $select_style="display: none;";
          $label = $field_settings['label'];
          if(isset($field_settings["radio_btn"])){
              if($field_settings["radio_btn"]=='on'){
                  $checked='checked';
                  if($field_settings["orderBy"]=='a'){
                      $selectedA='selected="selected"';
                      $selectedD='';
                  }
                  else {
                      $selectedA='';
                      $selectedD='selected="selected"';                      
                  }
                  $select_style="display: inline;";
              }
          }
          
              
        } else {
          $checked='';
          $label = \translate($field_settings['vname'], $module_bean->module_name );
          $select_style="display: none;";
          $orderBy='';
        }
        
        $html = '';
        ob_start();
            require(dirname(__FILE__).'/tpls/DisplayColumns.php');
            $data_template = ob_get_contents();
        ob_end_clean();
        
        return $html . $data_template;
    }
    
    
    /**
     * Get html for selected fields greed
     * @access public
     * @param array fields_array
     * @return string
     * @ParamType fieldsArray array
     * @ReturnType string
     */
    public function display() 
    {
        $wizard_displayfields = new \Reports\Settings\WIzard\DisplayFields();
        $settings = $wizard_displayfields->get();
        
        $html = $this->buildSelectedFieldsGrid(
            isset($settings['columns']) ? $settings['columns'] : array()
        );
        return $html;
    }
    
}
?>
