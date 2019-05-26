<?php
namespace Reports\Settings;
/**
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.Settings
 */
class Report {

    /**
     * Return prepared array for save to bean
     * @access public
     * @param array $_REQUEST
     * @return array
     */
    public function prepareUserSettings(array $__REQUEST) 
    {
        $return_array = array();
        foreach($__REQUEST as $step_name=>$step){
          
          $pars_class = Factory::load($step_name);
          if ($pars_class) {
              $return_array[$step_name] = $pars_class->parse($step);
          }
        }
        return $return_array;
    }
}
?>
