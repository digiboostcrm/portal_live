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

Class ViewPortalConvertedContactsExport extends SugarView {

    function display() {
        require_once 'custom/biz/classes/Portalcontroller.php';
        parent::display();
        global $sugar_version, $sugar_config, $current_user, $db;
        require_once 'custom/biz/classes/Portalutils.php';
        $checkLicenceSubscription = Portalutils::validateLicenceSubscription();
        //check license status
        if (!$checkLicenceSubscription['success']) {
            if (!empty($checkLicenceSubscription['message'])) { //license not validated
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkLicenceSubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkLicenceSubscription['message'])) { //plugin disabled
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkLicenceSubscription['message'] . '</div>';
            }
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
        $portalContactsExport = new Portalcontroller();
        $record = $portalContactsExport->portalContactsExport();
        $html = '';
        if ($record == '') {
                $html .= '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">No Portal Contacts For Export.</div>';
            } else {
                $html .= "<form name='setLayout' class='module-form'>";
                $html .= "<div class='title wp-form-heading'><h1>Export Portal Contacts</h1></div>";
                $html .= "<div class='btn-bar'><input type='button' class='button' name='export_contacts' value='Export Contacts' class='button' onclick='exportContacts();'></div>";
          

        $html .= "</form>";

        $html .= "<script type='text/javascript'>
                
                  function exportContacts(){
              
                       $.ajax({
                             url:'index.php?module=Administration&action=portalHandler&method=portalContactsExport',
                             data :{},
                             success:function(result){
                             var  data = JSON.parse(result);
                              
                             window.location = 'index.php?entryPoint=customexport&uid='+data+'&module=Contacts';
                          }
                   });
                    
                   }


                </script>";
            }
        echo $html;
        }
    }

}
