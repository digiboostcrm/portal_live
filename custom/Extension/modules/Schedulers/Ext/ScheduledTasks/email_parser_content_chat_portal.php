<?php

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



?>