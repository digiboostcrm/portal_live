<?php

function getContactsReletedModules() {

    global $db, $current_user, $app_list_strings;
    $query = "SELECT * FROM relationships
                  WHERE lhs_module LIKE 'Contacts' 
                  or `rhs_module` LIKE 'Contacts' 
                  ORDER BY `relationship_type` DESC ";
    $runQuery = $db->query($query);
    $modules_list = array();
    while ($result = $db->fetchByAssoc($runQuery)) {
        $module = ($result['lhs_module'] == 'Contacts') ? $result['rhs_module'] : $result['lhs_module'];
        $modules_list[] = $module;
    }
    $indivsualModules = array();
    $indivsualModules = getIndivisualModules();
    $modules_list = array_unique($modules_list);
    $allModules = array_merge($modules_list, $indivsualModules);
    sort($allModules);
    $modules = array();
    $excluded_modules = array('Home', 'Calendar', 'Bugs', 'Emails', 'KBDocuments', 'Forecasts', 'Iframeapp', 'Campaigns', 'Users', 'ProspectLists');
    foreach ($allModules as $module) {
        if (!in_array($module, $excluded_modules)) {
            $modules[] = $module;
        }
    }
// set modules as per role
    $userAccessibleModulesList = query_module_access_list($current_user);
    foreach ($modules as $module) {
        if (in_array($module, $userAccessibleModulesList)) {
            $access_right_modules[] = $module;
        }
    }
    // get rename module label

    foreach ($app_list_strings['moduleList'] as $module_key => $module_label) {
        if (in_array($module_key, $access_right_modules)) {
            $module_list[$module_key] = $module_label;
        }
    }

    $result = array();
    foreach ($module_list as $module_keys => $module_labels) {
        $result[$module_keys] = $module_labels;
    }
    asort($result);

    return $result;
}

function getIndivisualModules() {
    global $db, $current_user, $app_list_strings;
    require_once('modules/MySettings/TabController.php');
    $controller = new TabController();
    $tabArray = $controller->get_tabs($current_user);
    $modules = $tabArray[0];
    $ind_modules = array();
    $modules_list = array();
    foreach ($modules as $module) {
        $query = "SELECT * FROM relationships
                  WHERE lhs_module LIKE '$module' 
                  or `rhs_module` LIKE '$module' 
                  ORDER BY `relationship_type` DESC ";
        $runQuery = $db->query($query);
        while ($result = $db->fetchByAssoc($runQuery)) {
            $module_name = ($result['lhs_module'] == $module) ? $result['rhs_module'] : $result['lhs_module'];
            $modules_list[$module][] = $module_name;
        }
        if (getOnlyUserRelatedModules($modules_list[$module])) {
            $ind_modules[] = $module;
        }
    }
    return $ind_modules;
}

function getOnlyUserRelatedModules(&$array) {
    foreach ($array as $x) {
        if ($x !== 'Users') {
            return false;
        }
    }
    return true;
}

