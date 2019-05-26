<?php 
 //WARNING: The contents of this file are auto-generated


array_push($job_strings, 'SendReports_Job');
function SendReports_Job()
{
    require_once('modules/rls_Reports/classes/load.php');
    require_once('modules/rls_Reports/license/OutfittersLicense.php');
    global $db, $timedate;
    $now_db = $timedate->nowDb();
    $sql = 'SELECT id
            FROM rls_scheduling_reports
            WHERE deleted=0
                  AND subscribe_active=1
                  AND next_run<\'' . $now_db . '\'';
    $result = $db->query($sql);

    while ($row = $db->fetchByAssoc($result)) {

        if ($RLS_Scheduling_Reports = loadBean('RLS_Scheduling_Reports')->retrieve($row['id'])) {
            $RLS_Scheduling_Reports->last_run = $now_db;

            if($RLS_Scheduling_Reports->single_launch){
                $RLS_Scheduling_Reports->subscribe_active = 0;
            }
            $RLS_Scheduling_Reports->save();
            
            $users_email_list = getFieldsList(
                $RLS_Scheduling_Reports,
                'Users',
                'rls_scheduling_reports_users',
                'email1'
            );
            $contacts_email_list = getFieldsList(
                $RLS_Scheduling_Reports,
                'Contacts',
                'rls_scheduling_reports_contacts',
                'email1'
            );
            $emails_list = array_merge($users_email_list, $contacts_email_list);
            $emails_list = array_unique($emails_list);
            /*if (empty($emails_list)) {
                $RLS_Scheduling_Reports->last_run = $now_db;
                if($RLS_Scheduling_Reports->single_launch){
                    $RLS_Scheduling_Reports->subscribe_active = 0;
                }
                $RLS_Scheduling_Reports->save();
                continue;
            }*/

            $reports_list = getFieldsList(
                $RLS_Scheduling_Reports,
                'rls_Reports',
                'rls_scheduling_reports_rls_reports',
                'id'
            );
            foreach ($reports_list as $report_id) {
                $report_bean = loadBean('rls_Reports')->retrieve($report_id);
                if($RLS_Scheduling_Reports->attach_type == 'PDF'){
                    saveReportPDF($report_bean);
                } else {
                    $report_bean->exportCSVForSchedulers();
                }

                foreach ($emails_list as $email_address) {
                    if(!empty($email_address)){
                        sendReportToEmail($report_bean, $email_address, $now_db, $RLS_Scheduling_Reports);
                    }
                }
                unlink("upload/emails_report.pdf");
                unlink("upload/emails_report.csv");
            }
            /*$RLS_Scheduling_Reports->last_run = $now_db;

            if($RLS_Scheduling_Reports->single_launch){
                $RLS_Scheduling_Reports->subscribe_active = 0;
            }
            $RLS_Scheduling_Reports->save();*/
        }
    }
    return true;
}


function getFieldsList($bean, $module_name, $rel, $field_name)
{
    $fields_list = array();
    $bean->load_relationship($rel);
    $rel_records_list = $bean->$rel->getBeans();
    foreach ($rel_records_list as $rel_record) {
        $record_bean = loadBean($module_name)->retrieve($rel_record->id);
        $fields_list[] = $record_bean->$field_name;
    }
    return $fields_list;
}


function saveReportPDF($report_bean)
{

    \Reports\Chart\Drilldown::resetReport();
    \Reports\Settings\Joins::resetReport();
    \Reports\Data\Grouping::resetReport();
    \Reports\Data\Summarizing::resetReport();
    \Reports\Data\Criterion::resetReport();
    \Reports\Settings\Storage::resetReport();

    \Reports\Settings\Storage::setFocus($report_bean);
    
    $GLOBALS['sendRportsAction'] = true;
    
    $pdf = \Reports\PDF\Factory::load('DefaultTypeForSchedulers');
    $pdf->generateContent();
    $pdf->setNameOfPdf('emails_report.pdf');
    $pdf->savePdfToDisk('upload');
    unset($GLOBALS['sendRportsAction']);
}

function sendReportToEmail($reports_bean, $email_address, $now_db,$RLS_Scheduling_Reports)
{

    $subject = $reports_bean->name . ' ' . $now_db;
    $emailObj = new Email();
    $defaults = $emailObj->getSystemDefaultEmail();
    $mail = new SugarPHPMailer();
    $mail->setMailerForSystem();
    $mail->From = $defaults['email'];
    $mail->FromName = $defaults['name'];
    $mail->ClearAllRecipients();
    $mail->ClearReplyTos();
    $mail->Subject = from_html($subject);
    $mail->Body = from_html($reports_bean->description);
    $mail->prepForOutbound();
    $mail->AddAddress($email_address);

    if($RLS_Scheduling_Reports->attach_type == 'PDF'){
        $file_name = $subject . '.pdf';
        $location = "upload/emails_report.pdf";
        $mime_type = 'application/pdf';
    } else {
        $file_name = $subject . '.csv';
        $location = "upload/emails_report.csv";
        $mime_type = 'text/csv';
    }

    $mail->AddAttachment($location, $file_name, 'base64', $mime_type);
    if ($mail->Send()) {
        return true;
    }
    return false;
}





$job_strings[] = 'email_parser_content_chat_portal';

