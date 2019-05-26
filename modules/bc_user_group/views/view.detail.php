<?php

/**
 * The file used to view Detail view for User Group module. 
 * View is displayed if license validated successfully
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
require_once('include/MVC/View/views/view.detail.php');

class bc_user_groupViewDetail extends ViewDetail {

    function preDisplay() {
        parent::preDisplay();
    }

    public function display() {
        require_once 'custom/biz/classes/Portalutils.php';

        global $sugar_version, $sugar_config, $current_user, $app_list_strings;
        $current_theme = $current_user->getPreference('user_theme');
        echo "<div><input type='hidden' id='current_theme' name='current_theme' value='{$current_theme}'></div>";

        $checkLicenceSubscription = Portalutils::validateLicenceSubscription();
        if (!$checkLicenceSubscription['success']) {
            if (!empty($checkLicenceSubscription['message'])) {
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkLicenceSubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkLicenceSubscription['message'])) {
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkLicenceSubscription['message'] . '</div>';
            }
            require_once 'custom/biz/function/default_portal_module.php';
            $re_sugar_version = '/(6\.4\.[0-9])/';
            $re_suite_version = '/(7\.[0-9].[0-9])/';
            // Differentiate file for Sugar7 and Sugar6/Suite To Escape Ondemand Issue.
            if ($sugar_config['suitecrm_version'] != '' && preg_match($re_suite_version, $sugar_config['suitecrm_version'])) {
                $current_theme = $current_user->getPreference('user_theme');
                $random_num = mt_rand();
                if ($current_theme == 'SuiteP') {
                    echo "<link href='custom/include/portal_suitecrm_css/SuiteP/suiteP_portal_style.css?{$random_num}' rel='stylesheet'>";
                }
                if ($current_theme == 'SuiteR') {
                    echo "<link href='custom/include/portal_suitecrm_css/SuiteR/suiteR_portal_style.css?{$random_num}' rel='stylesheet'>";
                }
                if ($current_theme == 'Suite7') {
                    echo "<link href='custom/include/portal_suitecrm_css/Suite7/suite7_portal_style.css?{$random_num}' rel='stylesheet'>";
                }
            } else {
                echo "<link rel='stylesheet' href='custom/biz/css/portal_style.css' type='text/css'>";
            }

            $user_accessible_modules = get_modules();
            $portal_accessiable_module = $this->bean->portal_accessiable_module;
            $gropuId = $this->bean->id;
            $portalAccessLevel = json_decode(html_entity_decode($portal_accessiable_module));
            $combineModuleArry = array();
            $actions = array("create", "edit", "delete");

            $fullaccessnonArray = array("AOS_Contracts", "AOS_Invoices", "AOK_KnowledgeBase", "AOS_Quotes","Calls","Meetings");
            $editaccessnonArray = array();
            $createaccessnonArray = array();
            $deleteaccessnonArray = array();
            foreach ($user_accessible_modules as $key => $label) {
                if ($portalAccessLevel == '' || $this->bean->name == 'Default') {
                    foreach ($actions as $num => $action) {
                        if (in_array($key, $fullaccessnonArray)) {
                            $combineModuleArry['EnableModule'][$key]['DisableAction'][] = $action;
                        } else if (in_array($key, $editaccessnonArray) && $action == 'edit') {
                            $combineModuleArry['EnableModule'][$key]['DisableAction'][] = $action;
                        } else if (in_array($key, $createaccessnonArray) && $action == 'create') {
                            $combineModuleArry['EnableModule'][$key]['DisableAction'][] = $action;
                        } else if (in_array($key, $deleteaccessnonArray) && $action == 'delete') {
                            $combineModuleArry['EnableModule'][$key]['DisableAction'][] = $action;
                        } else if ($this->bean->name == 'Default' && $action == 'delete') {
                            $combineModuleArry['EnableModule'][$key]['EnableAction'][] = $action;
                        } else if ($action == 'delete') {
                            $combineModuleArry['EnableModule'][$key]['DisableAction'][] = $action;
                        } else {
                            $combineModuleArry['EnableModule'][$key]['EnableAction'][] = $action;
                        }
                    }
                } else {
                    if (array_key_exists($key, $portalAccessLevel)) {
                        foreach ($portalAccessLevel as $moduleName => $accesslevel) {
                            if ($key == $moduleName) {
                                foreach ($actions as $num => $action) {
                                    if (in_array($action, $accesslevel)) {
                                        $combineModuleArry['EnableModule'][$moduleName]['EnableAction'][] = $action;
                                    } else {
                                        $combineModuleArry['EnableModule'][$moduleName]['DisableAction'][] = $action;
                                    }
                                }
                            }
                        }
                    } else {
                        $combineModuleArry['DisableModule'][$key]['DisableAction'] = $actions;
                    }
                }
            }
            $fullaccessnonmodule = implode(',', $fullaccessnonArray);
            $editaccessnonmodule = implode(',', $editaccessnonArray);
            $createaccessnonmodule = implode(',', $createaccessnonArray);
            $deleteaccessnonmodule = implode(',', $deleteaccessnonArray);
            $html = "";
            $html .= "<input type='hidden' name='fullaccessnonArray' id='fullaccessnonArray' value='{$fullaccessnonmodule}'>";
            $html .= "<input type='hidden' name='createaccessnonArray' id='createaccessnonArray' value='{$createaccessnonmodule}'>";
            $html .= "<input type='hidden' name='editaccessnonArray' id='editaccessnonArray' value='{$editaccessnonmodule}'>";
            $html .= "<input type='hidden' name='deleteaccessnonArray' id='deleteaccessnonArray' value='{$deleteaccessnonmodule}'>";

            $html .= "<p align='center' id='Msg' class='Msg' style='font-weight:bold; color:green;'> </p><form name='portalModuleAccess' method='POST'  method='POST' action='index.php'>"
                    . "<input type='hidden' name='userID' id='userID' value='{$this->bean->id}'>";
            $html .= "<TABLE width='100%' class='list view subpanel-table'  cellpadding=0 cellspacing = 1  >";
            $html .= "<TR><th style='color:#555;'><b>Module name</b></th>
                                    <th style='color:#555;'><b>Status</b> </th>
                                    <th style='color:#555;'><b>Create</b></th>
                                    <th style='color:#555;'><b>Edit</b></th>
                                    <th style='color:#555;'><b>Delete</b></th></TR>";
            foreach ($combineModuleArry as $modulestatus => $value) {
                if ($modulestatus == 'EnableModule') {
                    foreach ($value as $key => $value1) {
                        $module_name = $app_list_strings['moduleList'][$key];
                        $html .= "<tr id='ACLEditView_Access_Header_category'> ";
                        $html.="<td>{$module_name}</td><td>";
                        if ($this->bean->name == 'Default') {
                            $html .= "<select name='status' id='status_{$key}' class='status_Enable_Disable' onchange='enableModuleCheckbox(this);' disabled>";
                        } else {
                            $html .= "<select name='status' id='status_{$key}' class='status_Enable_Disable' onchange='enableModuleCheckbox(this);' >";
                        }



                        $html .= "<option value='Enable' selected>Enable</option>
                                                        <option value='Disable'>Disable</option>
                                                    </select></td>  ";
                        $edit_disable_module_link = '';
                        $create_disable_module_link = '';
                        $delete_disable_module_link = '';
                        $checked_edit = '';
                        $checked_create = '';
                        $checked_delete = '';
                        if ($this->bean->name == 'Default' || in_array($key, $fullaccessnonArray)) {
                            $edit_disable_module_link = "disabled";
                            $create_disable_module_link = "disabled";
                            $delete_disable_module_link = "disabled";
                        } else if ($this->bean->name == 'Default' || in_array($key, $createaccessnonArray)) {
                            $create_disable_module_link = "disabled";
                        } else if ($this->bean->name == 'Default' || in_array($key, $editaccessnonArray)) {
                            $edit_disable_module_link = "disabled";
                        } else if ($this->bean->name == 'Default' || in_array($key, $deleteaccessnonArray)) {
                            $delete_disable_module_link = "disabled";
                        }
                        foreach ($value1 as $num => $access) {

                            if ($num == 'EnableAction' || in_array($key, $fullaccessnonArray)) {

                                if (in_array('create', $value1['EnableAction']) && $num == 'EnableAction') {
                                    $checked_create = 'checked="checked"';
                                }
                                $html .= "<td >  <input type='checkbox' id='create_{$key}' name='action' class='action_dis checked_class_{$key}' value='create_{$key}'  {$create_disable_module_link}  {$checked_create} onclick = 'checkbox(this);'> ";

                                if (in_array('edit', $value1['EnableAction']) && $num == 'EnableAction') {
                                    $checked_edit = 'checked="checked"';
                                }
                                $html .= "</td> <td > <input type='checkbox' id='edit_{$key}' name='action' class='action_dis checked_class_{$key}' value='edit_{$key}'  {$edit_disable_module_link}  {$checked_edit} onclick = 'checkbox(this);'>";

                                if (in_array('delete', $value1['EnableAction']) && $num == 'EnableAction') {
                                    $checked_delete = 'checked="checked"';
                                }
                                $html .= "</td><td > <input type='checkbox' id='delete_{$key}' name='action' class='action_dis checked_class_{$key}' value='delete_{$key}'  {$delete_disable_module_link}  {$checked_delete} onclick = 'checkbox(this);'>";

                                $html .= "</td> ";
                            }
                        }
                        $html .= "</tr>";
                    }
                } else {
                    foreach ($value as $key => $value1) {
                        $module_name = $app_list_strings['moduleList'][$key];
                        $html .= "<tr> ";
                        $html.="<td >{$module_name}</td>
                                                <td > <select name='status' id='status_{$key}' class='status_Enable_Disable' onchange='enableModuleCheckbox(this);'>
                                                        <option value='Enable' >Enable</option>
                                                        <option value='Disable' selected>Disable</option>
                                                    </select></td>  
                                               <td >  <input type='checkbox' id='create_{$key}' name='action' class='action_dis checked_class_{$key}' value='create_{$key}' disabled></td>  
                                                    <td > <input type='checkbox' id='edit_{$key}' name='action' class='action_dis checked_class_{$key}' value='edit_{$key}' disabled></td>  
                                                    <td > <input type='checkbox' id='delete_{$key}' name='action' class='action_dis checked_class_{$key}' value='delete_{$key}' disabled></td>  
                                        </tr>";
                    }
                }
            }

            $html .= "</table><br>" ;
           if($this->bean->name != 'Default'){
                $html .=  "<input type='button' name='save' style='float: right;' value='save' onclick='savemoduleaccesslevel();'><br>";
           }
                    
                  $html  .= "</form>";

            $this->dv->ss->assign('PORTAL_MODULE_ACCESS_LEVEL', $html);
          
            echo "<script type='text/javascript' src='modules/bc_user_group/js/portal_module_access_check.js'>


                  </script>";

            parent::display();
        }
    }

}
