<?php
namespace Reports\Filter\Controls;

/**
 * 
 * 
 * */
class String extends Basic
{
    
    /**
     * Get html for control.
     * 
     * @param mixed $current_value The value which was saved in control
     * @return string
     * */
    public function getHtml($current_value = null)
    {
        $settings = $this->getSettings();        
        $html = null;
        $settings_array = explode(".", $settings['control_name']);
        $end_settings_array = end($settings_array);                             
        
        if ($end_settings_array == 'user_name' 
            //&& isset($_REQUEST['record'])
            //&& !empty($_REQUEST['record'])
        ) {
            
            global $db;
            $record = '';
            if(array_key_exists('record',$_REQUEST)){
                $record = $_REQUEST['record'];
            }

            $query_users = "SELECT id, user_name, first_name, last_name FROM users WHERE deleted=0";
            $query_roles = "SELECT reports_settings FROM rls_reports WHERE deleted=0 AND id='".$record."'";
            $result_select_users = $db->query($query_users);
            $result_select_roles = $db->query($query_roles);
            $html  = '<select name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'].'][value][]" id="filter_values-'.$settings['control_name'] . '_' . $settings['field_guide'].'">';
            
            $result_array_roles = $db->fetchByAssoc($result_select_roles);
            $reports_settings = unserialize(
                html_entity_decode(($result_array_roles['reports_settings']))
            );                        
            
            foreach ($reports_settings['DisplayFilters']['controls'] as $key => $value) {
            
                $settings_arrays = explode(".", $key);
                $end_settings_arrays = end($settings_arrays);
                $end_settings_arrays = str_replace('_'.$settings['field_guide'],'',$end_settings_arrays);
                if ($end_settings_arrays == 'user_name') {
                    
                    $current_user_db = $value['value'][0];
                    if($current_user_db == 'current_user') {
                        $html .= '<option selected="selected" value="current_user">Current User</option>';
                    } else {
                        $html .= '<option value="current_user">Current User</option>';
                    }                    
                }
            }

            if(empty($record)){
                $html .= '<option selected="selected" value="current_user">Current User</option>';
            }

            $rls_users_list = array();
            $rls_selected_user = '';

            while($result_array_users = $db->fetchByAssoc($result_select_users)) {
                
                $full_name = $result_array_users['first_name'].' '.$result_array_users['last_name'];
                $full_name = trim($full_name);
                if(($result_array_users['user_name'] == $current_value[0]) && ($current_user_db != 'current_user')) {
                    $rls_selected_user = $result_array_users['id'];
                    $rls_users_list[$result_array_users['id']] = $full_name;
                } else {
                    $rls_users_list[$result_array_users['id']] = $full_name;
                }
            }
            asort($rls_users_list);
            foreach($rls_users_list as $rls_user_id => $rls_user_name){
                if($rls_user_id == $rls_selected_user){
                    $html .= '<option selected="selected" value="'.$rls_user_id.'">'.$rls_user_name.'</option>';
                } else {
                    $html .= '<option value="'.$rls_user_id.'">'.$rls_user_name.'</option>';
                }
            }

            $html .= '</select>';            
        } else {                        
            $html  = '<input
                     name="wizard[DisplayFilters]['.$settings['control_name'] . '_' . $settings['field_guide'].'][value][]"
                     id="filter_values-' . $settings['control_name'] . '_' . $settings['field_guide']. '"
                     size="50"
                     value="' . (isset($current_value[0]) ? htmlentities(
                                                                html_entity_decode($current_value[0], ENT_QUOTES, 'UTF-8')
                                                                , ENT_QUOTES
                                                                , 'UTF-8') : '') . '"> ';
        }        
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
        $settings = $this->getSettings();
        $params = array();

        $drilldown = \Reports\Chart\Drilldown::getInstance();
        if (is_array($current_value)) {
            $search_field_name = $settings['field_name_for_drilldown'] . $drilldown->getSearchPrefix();
            foreach ($current_value as $key => $value) {
                $params[$search_field_name] = $value;
            }
        }
        return $params;
    }

}
