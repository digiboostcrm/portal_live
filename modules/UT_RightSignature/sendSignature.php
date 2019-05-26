<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
require_once('modules/UT_RightSignature/RightSignatureUtils.php');
require_once('modules/UT_RightSignature/oauth-api/library/UT_RightSignatureClient.php');
require_once('modules/Notes/NoteSoap.php');
require_once('modules/UT_RightSignature/rightsignature_docupdate.php');

global $db,$app_strings,$app_list_strings,$current_user, $sugar_config, $current_language;
$licKey = isset($sugar_config['outfitters_licenses']['esignrightsignature']) ? $sugar_config['outfitters_licenses']['esignrightsignature'] : '';
require_once 'modules/UT_RightSignature/license/OutfittersLicense.php';
$oOutfittersLicense = new OutfittersLicense();
$valid = $oOutfittersLicense->doValidate('UT_RightSignature',$licKey);
if(empty($valid['success'])) {
    $msg = "Invalid License";
    if($valid['result'])
        $msg = $valid['result'];
    echo json_encode($msg);
    exit;
}

$module = $recordId = $modalView = '';
$module = $_REQUEST['moduleName'];
$recordId = $_REQUEST['recordId'];
$modalView = $_REQUEST['modalView'];
$templateID = $_REQUEST['template_id'];

$error_message = '';
$sReceipientName = '';
$sReceipientEmail = '';
$sMessageOut='';
$base_mod_string = return_module_language($current_language, "UT_RightSignature");