function get_modules() {
    global $app_list_strings, $current_user, $sugar_version, $sugar_flavor, $sugar_config;
    $administrationObj = new Administration();
    $administrationObj->retrieveSettings("PortalPlugin");
    $portal_url = $administrationObj->settings['PortalPlugin_PortalInstance'];

    $pushmodules = array();
    $re_sugar_versionForPro75 = '/(7\.5\.[0-9])/';
    $re_sugar_versionForPro76 = '/(7\.6\.[0-9])/';
    $re_sugar_versionForPro77 = '/(7\.7\.[0-9])/';
    $re_sugar_versionForPro78 = '/(7\.8\.[0-9])/';
    $re_sugar_versionCE = '/(6\.5\.[0-9])/';
    $basic_modulesArray = array('Accounts', 'Cases', 'Documents', 'Calls', 'Meetings', 'Notes');
     if ($portal_url == 'Drupal' || $portal_url == 'Joomla') {

        if (($key = array_search('Contacts', $basic_modulesArray)) !== false) {
            unset($basic_modulesArray[$key]);
        }
    }
    if ((in_array($sugar_flavor, array('PRO'))) && (preg_match($re_sugar_versionForPro75, $sugar_version) || preg_match($re_sugar_versionForPro76, $sugar_version) || preg_match($re_sugar_versionForPro77, $sugar_version))) {
        $pushmodules = array('Quotes');
    } else if (in_array($sugar_flavor, array('CE')) && preg_match($re_sugar_versionCE, $sugar_version) && (!is_null($sugar_config['suitecrm_version']) || $sugar_config['suitecrm_version'] != '')) {
        $pushmodules = array('AOK_KnowledgeBase', 'AOS_Contracts', 'AOS_Invoices', 'AOS_Quotes');
    } else if ((in_array($sugar_flavor, array('ENT'))) && (preg_match($re_sugar_versionForPro75, $sugar_version) || preg_match($re_sugar_versionForPro76, $sugar_version) || preg_match($re_sugar_versionForPro77, $sugar_version) || preg_match($re_sugar_versionForPro78, $sugar_version))) {
        $pushmodules = array('KBDocuments', 'Quotes');
    }

    $All_modulesArrays = array_merge($basic_modulesArray, $pushmodules);

    foreach ($All_modulesArrays as $module_name) {
        if (array_key_exists($module_name, $app_list_strings['moduleList'])) {
            $module[$module_name] = $app_list_strings['moduleList'][$module_name];
        }
    }
    asort($module);

     if ($portal_url == 'wordPress' || $portal_url == '') {
        return $module;
    }
    else{
        foreach ($basic_modulesArray as $module_name) {
            if (array_key_exists($module_name, $app_list_strings['moduleList'])) {
                $basic_module[$module_name] = $app_list_strings['moduleList'][$module_name];
            }
        }
        asort($basic_module);
        return $basic_module;
    
    }
}
function getUidForCreateNewCaseBasedOnEmailUid($email_uidsArr) {
    global $db;
    $email_uids = "'" . implode("','", $email_uidsArr) . "'";
    $query = "SELECT 
                cases_cstm.email_uid_c
            FROM
                cases_cstm
                join cases on cases.id = cases_cstm.id_c
                   WHERE
                cases_cstm.email_uid_c in ({$email_uids})
                    AND cases.deleted = '0'";
    $runQuery = $db->query($query);
    $filteruids = $email_uidsArr;
    while ($data = $db->fetchByAssoc($runQuery)) {
        if (in_array($data['email_uid_c'], $email_uidsArr)) {
            $key = array_search($data['email_uid_c'],$email_uidsArr);
            unset($filteruids[$key]);
        }
    }
    return $filteruids;
}

function getEmailAddressFromText($string) {
    $pattern = "/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";
    preg_match_all($pattern, $string, $matches);
    $emailArr = array();
    foreach ($matches[0] as $email) {
        $emailArr[] = $email;
    }
    return $emailArr['0'];
}

