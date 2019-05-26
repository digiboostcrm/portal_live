<?php

/**
 * The file used to view Set Portal Layout
 * display dropdown of module list 
 * on selection of module views need to be configure.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
require_once ('modules/ModuleBuilder/parsers/ParserFactory.php');
require_once ('modules/ModuleBuilder/MB/AjaxCompose.php');
require_once 'modules/ModuleBuilder/parsers/constants.php';

Class ViewSetPortalLayout extends SugarView {

    function display() {
        require_once 'custom/biz/classes/Portalutils.php';
        require_once 'custom/biz/function/default_portal_module.php';
        global $sugar_version, $sugar_config, $current_user, $db;
        //check sugar version and include js file if required
        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="custom/biz/js/jquery/jquery-1.11.0.min.js"></script>';
            echo '<script type="text/javascript" src="custom/biz/js/jquery/jquery-ui.js"></script>';
        }
        //check license status
        $checkLicenceSubscription = Portalutils::validateLicenceSubscription();
        if (!$checkLicenceSubscription['success']) { //license not validated
            parent::display();
            if (!empty($checkLicenceSubscription['message'])) {
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkLicenceSubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkLicenceSubscription['message'])) {  //plugin disabled
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkLicenceSubscription['message'] . '</div>';
            }
            //plugin enabled
            $smarty = new Sugar_Smarty();
            $smarty->display('modules/ModuleBuilder/tpls/includes.tpl');
            $modules = get_modules();

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
            // module list dropdown
            echo "<script type='text/javascript' src='custom/biz/js/layout-set.js'></script>";
            parent::display();
            // $modules = array('Accounts', 'Contacts', 'Leads', 'Cases', 'Opportunities', 'Documents', 'Calls', 'Meetings', 'Tasks', 'Notes');
            $html = '';
            if (!empty($_REQUEST['msg']) && $_REQUEST['msg'] == 'success') {
                $html .= "<div style='margin:10px;text-align:center'><span style='color:green;font-weight:bold;'> Changes saved successfully.</span></div>";
            }
            $html = "<form name='setLayout' class='module-form'>";
            $html .= "<div class='title wp-form-heading'><h1>Portal Layout Setting</h1></div>";
            $html .= "<div class='module-selection'><label>Module : </label><select name='modules' id='modules'>
                    <option value=''>Select</option> ";

            // set modules as per role
            $modules['Contacts'] = 'Contacts';
            asort($modules);
            foreach ($modules as $module => $module_label) {
                $html .= "<option value='{$module}'>{$module_label}</option>";
            }
            $html .= " </select></div>";
            $html .= "<div class='btn-bar'>
                        <input type='button' class='button' name='save' value='Save' class='button' onclick='saveModulelayout();'>
                        <input type='button' class='button' name='cancel' value='Cancel' class='button' onclick='redirectToindex();'>
                 </div></form>";
            echo $html;
        }
        echo "<script type='text/javascript'>
            function redirectToindex(){
                        location.href = 'index.php?module=Administration&action=index';
                    }
                $('document').ready(function(){
                    var panel_index = 0;
                    $('.list-layout-block').css('display','none');
                    $('#modules').change(function(){

                        $('<input>').attr({
                            type: 'hidden',
                            id: 'layout_type',
                            name: 'layout_type'
                        }).appendTo('form');
                        $('#layouts').html('');

                        var module = $('#modules').val();
                        var module_label=$('[name=modules]').find('[value='+module+']').html();
                        if(module){
                        $('#modules').after('<div id=\"layouts\" class=\"layouts\"  style=\"margin:10px\"></div>');
                        if(module == 'Calls' || module == 'Meetings' || module == 'AOK_KnowledgeBase' || module == 'AOS_Contracts' || module=='AOS_Invoices' || module == 'AOS_Quotes'){
                            $('#layouts').append('<div class=\"detail-view edit-view\"><a id=\"detailview\" onclick=\"get_fields('+\"'\" + module+\"'\"+ ','+\"'detail'\"+')\">Detail View</a></div>' +
                                             '<div class=\"list-view\"><a  id=\"listview\" onclick=\"get_list_fields('+\"'\" + module_label+\"'\"+ ','+\"'\" + module+\"'\"+ ','+\"'list'\"+')\">List View</a></div>' +
                                             '<div class=\"list-layout-block\"><div id=\"fieldlist\" style=\"float:left\"></div>') ;
                                
                        }else if(module == 'Contacts'){
                            $('#layouts').append('<div class=\"edit-view\"><a id=\"editview\" onclick=\"get_fields('+\"'\" + module+\"'\"+ ','+\"'edit'\"+')\">Edit View</a></div>' +
                                             '<div class=\"detail-view\"><a id=\"detailview\" onclick=\"get_fields('+\"'\" + module+\"'\"+ ','+\"'detail'\"+')\">Detail View</a></div>' +
                                             '<div class=\"list-layout-block\"><div id=\"fieldlist\" style=\"float:left\"></div>') ;
                        }else{
                        
                        $('#layouts').append('<div class=\"edit-view\"><a id=\"editview\" onclick=\"get_fields('+\"'\" + module+\"'\"+ ','+\"'edit'\"+')\">Edit View</a></div>' +
                                             '<div class=\"detail-view\"><a id=\"detailview\" onclick=\"get_fields('+\"'\" + module+\"'\"+ ','+\"'detail'\"+')\">Detail View</a></div>' +
                                             '<div class=\"list-view\"><a  id=\"listview\" onclick=\"get_list_fields('+\"'\" + module_label+\"'\"+ ','+\"'\" + module+\"'\"+ ','+\"'list'\"+')\">List View</a></div>' +
                                             '<div class=\"list-layout-block\"><div id=\"fieldlist\" style=\"float:left\"></div>') ;
                            }     
                         $('.list-layout-block').css('display','none');
                         }
                    });
                });
              </script>";
    }

}
