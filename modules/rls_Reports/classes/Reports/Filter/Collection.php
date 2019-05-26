<?php
namespace Reports\Filter;

/**
 * This class is intended for getting Filters collection.
 * 
 * Builds and display all controls for filtering form
 * 
 * @access public
 * @author Richlode Solutions
 * @package Reports.Filter
 */
class Collection 
{
    /**
     * @var array
     * Loaded settings for Form of filter
     */
    private $settings = array();
    
    /**
     * @var array
     * The list of controls that was generated
     */
    private $controlsList = array();

    /**
     * This method will draw the Form of the filter
     * 
     * @access public
     * @param array $filter_settings  Settings for filter, retrieved from metadata
     * @return string
     */
    public function display(array $filter_settings)
    { 
        if (isset($filter_settings) 
            && is_array($filter_settings)
        ) {
            $html = null;
            
            $this
              ->setSettings($filter_settings)
              ->buildListOfControls();
            
            foreach ($this->getControlsList() as $control){
                $html .= '<td width="150" align="right">'.$control['label'].'</td><td>'.$control['html'].'</td>';
            }
            
            $html = '
            <table>
              <tr>
                '.$html.'
              </tr>
            </table>';
            
            return $html;
        }
    }

    /**
     * Returns current values for control
     * 
     * @access private
     * @param string control_name
     * @return string
     */
    private function getCurrentValue($control_name)
    {
        // Not yet implemented
    }

    /**
     * Return HTML code of the control
     * 
     * @access private
     * @param array $settings settings for control
     * @return string
     */
    private function getHtmlForControl(array $settings)
    {
        global $app_list_strings;
        
        $type = $settings['type'];
        $html = null;
        $saved_filter = \Reports\Settings\Storage::getFilterValues();
        $field_value = isset($saved_filter[$settings['name']]) ? $saved_filter[$settings['name']] : array();

        
        if ($field = \Reports\Filter\Factory::loadControl($settings)) {
            $html = $field->getHtml($field_value);
        }
        
        return $html;
    }

    /**
     * This method will generate the list of all controls of FIlter
     * 
     * @access private
     */
    private function buildListOfControls()
    {
        $settings = $this->getSettings();
        
        foreach ($settings['controls'] as $key => $control){
            $this->controlsList[] = array(
                'label' => $control['label'],
                'html'  => $this->getHtmlForControl($control),
            );
        }        
    }
    
    /**
     * This method will extend filter settings with names for every control of filter.
     * 
     * @return array
     */
    public function setControlNamesToSettings() 
    {
        $filter_settings = $this->getSettings();
        
        foreach ($filter_settings['controls'] as $key => $control) {
            $control['name'] = $this->getNameForControl($key);
            $filter_settings['controls'][$key] = $control;
        }
        $filter_settings['controls']['grouping'] = array(
                'label' => 'Grouping',
                'name'  => 'sbr_grouping',
                'type'  => 'Grouping',
            );
        
        return $filter_settings;
    }
    
    /**
     * Returns the name for control by number in list of all.
     * 
     * @param integaer $index   Number of control
     * @return string
     */
    public function getNameForControl($index)
    {
        $filter_settings = $this->getSettings();
        
        return strtolower($filter_settings['controls'][$index]['type']). '_' .$index;
    }
    
    /**
     * @access public
     * @param array settings
     * @return self
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
        $this->settings = $this->setControlNamesToSettings();
               
        return $this;
    }

    /**
     * Return the loaded settings.
     * 
     * @access public
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @access public
     * @param array controlsList
     * @ParamType controlsList array
     */
    public function setControlsList(array $controlsList)
    {
        $this->controlsList = $controlsList;
    }

    /**
     * @access public
     * @return array
     * @ReturnType array
     */
    public function getControlsList()
    {
        return $this->controlsList;
    }
}
