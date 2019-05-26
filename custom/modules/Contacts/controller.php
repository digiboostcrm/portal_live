<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
require_once 'modules/Contacts/controller.php';

class CustomContactsController extends ContactsController {

    function action_convertToPortalContacts() {
        $this->view = 'convertToPortalContacts';
        $GLOBALS['view'] = $this->view;
    }

    function action_loadpaginationdata() {

        global $db, $sugar_config,$sugar_version,$current_user;
        $limit = 5;
        $record = $_POST['recordId'];
        if (isset($_POST['pageId']) && !empty($_POST['pageId'])) {
            $page = $_POST['pageId'];
            $start = $limit * ($_POST['pageId'] - 1);
        } else {
            $page = 1;
            $start = 0;
        }
        
        $array = array();
        foreach ($record as $reason => $recordDetail) {
             foreach ($recordDetail as $key => $id) {
                 array_push($array, "'{$id}'");
             }
        }
        $totalCountOfRecords = count($array);
        $num_page = ceil($totalCountOfRecords / $limit);
        $li = implode(" or id = ", $array);
        $query = "select * from contacts where id = {$li}";
        $query .= " limit $start,$limit";
        $pageArry = array();
        $res = $db->query($query);
        $count = $res->num_rows;
        $re_sugar_version = '/(6\.4\.[0-9])/';
        $re_suite_version = '/(7\.[0-9].[0-9])/';
        // Differentiate file for Sugar7 and Sugar6/Suite To Escape Ondemand Issue.
        if ($sugar_config['suitecrm_version'] != '' && preg_match($re_suite_version, $sugar_config['suitecrm_version'])) {
            $current_theme = $current_user->getPreference('user_theme');
            $random_num = mt_rand();
            if ($current_theme == 'SuiteP') {
                $html = "<link href='custom/include/portal_suitecrm_css/SuiteP/suiteP_portal_style.css?{$random_num}' rel='stylesheet'>";
            }
            if ($current_theme == 'SuiteR') {
                $html = "<link href='custom/include/portal_suitecrm_css/SuiteR/suiteR_portal_style.css?{$random_num}' rel='stylesheet'>";
            }
            if ($current_theme == 'Suite7') {
                $html = "<link href='custom/include/portal_suitecrm_css/Suite7/suite7_portal_style.css?{$random_num}' rel='stylesheet'>";
            }
        } else {
            $html = "<link rel='stylesheet' href='custom/biz/css/portal_style.css' type='text/css'>";
        }
         $totalnotconverted = json_encode($record);
        if ($count > 0) {
            $html .= "<table width='100%' class='view list view table-responsive' border='0' cellpadding=0 cellspacing = 1 >";
            $html .= "<thead><tr><th colspan='3'><center><b>Not Converted Records</b><center></th></tr></thead><tbody><tr class='oddListRowS1' height='20'><td>Name</td><td>Reason</td></tr>";

            while ($row = $db->fetchByAssoc($res)) {

                $contact_url = $sugar_config['site_url'] . "/index.php?module=Contacts&action=DetailView&record={$row['id']}";
                $html .= "<tr class='evenListRowS1' height='20'><td><a target='_blank' href='{$contact_url}'>{$row['first_name']} {$row['last_name']}</a></td>";
                if (in_array($row['id'], $record['EmailIdSame'])) {
                    $html .= "<td>Duplicate Email.</td>";
                }if (in_array($row['id'], $record['EmailIdBlank'])) {
                    $html .= "<td>Email field is blank.</td>";
                }
            }
            $html .= "</tr></tbody></table>";
            $html .= "<div class='pagination cstm-pagination'>";
            $prev_page = $page - 1;
            $next_page = $page + 1;
            
            $html .= "<ul class='pagination cstm-pagination'>";
            if ($page != 1) {
                $html .="<li><a href='#' onclick='changePagination({$totalnotconverted},{$prev_page})'>Prev</a></li>";
            }
            if($num_page != '1'){
            for ($i = 1; $i <= $num_page; $i++) {
                if ($i == $page) {
                    $html .= "<li><a href='#' class='active' onclick='changePagination({$totalnotconverted},{$i})'>" . $i . "</a></li>";
                } else {
                    $html .= "<li><a href='#' onclick='changePagination({$totalnotconverted},{$i})'>" . $i . "</a></li>";
                }
            }
            }
            if ($page != $num_page) {
                $html .= "<li><a href='#' onclick='changePagination({$totalnotconverted},{$next_page})'>Next</a></li></ul>";
            }
            $html .= "</div>";
        } else {
            $html .= 'No Data Found';
        }

        ob_clean();
        echo $html;
        exit();
    }

}

?>