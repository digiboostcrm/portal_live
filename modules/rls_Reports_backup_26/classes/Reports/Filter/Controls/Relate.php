<?php
namespace Reports\Filter\Controls;

/**
 * Class for relate control.
 * 
 *  
 * 
 * @package Reports.Filter.Controls
 * */
class Relate extends Basic
{
    /**
     * Sets the settings
     * 
     * @return self
     * @param array $settings  Settings list for control
     * */
    public function setSettings(array $settings)
    {
        $settings['relate_id_field'] = 'id';
        $settings['relate_name_field'] = 'name';

        $module = loadbean($settings['module']);
        if ($module instanceof Person) {
            $settings['relate_name_field'] = 'last_name';
        }

        parent::setSettings($settings);
        return $this;
    }

    /**
     * Get html for relate control.
     * 
     * @param mixed $current_value The value which was saved in control
     * */
    public function getHtml($current_value = null)
    {
        $rand_key = rand(90000, 900000);
        if (!isset($current_value['guid'])) {
            $current_value = array(
                'relate_name' => '',
                'guid' => '',
            );
        }
        
        $settings = $this->getSettings();
        $html = '';
        ob_start();
            include(dirname(__FILE__).'/../tpls/Relate.php');
            $html = ob_get_contents();
        ob_end_clean();
        
        return $html;
    }

    /**
     * Get url search params for control.
     * 
     * @param mixed $current_value The value which was saved in control
     * @return array
     * */
    public function getUrlParams($current_value = null)
    {
        // not implemented yet
        
        $settings = $this->getSettings();
        $params = array();

        return $params;
    }

}
