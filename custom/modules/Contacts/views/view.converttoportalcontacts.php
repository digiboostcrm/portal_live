<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * ****************************************************************************** */


/**
 * ContactsViewValidPortalUsername.php
 * 
 * This class overrides SugarView and provides an implementation for the ValidPortalUsername
 * method used for checking whether or not an existing portal user_name has already been assigned.
 * We take advantage of the MVC framework to provide this action which is invoked from
 * a javascript AJAX request.
 * 
 * @author Collin Lee
 * */
require_once('include/MVC/View/views/view.list.php');

class ContactsViewconverttoportalcontacts extends ViewList {

    function ContactsViewconverttoportalcontacts() {
        parent::ViewList();
    }

    /**
     * @see SugarView::display()
     */
    public function display() {

        echo "<script src='custom/modules/Contacts/js/portal_custom.js' type='text/javascript'></script>";
        require_once('include/entryPoint.php');
        require_once 'custom/biz/classes/Portalutils.php';
        global $sugar_version, $sugar_config, $current_user, $db;
        $re_sugar_version = '/(6\.4\.[0-9])/';
        $re_suite_version = '/(7\.[0-9].[0-9])/';
        
        $checkLicenceSubscription = Portalutils::validateLicenceSubscription();
        if (!$checkLicenceSubscription['success']) { //license not validated
           
            if (!empty($checkLicenceSubscription['message'])) {
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkLicenceSubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkLicenceSubscription['message'])) {  //plugin disabled
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkLicenceSubscription['message'] . '</div>';
            }
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
        $allContactemailArry = array();
        $getEmailQuery = "SELECT email_addr_bean_rel.bean_id,email_addresses.email_address AS email
                FROM email_addresses
                LEFT JOIN email_addr_bean_rel 
                    ON email_addresses.id = email_addr_bean_rel.email_address_id AND email_addresses.deleted = 0 AND email_addr_bean_rel.deleted = 0
                WHERE email_addr_bean_rel.bean_module = 'Contacts'";

        $run_Query = $db->query($getEmailQuery);
        while ($row = $db->fetchByAssoc($run_Query)) {
            array_push($allContactemailArry, $row['email']);
        }
        $countOfUniqueEmail = array_count_values($allContactemailArry);
        
        if ($current_user->is_admin) {
            $records = json_decode(html_entity_decode($_REQUEST['ids']));
            $oUsergroup = new bc_user_group();
            $oUsergroup->retrieve_by_string_fields(array('is_portal_accessible_group' => 1));

            $index = '0';
            $alreadyConvertContactId = array();
            $exportConvertRecords = array();
            $isConvertContactId = array();
            $notConvertContactId = array();
            foreach ($records as $key => $recordID) {
                $contacts = new Contact();
                $contacts->retrieve($recordID);
                $contactEmail = $contacts->email1;
                $emailCount = $countOfUniqueEmail[$contactEmail] ;

                if ($contactEmail == '') {
                    $notConvertContactId['EmailIdBlank'][$index] = $recordID;
                    $index++;
                } else if ($emailCount > 1) {
                    $notConvertContactId['EmailIdSame'][$index] = $recordID;
                    $index++;
                } else if (!$contacts->enable_portal_c) {
                    $_REQUEST['frm_convert'] = true;
                    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
                    $pass = array(); //remember to declare $pass as an array
                    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                    for ($i = 0; $i < 6; $i++) {
                        $n = rand(0, $alphaLength);
                        $pass[] = $alphabet[$n];
                    }
                    $contacts->load_relationship('bc_user_group_contacts');
                    $contacts->bc_user_group_contacts->add($oUsergroup->id);
                    $contacts->username_c = $contactEmail;
                    $contacts->register_from_c = 'CRM';
                    $contacts->password_c = implode($pass);
                    $contacts->enable_portal_c = 1;
                    $contacts->save();
                    array_push($exportConvertRecords, $recordID);
                    $isConvertContactId[$index] = $recordID;
                    $index++;
                } else {
                    $alreadyConvertContactId[$index] = $recordID;
                    $index++;
                }
            }
            $TotalisConvertContactsId = count($isConvertContactId);
            $TotalAlreadyConvertContactId = count($alreadyConvertContactId);
            $totalPortalRecord = array();
            $notConvertContact = array();
            $totalCountOfRecords = count($notConvertContactId['EmailIdSame']) + count($notConvertContactId['EmailIdBlank']);
            $notConvertContact = json_encode($notConvertContactId);
            $totalPortalRecord = json_encode($exportConvertRecords);
            $totalRecords = count($records);
            $html = "<br><br><input type='hidden' name='record_to_convert' value='{$totalPortalRecord}'>";
            $html .= "<div class='main wp-cstm-main list-view-rounded-corners'>";
            $html .= "<TABLE width='100%' class='view list view table-responsive' border='0' cellpadding=0 cellspacing = 1  >";
            $html .= "<thead><tr><th>Name</th><th>Count Of Record</th></tr></thead>";
            $html .= "<tbody><tr class='oddListRowS1' height='20'><td>Converted Records</td><td>{$TotalisConvertContactsId}</td></tr>";
            $html .= "<tr class='evenListRowS1' height='20'><td>Already Converted Records</td><td>{$TotalAlreadyConvertContactId}</td></tr>";
            $html .= "<tr class='oddListRowS1' height='20'><td>Failed To Convert Records</td>";
            if ($totalCountOfRecords > 0) {
                $page = '1';
                $html .= "<td><a href='#' id='view_not_converted_records' onclick='changePagination({$notConvertContact},{$page});'>{$totalCountOfRecords}</a></td></tr>";
            } else {
                $html .= "<td>0</td></tr>";
            }
            $html .= "<tr class='evenListRowS1' height='20'><td>Total Selected Records</td><td>{$totalRecords}</td></tr></tbody>";
            $html .= "</TABLE>";

            $html .= "</div>";
            $html .= "<div id='not_converted_records_view' style='display:none ;'>";
            $html .="</div>";
            $contactListViewurl = "'" . $sugar_config['site_url'] . "/index.php?module=Contacts" . "'";
            if (count($exportConvertRecords) > 0) {
                $html .= "<div class='btn-bar'><input type='button' class='button' name='export_contacts' id='export_contacts' value='Export Contacts' onclick='viewContactRecords(this);'>";
            } else {
                $html .= "<div class='button cstm-button' style ='color:red !important'>No Converted Contact For Export</div>";
            }
            $html .= '&nbsp;&nbsp;&nbsp;<input title="Cancel" class="button" onclick="window.location =' . $contactListViewurl . '" type="submit" name="button" value="Back To Contact Listview"></div>';
        } else {
            $html = '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">You dont have access for this. please contact Administrator.</div>';
        }echo $html;
    }
    
        }

}
