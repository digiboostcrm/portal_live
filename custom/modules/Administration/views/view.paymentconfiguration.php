<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/**
 * The file used to set Portal url configuration need to provide url to connect with Customer Portal 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
class Viewpaymentconfiguration extends SugarView {

    function display() {
        /**
         *
         * This variable contain array and that array contain all bad value of fields that we want to hide in dropdown list.
         * 
         */
//         $badFields = array(
//        'account_description',
//        'contact_id',
//        'lead_id',
//        'opportunity_amount',
//        'opportunity_id',
//        'opportunity_name',
//        'opportunity_role_id',
//        'opportunity_role_fields',
//        'opportunity_role',
//        'campaign_id',
//        // User objects
//        'id',
//        'date_entered',
//        'date_modified',
//        'user_preferences',
//        'accept_status',
//        'user_hash',
//        'authenticate_id',
//        'sugar_login',
//        'reports_to_id',
//        'reports_to_name',
//        'is_admin',
//        'receive_notifications',
//        'modified_user_id',
//        'modified_by_name',
//        'created_by',
//        'created_by_name',
//        'accept_status_id',
//        'accept_status_name',
//    );
        /** $badmodule
         * This variable contain array and that array contain all bad value of module that we want to hide in dropdown list.
         */
//         $badmodule = array(
//        
//        'Documents',
//        'Emails',
//        'Campaigns',
//        'Calls',
//        'Meetings',
//        'Tasks',
//        'Notes',
//        'Cases ',
//        'Prospects',
//        'ProspectLists',
//        'Project',
//        'AM_ProjectTemplates',
//        'FP_events',
//        'FP_Event_Locations',
//        'AOS_Product_Categories',
//        'AOS_PDF_Templates',
//        'jjwg_Maps',
//        'jjwg_Markers',
//        'jjwg_Areas',
//        'jjwg_Address_Cache',
//        'AOR_Reports',
//        'AOW_WorkFlow',
//        'AOK_KnowledgeBase',
//        'AOK_Knowledge_Base_Categories',
//      );

        require_once 'custom/biz/classes/Portalutils.php';

        global $mod_strings, $app_strings, $db;

        //fetch p_payment module config data from database
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
        $oAdministration = new Administration();
        $oAdministration->retrieveSettings('payment');
        $payment_amount_module = $oAdministration->settings['payment_amount_module'];
        $payment_amount_fields = $oAdministration->settings['payment_amount_feilds'];
        $payment_payment_module = $oAdministration->settings['payment_payment_module'];
        $payment_payment_feilds = $oAdministration->settings['payment_payment_feilds'];
        $payment_status = $oAdministration->settings['payment_status'];

        // retrive all module 
//        $controller = new TabController();
//        $tabs = $controller->get_tabs_system();
        //  $modules_display=array("AOS_Quotes" => "AOS_Quotes","AOS_Invoices" => "AOS_Invoices");
        $modules_display = array("AOS_Invoices" => "AOS_Invoices");
        $enabled = array();
        foreach ($modules_display as $key => $value) {
            $enabled[] = array("module" => $key, 'label' => translate($key));
        }
        if ($db->dbType == "mssql") {
            $sq = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES";
        } else {
            $sq = "SHOW TABLES ";
        }
        $res = $db->query($sq);
        while ($row_table[] = $db->fetchByAssoc($res)) {
            
        }
        // chaeck if status is disable then not check that condition.   
        if ($payment_status == 1) {

            $moduleClass1 = BeanFactory::getBeanName($payment_amount_module);
            $moduleClass2 = BeanFactory::getBeanName($payment_payment_module);
            $focus1 = new $moduleClass1();
            $focus2 = new $moduleClass2();

            // retrive amount_module's fields     
            foreach ($focus1->field_defs as $key => $field_def) {
//        if ((($field_def['type'] == 'varchar')  || ($field_def['type'] == 'name') || ($field_def['type'] == 'enum') ||
//                        ($field_def['type'] == 'float') || ($field_def['type'] == 'currency') ||
//                        ($field_def['type'] == 'int')) && ($field_def['source'] != 'non-db') && (strpos($field_def['name'], 'id') == false)) {
                if (($field_def['type'] == 'currency')) {

                    $row1 = $key;
                    $row = $field_def['vname'];
                    $label = translate($row, $payment_amount_module);
                    $collectionKey[] = array("name" => $row1, "value" => $label);
                }
                //       else if ((in_array($field_def['name'], $this->badFields))) {
//                    continue;
//                }
            }
            // retrive payment_module fields  
            foreach ($focus2->field_defs as $key => $field_def) {
                if ($field_def['name'] == 'status') {
                    $row1 = $key;
                    $row = $field_def['vname'];
                    $label = translate($row, $payment_payment_module);
                    $collectionKey1[] = array("name" => $row1, "value" => $label);
                }
            }
        }
        // assign value to edit tpl file 
        $this->ss->assign('MOD', $mod_strings);
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('payment_status', $payment_status);
        $this->ss->assign('payment_amount_module', $payment_amount_module);
        $this->ss->assign('payment_amount_fields', $payment_amount_fields);
        $this->ss->assign('payment_payment_module', $payment_payment_module);
        $this->ss->assign('payment_payment_feilds', $payment_payment_feilds);
        $this->ss->assign('collectionKey', $collectionKey);
        $this->ss->assign('collectionKey1', $collectionKey1);
        // $this->ss->assign('badFields', $badFields);
        $this->ss->assign('amount_module', $enabled);
        $this->ss->assign('table', $row_table);
        $this->ss->display('custom/modules/Administration/tpls/paymentconfiguration.tpl');


        parent::display();
    }
    }

}
