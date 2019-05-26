<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('modules/UT_RightSignature/RightSignatureUtils.php');
require_once('modules/UT_RightSignature/oauth-api/library/UT_RightSignatureClient.php');
require_once('modules/Notes/NoteSoap.php');

    global $db, $app_strings, $mod_strings,$current_user,$app_list_strings, $sugar_config,$timedate;
    // checking if current user is not admin then do not procced further
    $licKey = isset($sugar_config['outfitters_licenses']['esignrightsignature']) ? $sugar_config['outfitters_licenses']['esignrightsignature'] : '';
    require_once 'modules/UT_RightSignature/license/OutfittersLicense.php';
    $oOutfittersLicense = new OutfittersLicense();
    $valid = $oOutfittersLicense->doValidate('UT_RightSignature',$licKey);
    if(empty($valid['success'])) {
        $msg = "Invalid License";
        if($valid['result'])
            $msg = $valid['result'];
        SugarApplication::appendErrorMessage($msg);
        SugarApplication::redirect('index.php?module=UT_RightSignature&action=license');
        exit;
    }
    
    $isSetRightSignatureKey = false;
    $isSetRightSignatureAuth = false;
    $aAppKeys = getApplicationKey();
    $aOAuthDBSettings = getOAuthDBSettings();
    if(!empty($aAppKeys))
        $isSetRightSignatureKey = true;
    if(!empty($aOAuthDBSettings))
        $isSetRightSignatureAuth = true;
    if($isSetRightSignatureKey == false){
        SugarApplication::redirect('index.php?module=UT_RightSignature&action=RightSignatureAppKeys');
        exit;
    }
    else  if($isSetRightSignatureAuth == false){
        SugarApplication::redirect('index.php?module=UT_RightSignature&action=RightSignatureAuth');
        exit;
    }
    
    require_once('modules/ACLActions/ACLAction.php');
    if(!ACLAction::userHasAccess($current_user->id, 'UT_RightSignature', 'edit','module', true, true))
    {
        echo $mod_strings['LBL_NOT_AUTHORIZED'];
        return;
    }
    $aFinalRes = array(
        'consumer_key'=>'',
        'consumer_secret'=>'',
    );
    $sugar_smarty   = new Sugar_Smarty();
    $SugarUserId = $current_user->id;
    $oRSignatureClient = new UT_RightSignatureClient();
    if(!empty($_POST) && $_POST['sending_type'] == 'select_template')
    {
        $sRSTemplateID = '';
        $guid = $sNewGuid = '';
        $sDocumentName = $_POST['subject'];
        $sSharedWith = array();
        $sMessage = $_POST['message'];
        $bInPerson = NULL;
        $sCallbackURL = $sugar_config['site_url'].'/index.php?entryPoint=RSPushServiceCallback';
        $merge_field_values = array();
        $aRoles = array();
        if(!empty($_POST['expires_in']))
            $iExpiresIn = $_POST['expires_in'];
        else
            $iExpiresIn = 30;
        $iPin = NULL;
        $aTags = array();
        $aSugarRecordInfo = array();
        
        $aFinalMergeField = array();
        $aFinalRoles = array();
        $aCCReceipients=array();
    
        if(!empty($_POST['rs_template_id']))
        {
            $sRSTemplateID = $_POST['rs_template_id'];
            $guid = $_POST['rs_template_id'];
            $signer_sequencing = $_POST['signer_sequencing'];
            foreach($_REQUEST as $sKey => $sReqValue)
            {
                if (strpos($sKey, 'mergeid|||') !== false) 
                {
                    $aExplodedMerge = explode("|||",$sKey);
                    if(!empty($aExplodedMerge[1]))
                    {
                        $aTempMergeField=array(
                            'id' => $aExplodedMerge[1],
                            'value' => $sReqValue
                            );
                        $aFinalMergeField[]=$aTempMergeField;
                    }
                }
                if (strpos($sKey, 'role|||') !== false) 
                {
                    $aExplodedRole = explode("|||",$sKey);
                    if(!empty($aExplodedRole[1]))
                    {
                        $sOwnerName=htmlspecialchars($_POST['receipient_name___'.$aExplodedRole[1]]);
                        $sOwnerEmail=$_POST['receipient_email___'.$aExplodedRole[1]];
                        $aTempRoles=array();
                        $aTempRoles['name'] = $sReqValue;
                        $aTempRoles['signer_name'] = $sOwnerName;
                        $aTempRoles['signer_email'] = $sOwnerEmail;
                        //$aTempRoles['is_sender'] = $bIsSigner;
                        if(!empty($signer_sequencing)){
                            $sSequenceNumber = 'sequence|||'.$aExplodedRole[1];
                            $aTempRoles['sequence'] = $_REQUEST[$sSequenceNumber];
                        }
                        $aTempRoles['message'] = $_REQUEST['message'];
                        $aFinalRoles[]=$aTempRoles;
                        if(!empty($_POST['sugar_module___'.$aExplodedRole[1]]) && !empty($_POST['sugar_module_id___'.$aExplodedRole[1]]) )
                        {
                            $aSugarRecordInfo[] = array(
                                'role' =>$sReqValue,
                                'name' => $sOwnerName,
                                'email' => $sOwnerEmail,
                                'parent_id' => $_POST['sugar_module_id___'.$aExplodedRole[1]],
                                'parent_type' => $_POST['sugar_module___'.$aExplodedRole[1]],
                            );
                        }
                    }
                }
                if (strpos($sKey, 'cc|||') !== false) 
                {
                    $aExplodedCC = explode("|||",$sKey);
                    if(!empty($aExplodedCC[1]))
                    {
                        $sOwnerName=htmlspecialchars($_POST['receipient_name___'.$aExplodedCC[1]]);
                        $sOwnerEmail=$_POST['receipient_email___'.$aExplodedCC[1]];
                        $aCCReceipients[] = $sOwnerEmail;
                        if(!empty($_POST['sugar_module___'.$aExplodedCC[1]]) && !empty($_POST['sugar_module_id___'.$aExplodedCC[1]]) )
                        {
                            $aSugarRecordInfo[] = array(
                                'role' =>'cc',
                                'name' => $sOwnerName,
                                'email' => $sOwnerEmail,
                                'parent_id' => $_POST['sugar_module_id___'.$aExplodedCC[1]],
                                'parent_type' => $_POST['sugar_module___'.$aExplodedCC[1]],
                            );
                        }
                    }
                }
            }
            if(!empty($aFinalMergeField)){
                $merge_field_values=$aFinalMergeField;
            }   
            if(!empty($aFinalRoles)){
                $aRoles=$aFinalRoles;
            }
            $oResponse1 = $oRSignatureClient->sendTemplate($guid, $sDocumentName, $merge_field_values, $aRoles, $iExpiresIn, $aCCReceipients, $sMessage, $bInPerson, $sCallbackURL, $iPin, $aTags);
            if(empty($oRSignatureClient->client->error))
            {
                $aResult = convertAllObjectToArray($oResponse1);
                if(!empty($aResult['document']['id'])) {
                    $sNewGuid = $aResult['document']['id'];
                }
            }
            else
                $_SESSION['urdhvarightsignature']['errormsg'] = $mod_strings['LBL_SETTINGS_ERROR'].'<br/>'. HtmlSpecialChars($oRSignatureClient->client->error);
        }
        else{
            $_SESSION['urdhvarightsignature']['errormsg'] = $mod_strings['LBL_SETTINGS_ERROR'].'<br/>';
        }
    }
    else if(!empty($_POST) && $_POST['sending_type'] == 'external_file' && !empty($_FILES))
    {
        $sRSTemplateID = '';
        $guid = $sNewGuid = '';
        require_once("include/utils/sugar_file_utils.php");
        require_once("include/upload_file.php");
        $directoryName = "UT_RSUploads";
        $sFileName = $_FILES['uploadfiles']['name'];
        if(!sugar_is_file($directoryName) && !sugar_is_dir($directoryName))
        {
            sugar_mkdir($directoryName, 0755, true);
        }
        $sFilePathName = $directoryName."/".$sFileName;
        if(!move_uploaded_file($_FILES['uploadfiles']['tmp_name'], $sFilePathName)) {
            $msg = "can't move_uploaded_file to $directoryName. You should try making the directory writable by the webserver";
            $GLOBALS['log']->fatal("ERROR: ".$msg);
            $_SESSION['urdhvarightsignature']['errormsg'] = $msg;
            SugarApplication::appendErrorMessage($msg);
            SugarApplication::redirect('index.php?module=UT_RightSignature&action=SendDocumentRS');
            exit;
            return false;
        }
        $recipients = array();
        $aFinalRecipients = $aFinalCC = array();
        foreach($_REQUEST as $sKey => $sReqValue)
        {
            if (strpos($sKey, 'must_sign|||') !== false) 
            {
                $aExplodedSigner = explode("|||",$sKey);
                if(!empty($aExplodedSigner[1]))
                {
                    $sOwnerName=htmlspecialchars($_POST['receipient_name___'.$aExplodedSigner[1]]);
                    $sOwnerEmail=$_POST['receipient_email___'.$aExplodedSigner[1]];
                    if($sReqValue == '1'){
                        $aFinalRecipients[]=array(
                                            'name' => 'signer',
                                            'signer_name' => $sOwnerName,
                                            'signer_email' => $sOwnerEmail,
                                            'message' => $_POST['message']
                                        );
                        if(!empty($_POST['sugar_module___'.$aExplodedSigner[1]]) && !empty($_POST['sugar_module_id___'.$aExplodedSigner[1]]) )
                        {
                            $aSugarRecordInfo[] = array(
                                'role' =>'signer',
                                'name' => $sOwnerName,
                                'email' => $sOwnerEmail,
                                'parent_id' => $_POST['sugar_module_id___'.$aExplodedSigner[1]],
                                'parent_type' => $_POST['sugar_module___'.$aExplodedSigner[1]],
                            );
                        }
                        else
                        {
                            $aSugarRecordInfo[] = array(
                                'role' =>'signer',
                                'name' => $sOwnerName,
                                'email' => $sOwnerEmail,
                                'parent_id' => '',
                                'parent_type' => '',
                            );
                        }
                    }
                    else
                    {
                        $sOwnerName=htmlspecialchars($_POST['receipient_name___'.$aExplodedSigner[1]]);
                        $sOwnerEmail=$_POST['receipient_email___'.$aExplodedSigner[1]];
                        $aFinalCC[]=$sOwnerEmail;
                            if(!empty($_POST['sugar_module___'.$aExplodedSigner[1]]) && !empty($_POST['sugar_module_id___'.$aExplodedSigner[1]]) )
                            {
                                $aSugarRecordInfo[] = array(
                                    'role' =>'cc',
                                    'name' => $sOwnerName,
                                    'email' => $sOwnerEmail,
                                    'parent_id' => $_POST['sugar_module_id___'.$aExplodedSigner[1]],
                                    'parent_type' => $_POST['sugar_module___'.$aExplodedSigner[1]],
                                );
                            }
                            else{
                                $aSugarRecordInfo[] = array(
                                    'role' =>'cc',
                                    'name' => $sOwnerName,
                                    'email' => $sOwnerEmail,
                                    'parent_id' => '',
                                    'parent_type' => '',
                                );
                            }
                    }
                }
            }
        }
        $file_data = array(
                        'name' => $sFileName,
                        'source' => 'upload',
                     );
        $sDocumentName = $_POST['subject'];
        $expires_in = "30";
        $sMessage = $_POST['message'];
        $tags = array();
        $doc_callback_url = $sugar_config['site_url'].'/index.php?entryPoint=RSPushServiceCallback';
        $document_data['name'] = $sDocumentName;
        $document_data['signer_sequencing'] = false;
        $document_data['shared_with'] = $aFinalCC;
        $document_data['callback_url'] = $doc_callback_url; //The URL will receive a POST for each of the following document events: created, viewed, signed, executed. Note that due to the asynchronous nature of processing, the order in which the document callbacks are sent is not guaranteed
        $document_data['roles'] = $aFinalRecipients;
        $document_data['expires_in'] = $expires_in;
        $sending_req_callback_url = $sugar_config['site_url'].'/index.php?entryPoint=RSPushServiceCallback';
        
        $aResponseSendingRequest = array();
        $oResponse = $oRSignatureClient->createSendingRequest($file_data, $document_data, $sending_req_callback_url);
        if(empty($oRSignatureClient->client->error))
        {
            $aResponseSendingRequest = convertAllObjectToArray($oResponse);
            if(!empty($aResponseSendingRequest['sending_request']['id']))
            {
                $sSendRequestId = $aResponseSendingRequest['sending_request']['id'];
                $sSendingRequest_status = $aResponseSendingRequest['sending_request']['status'];
                $sSendingRequest_upload_url = $aResponseSendingRequest['sending_request']['upload_url'];
                $sSendingRequest_document_template_id = $aResponseSendingRequest['sending_request']['document_template_id'];
                $oResponseCURLPUT = $oRSignatureClient->SendingRequest_UploadFile($sFilePathName,$sSendRequestId, $sSendingRequest_status, $sSendingRequest_upload_url,$sSendingRequest_document_template_id);
                if(!empty($oResponseCURLPUT) && $oResponseCURLPUT['status'] == true)
                {
                    $oResponseUploaded = $oRSignatureClient->SendingRequest_Uploaded($sSendRequestId);
                    if(empty($oRSignatureClient->client->error))
                    {
                        //"waiting_for_file" | "downloading" | "processing" | "creating_document" | "completed" | "errored"
                        $aResultUploaded = convertAllObjectToArray($oResponseUploaded);
                        if(!empty($aResultUploaded['id'])) {
                            //$sNewGuid = $aResultUploaded['id'];
                            $sNewGuid = $sSendRequestId;
                            unlink($sFilePathName);
                        }
                    }
                    else{
                        $_SESSION['urdhvarightsignature']['errormsg'] = HtmlSpecialChars($oRSignatureClient->client->error);
                        SugarApplication::appendErrorMessage(HtmlSpecialChars($oRSignatureClient->client->error));
                        SugarApplication::redirect('index.php?module=UT_RightSignature&action=SendDocumentRS');
                        exit;
                    }
                }
                else{
                    $_SESSION['urdhvarightsignature']['errormsg'] = $oResponseCURLPUT['err_msg'];
                    SugarApplication::appendErrorMessage($oResponseCURLPUT['err_msg']);
                    SugarApplication::redirect('index.php?module=UT_RightSignature&action=SendDocumentRS');
                    exit;
                }
            }
        }
        else{
            $_SESSION['urdhvarightsignature']['errormsg'] = HtmlSpecialChars($oRSignatureClient->client->error);
            SugarApplication::appendErrorMessage(HtmlSpecialChars($oRSignatureClient->client->error));
            SugarApplication::redirect('index.php?module=UT_RightSignature&action=SendDocumentRS');
            exit;
        }
    }
    if(!empty($sNewGuid))
    {
                $oUTRightSignature = BeanFactory::getBean('UT_RightSignature');
                $id = create_guid();
                $oUTRightSignature->new_with_id = true;
                $oUTRightSignature->id = $id;
                $oUTRightSignature->document_name = 'RS : ';
                $oUTRightSignature->sending_type = $_POST['sending_type'];
                if($_POST['sending_type'] == 'select_template'){
                    $oUTRightSignature->rightsignature_templates = $_POST['rs_template_id'];
                    $oUTRightSignature->rs_doc_id = $sNewGuid; // This is the RS document id, So we can call and get the Document Details
                }
                else{
                    // This is the sending id, Based on this we will receive a callback from RS once the document is created. So directly getting Document Details is not possible.
                    $oUTRightSignature->sending_request_id = $sNewGuid; 
                }
                $oUTRightSignature->recipient_name = '-- Multiple Recipient --';
                $oUTRightSignature->email_address = '-- Multiple Recipient --';
                $oUTRightSignature->subject = $sDocumentName;
                $oUTRightSignature->message = $sMessage;
                
                if($_POST['sending_type'] == 'select_template')
                {
                    $oResponseDocDetail = $oRSignatureClient->getDocumentDetails($sNewGuid);
                    if(empty($oRSignatureClient->client->error)){
                        $aResultDocDetail = convertAllObjectToArray($oResponseDocDetail);
                        if(!empty($aResultDocDetail['document']['state'])){
                            $oUTRightSignature->state = $aResultDocDetail['document']['state'];
                            if($aResultDocDetail['document']['state'] =='pending')
                                $oUTRightSignature->processing_state = 'processing_state';
                            else if($aResultDocDetail['document']['state'] =='executed')
                                $oUTRightSignature->processing_state = 'completed';
                        }
                        //if(!empty($aResultDocDetail['document']['processing_state']))
                            //$oUTRightSignature->processing_state = $aResultDocDetail['document']['processing_state'];
                        if(!empty($aResultDocDetail['document']['sent_at'])){
                            $stimestamp = strtotime($aResultDocDetail['document']['sent_at']);
                            $sDate = gmdate("Y-m-d H:i:s", $stimestamp);
                            $sDisplayDate = $timedate->to_display_date_time($sDate);
                            $oUTRightSignature->rs_created_at = $sDisplayDate;
                        }
                        if(!empty($aResultDocDetail['document']['executed_at'])){
                            $stimestamp = strtotime($aResultDocDetail['document']['executed_at']);
                            $sDate = gmdate("Y-m-d H:i:s", $stimestamp);
                            $sDisplayDate = $timedate->to_display_date_time($sDate);
                            $oUTRightSignature->rs_completed_at = $sDisplayDate;
                        }
                        if(!empty($aResultDocDetail['document']['expired_at'])){
                            $stimestamp = strtotime($aResultDocDetail['document']['expired_at']);
                            $sDate = gmdate("Y-m-d H:i:s", $stimestamp);
                            $sDisplayDate = $timedate->to_display_date_time($sDate);
                            $oUTRightSignature->rs_expires_on = $sDisplayDate;
                        }
                        // Begin : For Original Document
                        if(!empty($aResultDocDetail['document']['original_file_url'])) {
                            $oUTRightSignature->original_url = $aResultDocDetail['document']['original_file_url'];
                            $decodedFile = base64_encode(file_get_contents($oUTRightSignature->original_url));
                            
                            $oNotes = BeanFactory::getBean('Notes');
                            $sNotesID = create_guid();
                            $oNotes->new_with_id = true;
                            $oNotes->id = $sNotesID;
                            $oNotes->name = $aResultDocDetail['document']['name'];
                            $oNotes->filename = $aResultDocDetail['document']['filename'];
                            //$oNotes->file_mime_type='';
                            $oNotes->parent_type = 'UT_RightSignature';
                            $oNotes->parent_id = $oUTRightSignature->id;
                            $oNotes->assigned_user_id = $SugarUserId;
                            $oNotes->save();
                            
                            $oNoteSoap = new NoteSoap();
                            $aNoteFile = array(
                                'id' => $sNotesID,
                                'name'=> $aResultDocDetail['document']['name'],
                                'filename' => $aResultDocDetail['document']['filename'],
                                'file' => $decodedFile,
                            );
                            $sNotesID = $oNoteSoap->newSaveFile($aNoteFile);
                            $oUTRightSignature->note_id_c = $sNotesID;
                        }
                        // END : For Original Document
                        if(!empty($aResultDocDetail['document']['original_file_url']))
                            $oUTRightSignature->pdf_url = $aResultDocDetail['document']['original_file_url'];
                        // Begin : For Signed Document
                        if(!empty($aResultDocDetail['document']['signed_pdf_url'])) {
                            $oUTRightSignature->signed_pdf_url = $aResultDocDetail['document']['signed_pdf_url'];
                            $decodedFile = base64_encode(file_get_contents($oUTRightSignature->signed_pdf_url));
                            
                            $oNotes = BeanFactory::getBean('Notes');
                            $sNotesID = create_guid();
                            $oNotes->new_with_id = true;
                            $oNotes->id = $sNotesID;
                            $oNotes->name = $aResultDocDetail['document']['name'];
                            $oNotes->filename=$aResultDocDetail['document']['filename'];
                            //$oNotes->file_mime_type='';
                            $oNotes->parent_type = 'UT_RightSignature';
                            $oNotes->parent_id = $oUTRightSignature->id;
                            $oNotes->assigned_user_id = $SugarUserId;
                            $oNotes->save();
                            
                            $oNoteSoap = new NoteSoap();
                            $aNoteFile = array(
                                'id' => $sNotesID,
                                'name'=>$aResultDocDetail['document']['name'],
                                'filename'=>$aResultDocDetail['document']['filename'],
                                'file'=>$decodedFile,
                            );
                            $sNotesID = $oNoteSoap->newSaveFile($aNoteFile);
                            $oUTRightSignature->note_id1_c = $sNotesID;
                        }
                        // END : For Signed Document
                        if(!empty($aResultDocDetail['document']['thumbnail_url']))
                            $oUTRightSignature->thumbnail_url = $aResultDocDetail['document']['thumbnail_url'];
                        if(!empty($aResultDocDetail['document']['size']))
                            $oUTRightSignature->size = $aResultDocDetail['document']['size'];
                        $oUTRightSignature->assigned_user_id = $SugarUserId;
                        $oUTRightSignature->save();
                        $oUTRightSignature->load_relationship("ut_rightsignature_ut_rsactivities");

                        if(!empty($aResultDocDetail['document']['audits'])){
                            foreach($aResultDocDetail['document']['audits'] as $iDx => $aAudit) {
                                
                                $oUTRSActivities = BeanFactory::getBean('UT_RSActivities');
                                $act_id = create_guid();
                                $oUTRSActivities->new_with_id = true;
                                $oUTRSActivities->id = $act_id;
                                if(!empty($aAudit['payload'])) {
                                    $oUTRSActivities->name = $aAudit['payload'];
                                    //$oUTRSActivities->rs_summary = $aAudit['message'];
                                    $oUTRSActivities->description = $aAudit['payload'];
                                }
                                if(!empty($aAudit['keyword']))
                                    $oUTRSActivities->rs_keyword = $aAudit['keyword'];
                                if(!empty($aAudit['timestamp']))
                                {
                                    $stimestamp = strtotime($aAudit['timestamp']);
                                    $sDate = gmdate("Y-m-d H:i:s", $stimestamp);
                                    $sDisplayDate = $timedate->to_display_date_time($sDate);
                                    $oUTRSActivities->rs_created_at = $sDisplayDate;
                                }
                                $oUTRSActivities->assigned_user_id = $SugarUserId;
                                $oUTRSActivities->save();
                                
                                if ($oUTRightSignature->ut_rightsignature_ut_rsactivities->add($oUTRSActivities->id) == false) {
                                    $GLOBALS['log']->fatal("Failed to associate RSActivities (".$oUTRSActivities->id.") with RightSignature (".$oUTRightSignature->id.")");
                                }
                            }
                        }
                   }
                   else{
                        $_SESSION['urdhvarightsignature']['errormsg'] = HtmlSpecialChars($oRSignatureClient->client->error);
                        SugarApplication::appendErrorMessage(HtmlSpecialChars($oRSignatureClient->client->error));
                        SugarApplication::redirect('index.php?module=UT_RightSignature&action=SendDocumentRS');
                        exit;
                   }
               }
               else{
                   $oUTRightSignature->assigned_user_id = $SugarUserId;
                   $oUTRightSignature->save();
               }
               if(!empty($aSugarRecordInfo)) {
                    foreach($aSugarRecordInfo as $field_value) {
                        $oSigner = BeanFactory::getBean("UT_Signers");
                        $oSigner->name = $field_value['name'];
                        $oSigner->recipient_email = $field_value['email'];
                        $oSigner->role = $field_value['role'];
                        $oSigner->ut_signers_ut_rightsignatureut_rightsignature_ida = $oUTRightSignature->id;
                        if(!empty($field_value['parent_id']))
                            $oSigner->parent_id = $field_value['parent_id'];
                        if(!empty($field_value['parent_type']))
                            $oSigner->parent_type = $field_value['parent_type'];
                        $oSigner->assigned_user_id = $SugarUserId; 
                        $oSigner->save();
                        
                        if(!empty($field_value['parent_id']) && !empty($field_value['parent_type'])){
                            $sRelationShipName = '';
                            if($field_value['parent_type'] == 'Accounts') {
                                $sRelationShipName = 'ut_rightsignature_accounts_1';
                                $oParObj = BeanFactory::getBean('Accounts',$field_value['parent_id']);
                            }
                            else if($field_value['parent_type'] == 'Contacts'){
                                $sRelationShipName='ut_rightsignature_contacts_1';
                                $oParObj = BeanFactory::getBean('Contacts',$field_value['parent_id']);
                            }
                            else if($field_value['parent_type'] == 'Leads'){
                                $sRelationShipName='ut_rightsignature_leads_1';
                                $oParObj = BeanFactory::getBean('Leads',$field_value['parent_id']);
                            }
                            else if($field_value['parent_type'] == 'Prospects'){
                                $sRelationShipName='ut_rightsignature_prospects_1';
                                $oParObj = BeanFactory::getBean('Prospects',$field_value['parent_id']);
                            }
                            else if($field_value['parent_type'] == 'AOS_Contracts'){
                                $sRelationShipName='ut_rightsignature_aos_contracts_1';
                                $oParObj = BeanFactory::getBean('AOS_Contracts',$field_value['parent_id']);
                            }
                            else if($field_value['parent_type'] == 'AOS_Quotes'){
                                $sRelationShipName='ut_rightsignature_aos_quotes_1';
                                $oParObj = BeanFactory::getBean('AOS_Quotes',$field_value['parent_id']);
                            }
                            if(!empty($sRelationShipName)){
                                $oParObj->load_relationship($sRelationShipName);
                                if ($oParObj->$sRelationShipName->add($oUTRightSignature->id) == false)
                                    $GLOBALS['log']->fatal("Failed to associate (".$field_value['parent_id'].") with RightSignature (".$oUTRightSignature->id.")");
                            }
                            
                        }
                    }
               }
        $_SESSION['urdhvarightsignature']['message'] = $mod_strings['LBL_DOC_SENT_FOR_SIGNATURE'].' <a href="index.php?module=UT_RightSignature&action=DetailView&record='.$oUTRightSignature->id.'"> here</a>';
        $url = "index.php?module=UT_RightSignature&action=SendDocumentRS";
        SugarApplication::redirect($url);
    }
    $sRSTemplateOption = '';
    if(!empty($app_list_strings['rightsignature_templates_list']))
    {
        $sRSTemplateOption .= '<option value=""></option>';
        foreach($app_list_strings['rightsignature_templates_list'] as $iRsTemplateId => $sTemplateName) {
            $sRSTemplateOption .= '<option value="'.$iRsTemplateId.'">'.$sTemplateName.'</option>';
        }
    }
    $sugar_smarty->assign('rs_template_options' , $sRSTemplateOption);
    $sSupportedModuleOption='';
    $aSupportedSignerModule = array('Accounts','Contacts','Leads','Prospects');
    foreach($aSupportedSignerModule as $sK => $sModName){
        if(ACLController::checkAccess($sModName, 'list', true)) {
            $sSupportedModuleOption .= '<option value="'.$sModName.'">'.$app_list_strings['moduleList'][$sModName].'</option>';
        }
    }
    $sugar_smarty->assign('sSupportedModuleOption' , $sSupportedModuleOption);
    
    $prePopulateAction = '';
    $prePopFromDetailViewModule = '';
    $prePopFromDetailViewId = '';
    $prePopFromDetailViewName = '';
    $prePopFromDetailViewEmail = '';
    $sCancelRedirectURL = "index.php?module=UT_RightSignature&action=index";
    
    if(!empty($_REQUEST['prePopModule']) && !empty($_REQUEST['prePopID'])) {
        $oDynamicModule = BeanFactory::getBean($_REQUEST['prePopModule'],$_REQUEST['prePopID']);
        $prePopFromDetailViewModule = $_REQUEST['prePopModule'];
        $prePopFromDetailViewId = $_REQUEST['prePopID'];
        if($_REQUEST['prePopModule'] == 'Accounts'){
            $prePopFromDetailViewName = $oDynamicModule->name;
            $prePopFromDetailViewEmail = $oDynamicModule->email1;
        }
        else if($_REQUEST['prePopModule'] == 'Contacts' || $_REQUEST['prePopModule'] == 'Leads' || $_REQUEST['prePopModule'] == 'Prospects'){
            $prePopFromDetailViewName = $oDynamicModule->full_name;
            $prePopFromDetailViewEmail = $oDynamicModule->email1;
        }
        
        $sCancelRedirectURL = "index.php?module=".$_REQUEST['prePopModule']."&action=DetailView&record=".$_REQUEST['prePopID'];
    }
    if(!empty($_REQUEST['prePopulateAction'])){
        $prePopulateAction=$_REQUEST['prePopulateAction'];
    }
    
    $sugar_smarty->assign('prePopFromDetailViewModule' ,$prePopFromDetailViewModule);
    $sugar_smarty->assign('prePopFromDetailViewId' ,$prePopFromDetailViewId);
    $sugar_smarty->assign('prePopFromDetailViewName' ,$prePopFromDetailViewName);
    $sugar_smarty->assign('prePopFromDetailViewEmail' ,$prePopFromDetailViewEmail);
    $sugar_smarty->assign('prePopulateAction' ,$prePopulateAction);
    $sugar_smarty->assign('sCancelRedirectURL' ,$sCancelRedirectURL);
    // showing message on page it not empty
    if(!empty($_SESSION['urdhvarightsignature']['message']))
    {
        $sugar_smarty->assign("MESSAGE", $_SESSION['urdhvarightsignature']['message']);
        unset($_SESSION['urdhvarightsignature']['message']);
    }
    // showing error message on page it not empty
    if(!empty($_SESSION['urdhvarightsignature']['errormsg']))
    {
        $sugar_smarty->assign("ERRORMESSAGE", $_SESSION['urdhvarightsignature']['errormsg']);
        unset($_SESSION['urdhvarightsignature']['errormsg']);
    }
    $sugar_smarty->assign('mod', $mod_strings);
    $sugar_smarty->assign('app', $app_strings);
    $sugar_smarty->assign('applist' , $app_list_strings['moduleList']);
    $sugar_smarty->display('modules/UT_RightSignature/tpls/SendDocumentRS.tpl');