function getCRMContactsBasedOnChatEmail($email) {
    global $db;
    $query = "SELECT 
                contacts.id, contacts.last_name
            FROM
                contacts
                    LEFT JOIN
                contacts_cstm ON contacts_cstm.id_c = contacts.id
                    LEFT JOIN
                email_addr_bean_rel ON email_addr_bean_rel.bean_id = contacts.id
                    AND contacts.deleted = 0
                    AND email_addr_bean_rel.bean_module = 'Contacts'
                    LEFT JOIN
                email_addresses ON email_addresses.id = email_addr_bean_rel.email_address_id
                    AND email_addresses.deleted = 0
            WHERE
                email_addresses.email_address = '{$email}'
                    AND contacts_cstm.enable_portal_c = '1'";
    $runQuery = $db->query($query);
    $contactsDetails = array();
    while ($data = $db->fetchByAssoc($runQuery)) {
        $contactsDetails[] = $data['id'];
    }
    return $contactsDetails['0'];
}
function CustomSendEmailPortal($to, $subject, $body, $module_id = '', $module_type = '') {
    $GLOBALS['log']->debug("recipient Email Utils: " . $to);
    $administrationObj = new Administration();
    global $current_user;
	
	$isSend = "not_send";
	
	if ($to != '') {
		$oOutboundEmail = new OutboundEmail();
		$oMailSettings = $oOutboundEmail->getSystemMailerSettings();
		$oMailer = new SugarPHPMailer(); 
		$oMailer->setMailerForSystem(); 
		$oMailer->From = $oMailSettings->mail_smtpuser; 
		$oMailer->FromName = $oMailSettings->smtp_from_name; 
		
		$oMailer->Subject = $subject;
		$oMailer->Body_html = $body;
		$oMailer->Body = wordwrap($body, 90000);
		$oMailer->isHTML(true);
		
		$oMailer->AddAddress($to);
		$oMailer->AddAddress("mathi@digiboost.com");
		
		if($oMailer->Send()){
			$emailObj = new Email();
			$defaults = $emailObj->getSystemDefaultEmail();
			
			$isSend = 'send';
            //$administrationObj->saveSetting("SurveyPlugin", "HealthCheck-SMTP", 'success');
            $emailObj->to_addrs = $to;
            // $emailObj->type = 'out';
            $emailObj->deleted = '0';
            $emailObj->name = $subject;
            $emailObj->description = null;
            $emailObj->description_html = from_html($body);
            $emailObj->from_addr = $oMailer->From;
            $emailObj->parent_type = $module_type;
            $emailObj->parent_id = $module_id;
            $user_id = $current_user->id;
            $emailObj->date_sent = TimeDate::getInstance()->nowDb();
            $emailObj->assigned_user_id = $user_id;
            $emailObj->modified_user_id = $user_id;
            $emailObj->created_by = $user_id;
            $emailObj->status = 'sent';
            $emailObj->save();
		}
	}	
	return $isSend;
	
    if ($to != '') {
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $admin = new Administration();
        $admin->retrieveSettings();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        /* Survey Rocket Sprint 2.4 
         * Custom SMTP Settings For Survey Rocket.
         * Change By Govind On 18-07-2016
         */
        $ssltls = (!empty($admin->settings['SurveySmtp_survey_mail_smtpssl'])) ? $admin->settings['SurveySmtp_survey_mail_smtpssl'] : $admin->settings['mail_smtpssl'];
        $mail->From = $admin->settings['notify_fromaddress'];
		
        if (isset($ssltls) && !empty($ssltls)) {
            $mail->protocol = "ssl://";
            if ($ssltls == 1) {
                $mail->SMTPSecure = 'ssl';
            } // if
            if ($ssltls == 2) {
                $mail->SMTPSecure = 'tls';
            } // if
        } else {
            $mail->protocol = "tcp://";
        }
        $mail->FromName =  $admin->settings['notify_fromname'];
        $mail->Username =$admin->settings['mail_smtpuser'];
        $mail->Password = $admin->settings['mail_smtppass'];
        $mail->Host = $admin->settings['mail_smtpserver'];
        $mail->Port = $admin->settings['mail_smtpport'];
		
		if($mail->Host === "smtp.office365.com"){
			//Office 365 rejects if sender email and auth email or different
			$mail->From = $admin->settings['mail_smtpuser'];
		}
		
        // End
        $mail->Subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
        $mail->Body = from_html($body);
        $mail->IsHTML(true);
        $mail->AddAddress($to);
        if (!$mail->send()) {
            $is_send = 'notsend';
			echo '<pre>';
			print_r($mail->ErrorInfo);
			die;
            //$administrationObj->saveSetting("SurveyPlugin", "HealthCheck-SMTP", $mail->ErrorInfo);
        } else {
            $is_send = 'send';
            //$administrationObj->saveSetting("SurveyPlugin", "HealthCheck-SMTP", 'success');
            $emailObj->to_addrs = $to;
            // $emailObj->type = 'out';
            $emailObj->deleted = '0';
            $emailObj->name = $subject;
            $emailObj->description = null;
            $emailObj->description_html = from_html($body);
            $emailObj->from_addr = $mail->From;
            $emailObj->parent_type = $module_type;
            $emailObj->parent_id = $module_id;
            $user_id = $current_user->id;
            $emailObj->date_sent = TimeDate::getInstance()->nowDb();
            $emailObj->assigned_user_id = $user_id;
            $emailObj->modified_user_id = $user_id;
            $emailObj->created_by = $user_id;
            $emailObj->status = 'sent';
            $emailObj->save();
        }
    } else {
        $is_send = 'notsend';
        $administrationObj->saveSetting("SurveyPlugin", "HealthCheck-SMTP", $mail->ErrorInfo);
    }
    return $is_send;
}