if($modalView == "DetailView") {
    switch($module){
        case 'Accounts':
        case 'Contacts':
        case 'Leads':
              $oBean = BeanFactory::getBean($module,$recordId);
              if(empty($oBean->email1))
                $error_message = $base_mod_string['LBL_ERRMSG_1'];
              else{
                  if($module == 'Accounts')
                    $sReceipientName = $oBean->name;
                  else
                    $sReceipientName = $oBean->first_name.' '.$oBean->last_name;
                  $sReceipientEmail = $oBean->email1;
              }
            break;
        case 'AOS_Quotes':
        case 'AOS_Invoices':
            $oBean = BeanFactory::getBean($module,$recordId);
            if(!empty($oBean->billing_contact_id)){
                $oSubBean = BeanFactory::getBean("Contacts",$oBean->billing_contact_id);
                if(empty($oSubBean->email1))
                    $error_message = sprintf($base_mod_string['LBL_ERRMSG_2'], $oSubBean->full_name);
                else{
                    $sReceipientName = $oSubBean->full_name;
                    $sReceipientEmail = $oSubBean->email1;
                }
            }
            else {
                $error_message=$base_mod_string['LBL_ERRMSG_3'];
            }
           break;
         case 'AOS_Contracts':
            $oBean = BeanFactory::getBean($module,$recordId);
            if(!empty($oBean->contact_id)){
                $oSubBean = BeanFactory::getBean("Contacts",$oBean->contact_id);
                if(empty($oSubBean->email1))
                    $error_message = sprintf($base_mod_string['LBL_ERRMSG_2'], $oSubBean->full_name);
                else{
                    $sReceipientName = $oSubBean->full_name;
                    $sReceipientEmail = $oSubBean->email1;
                }
            }
            else
                $error_message=$base_mod_string['LBL_ERRMSG_3'];
           break;
    }
    if(empty($error_message)) 
    {
        require_once('modules/UT_RightSignature/generatePdf.php');
        $aFinalRecipients = $aFinalCC = array();
        $directoryName = "UT_RSUploads";
        $oRSignatureClient = new UT_RightSignatureClient();
        $aPDFInfo = UTGetPDFContent($templateID,'pdf',$module,$recordId);
        $sFileName = $aPDFInfo['file_name'];
        //$document_data['base64value'] = base64_encode($aPDFInfo['sPDFContent']);
//        $document_data['filename'] = $aPDFInfo['file_name'];

        $aFinalRecipients[]=array(
                                'name'=> 'signer',
                                'signer_name' => htmlspecialchars($sReceipientName),
                                'signer_email' => $sReceipientEmail,
                                'message' => $template->name
                             );
        $template = new AOS_PDF_Templates();
        $template->retrieve($templateID);
    
        $subject = $template->name;
        $action = "send";
        $expires_in = "30";
        $description = $template->name;
        //$tags = array('sent_from_api','mutual_nda');
        $tags = array();
        $callbackURL = $sugar_config['site_url'].'/index.php?entryPoint=RSPushServiceCallback';
        $use_text_tags = 'true';

        
        $sFilePathName = $directoryName."/".$sFileName;
        $file_data = array(
                        'name' => $sFileName,
                        'source' => 'upload',
                     );
        $sDocumentName = $template->name;
        $expires_in = "30";
        $sMessage = $template->name;
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
                        if(!empty($aResultUploaded['id'])) 
                        {
                            $sNewGuid = $aResponseSendingRequest['sending_request']['id'];
                            //$sNewGuid = $aResultUploaded['id'];
                            unlink($sFilePathName);
                            if(!empty($sNewGuid))
                            {
                                $oUTRightSignature = BeanFactory::getBean('UT_RightSignature');
                                $id = create_guid();
                                $oUTRightSignature->new_with_id = true;
                                $oUTRightSignature->id = $id;
                                $oUTRightSignature->document_name = 'RS : ';
                                $oUTRightSignature->sending_type = 'select_crm_pdf';
                                $oUTRightSignature->recipient_name = $sReceipientName;
                                $oUTRightSignature->email_address = $sReceipientEmail;
                                $oUTRightSignature->sending_request_id = $sNewGuid;
                                $oUTRightSignature->subject = $sDocumentName;
                                $oUTRightSignature->message = $sMessage;
                                $oUTRightSignature->processing_state = $aResultUploaded['status'];
                                $oUTRightSignature->parent_id = $recordId;
                                $oUTRightSignature->parent_type = $module;
                                $oUTRightSignature->assigned_user_id = $current_user->id;
                                $oUTRightSignature->save();
                                
                                $oSigner = BeanFactory::getBean("UT_Signers");
                                $oSigner->name = $sReceipientName;
                                $oSigner->recipient_email = $sReceipientEmail;
                                $oSigner->role = 'signer';
                                $oSigner->ut_signers_ut_rightsignatureut_rightsignature_ida = $oUTRightSignature->id;
                                $oSigner->parent_id = $recordId;
                                $oSigner->parent_type = $module;
                                $oSigner->assigned_user_id = $current_user->id;
                                $oSigner->save();
                                
                                if(!empty($recordId) && !empty($module)){
                                    $sRelationShipName = '';
                                    if($module == 'Accounts'){
                                        $sRelationShipName = 'ut_rightsignature_accounts_1';
                                        $oParObj = BeanFactory::getBean('Accounts',$recordId);
                                    }
                                    else if($module == 'Contacts'){
                                        $sRelationShipName='ut_rightsignature_contacts_1';
                                        $oParObj = BeanFactory::getBean('Contacts',$recordId);
                                    }
                                    else if($module == 'Leads'){
                                        $sRelationShipName='ut_rightsignature_leads_1';
                                        $oParObj = BeanFactory::getBean('Leads',$recordId);
                                    }
                                    else if($module == 'Prospects'){
                                        $sRelationShipName='ut_rightsignature_prospects_1';
                                        $oParObj = BeanFactory::getBean('Prospects',$recordId);
                                    }
                                    else if($module == 'AOS_Contracts'){
                                        $sRelationShipName='ut_rightsignature_aos_contracts_1';
                                        $oParObj = BeanFactory::getBean('AOS_Contracts',$recordId);
                                    }
                                    else if($module == 'AOS_Quotes'){
                                        $sRelationShipName='ut_rightsignature_aos_quotes_1';
                                        $oParObj = BeanFactory::getBean('AOS_Quotes',$recordId);
                                    }
                                    if(!empty($sRelationShipName)){
                                        $oParObj->load_relationship($sRelationShipName);
                                        if ($oParObj->$sRelationShipName->add($oUTRightSignature->id) == false)
                                            $GLOBALS['log']->fatal("Failed to associate (".$recordId.") with RightSignature (".$oUTRightSignature->id.")");
                                    }
                                }
                                rightsignature_docupdate_cron($oUTRightSignature->id);
                            }
                            $sMessageOut = $mod_strings['LBL_DOC_SENT_FOR_SIGNATURE'].' <a href="index.php?module=UT_RightSignature&action=DetailView&record='.$oUTRightSignature->id.'"> here</a>';
                        }
                    }
                    else{
                        $sMessageOut = HtmlSpecialChars($oRSignatureClient->client->error);
                    }
                }
                else{
                    $sMessageOut = $oResponseCURLPUT['err_msg'];
                }
            }
        }
        else{
            $sMessageOut = HtmlSpecialChars($oRSignatureClient->client->error);
        }
    }
    else {
       $sMessageOut=$error_message;
    }
}
echo json_encode($sMessageOut);
?>