function email_parser_content_chat_portal() {

    require_once 'modules/InboundEmail/InboundEmail.php';
    require_once('modules/Administration/Administration.php');
    require_once 'custom/biz/function/default_portal_module.php';

    global $db;

    $administrationObj = new Administration();
    $administrationObj->retrieveSettings('PortalPlugin');

    $chatEnable = $administrationObj->settings['PortalPlugin_ChatEnable'];
    $ie_user = $administrationObj->settings['PortalPlugin_GmailUsername'];
    $ie_mailBox = $administrationObj->settings['PortalPlugin_GmailMailbox'];
    $ie_userpass = $administrationObj->settings['PortalPlugin_GmailPassword'];

    if ($chatEnable == '1') {
        $get_ie_settings = "SELECT id, name FROM inbound_email
                    WHERE deleted=0 
                    AND status='Active' 
                    AND mailbox_type != 'bounce' 
                    AND find_in_set('{$ie_mailBox}',mailbox)
                    and email_user = '{$ie_user}'";
        $run_query = $db->query($get_ie_settings);
        $data = $db->fetchByAssoc($run_query);
        $ie = new InboundEmail();
        $ie->retrieve($data['id']);
        $service = $ie->getServiceString();
        $connectString = $ie->getConnectString($service, $ie_mailBox);
        $hostname = $connectString;
        $inbox = imap_open($hostname, $ie_user, $ie_userpass) or die('Cannot connect to Gmail: ' . imap_last_error());
        $schedulerDate = "SELECT date_modified FROM job_queue WHERE target='function::email_parser_content_chat_portal' AND status='done' AND resolution='success' ORDER BY date_modified desc LIMIT 1";
        $dateTime = $db->query($schedulerDate);
        $date = $db->fetchByAssoc($dateTime);
        $timestamp = strtotime($date['date_modified']);
        if ($timestamp > 0) {
            $checkTime = date('r', $timestamp);
        }
        $criteria = "SINCE \"{$checkTime}\" UNDELETED";
        $emails = imap_search($inbox, $criteria, SE_UID);
        $emails = getUidForCreateNewCaseBasedOnEmailUid($emails);
        if ($emails) {
            rsort($emails);
            foreach ($emails as $email_number) {
                $overview = imap_fetch_overview($inbox, $email_number, FT_UID);
                $message = imap_fetchbody($inbox, $email_number,1, FT_UID);
                $chat_emailAdd = getEmailAddressFromText($message);
                $contactId = getCRMContactsBasedOnChatEmail($chat_emailAdd);
                if ($contactId != '') {
                    $conObj = new Contact();
                    $conObj->retrieve($contactId);
                    $accounts = $conObj->get_linked_beans('accounts', 'Account');
                    $subject = $overview['0']->subject;
                    $caseObj = new aCase();
                    $caseObj->name = $subject;
                    $caseObj->chat_description_c = $message;
                    $caseObj->email_uid_c = $overview['0']->uid;
                    $caseObj->save();
                    // relate new created case to contact
                    $caseID = $caseObj->id;
                    $link = 'cases';
                    if ($conObj->load_relationship($link)) {
                        $conObj->$link->add($caseID);
                    }
                    if (!empty($accounts)) {
                        $accountId = $accounts['0']->id;
                        $accountObj = new Account();
                        $accountObj->retrieve($accountId);
                        if ($accountObj->load_relationship($link)) {
                            $accountObj->$link->add($caseID);
                        }
                    }
                    imap_setflag_full($inbox, $email_number, "\\Seen",ST_UID);
                }
            }
        }
        imap_close($inbox);
    }
    return true;
}





array_push($job_strings, 'qbs_qbsugar');
function qbs_qbsugar()
{
    $startTime = microtime(true);
    require_once('include/entryPoint.php');
    require_once('include/MVC/SugarApplication.php');
    require_once('modules/qbs_QBSugar/HandleQBQueue.php');
    $app = new SugarApplication();

    // error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED); ini_set('display_errors', 'On');

    global $sugar_version, $db, $dictionary, $current_user;

    $QBQueueList = array();
    $HQBQueue = new HandleQBQueue();
    $QBQueueList = $HQBQueue->getQBQueue();

    $QBQueueListCount = count($QBQueueList);
    if($QBQueueListCount > 0)   {
        for($i = 0; $i < $QBQueueListCount ; $i++)  {
            global $db;
            $functionNames = array('getContactsfromQB', 'sendContactstoQB', 'getInvoicefromQB', 'sendInvoicetoQB', 'getQuotesfromQB', 'sendQuotestoQB', 'getProductsfromQB', 'sendProductstoQB');

            if(in_array($QBQueueList[$i]['id'], $functionNames))    {
                $flow = 'Quickbooks => Suite';
                $vtigermodules = array('QBCustomer', 'QBQuotes','QBInvoice', 'QBItem');
                if(in_array($QBQueueList[$i]['module'], $vtigermodules))    {
                    $flow = 'Suite => Quickbooks';
                }

                $finalcall = $HQBQueue->ExecuteManualQBQueue($QBQueueList[$i]['module']);
                continue;
            }
            else    {
                $HQBQueue->ExecuteQBQueue($QBQueueList[$i]['id'], $QBQueueList[$i]['module'], $QBQueueList[$i]['source'], $QBQueueList[$i]['queuemode']);
            }
        }
    }
    return true;
}

?>