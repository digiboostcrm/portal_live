<?php

/**
 * The file used to view Edit view for User Group module. 
 * View is displayed if license validated successfully
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
require_once('include/MVC/View/views/view.edit.php');

class bc_user_groupViewEdit extends ViewEdit {

    function preDisplay() {
        parent::preDisplay();
    }

    public function display() {
        global $app_list_strings,$current_user;
       
        require_once 'custom/biz/classes/Portalutils.php';
        $checkLicenceSubscription = Portalutils::validateLicenceSubscription();
        if (!$checkLicenceSubscription['success']) {
            if (!empty($checkLicenceSubscription['message'])) {
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkLicenceSubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkLicenceSubscription['message'])) {
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkLicenceSubscription['message'] . '</div>';
            }
            $current_theme = $current_user->getPreference('user_theme');
             echo "<div><input type='hidden' id='current_theme' name='current_theme' value='{$current_theme}'></div>";
            if ($this->bean->name == 'Default') {
                $defaultGroupArr = unencodeMultienum($this->bean->accessible_modules);
                $d_group_li = '';
                foreach ($defaultGroupArr as $d_group) {
                    if (array_key_exists($d_group, $app_list_strings['moduleList'])) {
                        $d_group_li .= '<li>' . $app_list_strings['moduleList'][$d_group] . '</li>';
                    } else {
                        $d_group_li .= '<li>' . $d_group . '</li>';
                    }
                }
                echo '<script type="text/javascript">
                    $(document).ready(function(){
                      $("#name").parent("td").html("<span id=\"name\" name=\"name\">Default</span>");
                      $("#accessible_modules").parent("td").html("' . $d_group_li . '");
                    });
                </script>';
            }

            parent::display();
        }
    }

}
