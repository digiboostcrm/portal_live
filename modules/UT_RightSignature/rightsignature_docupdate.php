<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
//rightsignature_docupdate_cron();
function rightsignature_docupdate_cron($sID='')
{
    require_once('modules/UT_RightSignature/RightSignatureUtils.php');
    require_once('modules/UT_RightSignature/oauth-api/library/UT_RightSignatureClient.php');
    require_once('modules/Notes/NoteSoap.php');
    global $db,$timedate,$sugar_config,$current_user;

    $licKey = isset($sugar_config['outfitters_licenses']['esignrightsignature']) ? $sugar_config['outfitters_licenses']['esignrightsignature'] : '';
    require_once 'modules/UT_RightSignature/license/OutfittersLicense.php';
    $oOutfittersLicense = new OutfittersLicense();
    $valid = $oOutfittersLicense->doValidate('UT_RightSignature',$licKey);
    if(empty($valid['success'])) {
        $msg = "Invalid License";
        if($valid['result'])
            $msg = $valid['result'];
        $GLOBALS['log']->fatal($msg);
        return true;
    }
    if(!empty($sID))
        $sRightSignatureSQL = "SELECT * FROM ut_rightsignature WHERE deleted=0 AND id='".$sID."'";
    else
	{
        $sRightSignatureSQL = "SELECT * FROM ut_rightsignature WHERE deleted=0 AND state <> 'executed'";
        //$sRightSignatureSQL = "SELECT * FROM ut_rightsignature WHERE deleted=0 AND state <> 'executed' AND rs_doc_id <> '' ";
		//if($sugar_config['dbconfig']['db_type'] == "mysql")
//				$sRightSignatureSQL = "SELECT * FROM ut_rightsignature WHERE deleted=0 AND state <> 'signed' AND rs_doc_id <> '' LIMIT ".$iOffset.",".$Limit;
//			else
//				$sRightSignatureSQL = "SELECT * FROM ut_rightsignature WHERE deleted=0 AND state <> 'signed' AND rs_doc_id <> '' ";
        $sRightSignatureSQL = $db->limitQuery($sRightSignatureSQL, $iOffset, $Limit,false,'',false);
	}
    $oResRS = $db->query($sRightSignatureSQL,true);
    while($aDataOut = $db->fetchByAssoc($oResRS)) 
    {
        $oRSignatureClient = new UT_RightSignatureClient('',true,1);
        
        if(empty($aDataOut['rs_doc_id']) && !empty($aDataOut['sending_request_id'])){
            $oResponseCheckStatus = $oRSignatureClient->SendingRequestStatus($aDataOut['sending_request_id']);
            if(empty($oRSignatureClient->client->error))
            {
                $aResultCheckStatus = convertAllObjectToArray($oResponseCheckStatus);
                if(!empty($aResultCheckStatus['document_template_id'])){
                    $aDataOut['rs_doc_id'] = $aResultCheckStatus['document_template_id'];
                    $oUTRightSignature = BeanFactory::getBean('UT_RightSignature',$aDataOut['id']);
                    $oUTRightSignature->rs_doc_id = $aResultCheckStatus['document_template_id'];
                    $oUTRightSignature->save();
                }
            }
            else {
                $GLOBALS['log']->fatal("RightSigature : Error occured ".HtmlSpecialChars($oRSignatureClient->client->error));
                continue;
            }
        }
        if(!empty($aDataOut['rs_doc_id']))
        {
            $oResponse = $oRSignatureClient->getDocumentDetails($aDataOut['rs_doc_id']);
            if(empty($oRSignatureClient->client->error))
            {
                $aResult = convertAllObjectToArray($oResponse);
                if(!empty($aResult['document'])) 
                {
                    $oUTRightSignature = BeanFactory::getBean('UT_RightSignature',$aDataOut['id']);
                    $oUTRightSignature->subject = $aResult['document']['name'];
                    if(!empty($aResult['document']['recipients'][0])){
                        $oUTRightSignature->message = $aResult['document']['recipients'][0]['message'];
                    }
                    if(!empty($aResult['document']['state'])){
                        $oUTRightSignature->state = $aResult['document']['state'];
                        if($aResult['document']['state'] =='pending')
                            $oUTRightSignature->processing_state = 'processing_state';
                        else if($aResult['document']['state'] =='executed')
                            $oUTRightSignature->processing_state = 'completed';
                    }
                    //if(!empty($aResultDocDetail['processing_state']))
                        //$oUTRightSignature->processing_state = $aResultDocDetail['processing_state'];
                    if(!empty($aResult['document']['sent_at'])){
                        $stimestamp = strtotime($aResult['document']['sent_at']);
                        $sDate = gmdate("Y-m-d H:i:s", $stimestamp);
                        $sDisplayDate = $timedate->to_display_date_time($sDate);
                        $oUTRightSignature->rs_created_at = $sDisplayDate;
                    }
                    if(!empty($aResult['document']['executed_at'])){
                        $stimestamp = strtotime($aResult['document']['executed_at']);
                        $sDate = gmdate("Y-m-d H:i:s", $stimestamp);
                        $sDisplayDate = $timedate->to_display_date_time($sDate);
                        $oUTRightSignature->rs_completed_at = $sDisplayDate;
                    }
                    if(!empty($aResult['document']['expired_at'])){
                        $stimestamp = strtotime($aResult['document']['expired_at']);
                        $sDate = gmdate("Y-m-d H:i:s", $stimestamp);
                        $sDisplayDate = $timedate->to_display_date_time($sDate);
                        $oUTRightSignature->rs_expires_on = $sDisplayDate;
                    }
                    // Begin : For Original Document
                    if(!empty($aResult['document']['original_file_url'])) {
                        $oUTRightSignature->original_url = $aResult['document']['original_file_url'];
                        $decodedFile = base64_encode(file_get_contents($oUTRightSignature->original_url));
                        
                        if(!empty($oUTRightSignature->note_id_c)){
                            $oNotes = BeanFactory::getBean('Notes',$oUTRightSignature->note_id_c);
                            $sNotesID = $oUTRightSignature->note_id_c;
                        }
                        else{
                            $oNotes = BeanFactory::getBean('Notes');
                            $sNotesID = create_guid();
                            $oNotes->new_with_id = true;
                            $oNotes->id = $sNotesID;
                        }
                        if(!empty($aResult['document']['name']))
                        {
                            $oNotes->name = $aResult['document']['name'];
                            $oNotes->filename = $aResult['document']['filename'];
                        }
                        //$oNotes->file_mime_type='';
                        $oNotes->parent_type = 'UT_RightSignature';
                        $oNotes->parent_id = $oUTRightSignature->id;
                        $oNotes->save();
                        
                        $oNoteSoap = new NoteSoap();
                        $aNoteFile = array(
                            'id' => $sNotesID,
                            'name'=> $aResult['document']['name'],
                            'filename' => $aResult['document']['filename'],
                            'file' => $decodedFile,
                        );
                        $sNotesID = $oNoteSoap->newSaveFile($aNoteFile);
                        $oUTRightSignature->note_id_c = $sNotesID;
                    }
                    // END : For Original Document
                    if(!empty($aResult['document']['original_file_url']))
                        $oUTRightSignature->pdf_url = $aResult['document']['original_file_url'];
                    // Begin : For Signed Document
                    if(!empty($aResult['document']['signed_pdf_url'])) {
                        $oUTRightSignature->signed_pdf_url = $aResult['document']['signed_pdf_url'];
                        $decodedFile = base64_encode(file_get_contents($oUTRightSignature->signed_pdf_url));
                        
                        
                        if(!empty($oUTRightSignature->note_id1_c)){
                            $oNotes = BeanFactory::getBean('Notes',$oUTRightSignature->note_id1_c);
                            $sNotesID = $oUTRightSignature->note_id1_c;
                        }
                        else{
                            $oNotes = BeanFactory::getBean('Notes');
                            $sNotesID = create_guid();
                            $oNotes->new_with_id = true;
                            $oNotes->id = $sNotesID;
                        }
                        if(!empty($aResult['document']['name']))
                        {
                            $oNotes->name = $aResult['document']['name'];
                            $oNotes->filename=$aResult['document']['filename'];
                        }
                        //$oNotes->file_mime_type='';
                        $oNotes->parent_type = 'UT_RightSignature';
                        $oNotes->parent_id = $oUTRightSignature->id;
                        $oNotes->save();
                        
                        $oNoteSoap = new NoteSoap();
                        $aNoteFile = array(
                            'id' => $sNotesID,
                            'name'=>$aResult['document']['name'],
                            'filename'=>$aResult['document']['filename'],
                            'file'=>$decodedFile,
                        );
                        $sNotesID = $oNoteSoap->newSaveFile($aNoteFile);
                        $oUTRightSignature->note_id1_c = $sNotesID;
                    }
                    // END : For Signed Document
                    if(!empty($aResult['document']['thumbnail_url']))
                        $oUTRightSignature->thumbnail_url = $aResult['document']['thumbnail_url'];
                    if(!empty($aResult['document']['size']))
                        $oUTRightSignature->size = $aResult['document']['size'];
                    
                    $oUTRightSignature->save();
                    $oUTRightSignature->load_relationship("ut_rightsignature_ut_rsactivities");
                    if(!empty($aResult['document']['audits'])){
                        //Delete all Audit Trials
                        foreach ($oUTRightSignature->ut_rightsignature_ut_rsactivities->getBeans() as $eachAudit) {
                            $eachAudit->mark_deleted($eachAudit->id);
                        }
                        //Insert new Audit Trials
                        foreach($aResult['document']['audits'] as $iDx => $aAudit) {
                            $oUTRSActivities = BeanFactory::getBean('UT_RSActivities');
                            $act_id = create_guid();
                            $oUTRSActivities->new_with_id = true;
                            $oUTRSActivities->id = $act_id;
                            if(!empty($aAudit['payload'])) {
                                $oUTRSActivities->name = $aAudit['payload'];
                                $oUTRSActivities->description = $aAudit['payload'];
                            }
                            if(!empty($aAudit['keyword']))
                                $oUTRSActivities->rs_keyword = $aAudit['keyword'];
                            if(!empty($aAudit['timestamp']))
                            {
                                //$oUTRSActivities->timestamp = $aAudit['timestamp'];
                                $stimestamp = strtotime($aAudit['timestamp']);
                                $sDate = gmdate("Y-m-d H:i:s", $stimestamp);
                                $sDisplayDate = $timedate->to_display_date_time($sDate);
                                $oUTRSActivities->rs_created_at = $sDisplayDate;
                            }
                            $oUTRSActivities->save();
                            if ($oUTRightSignature->ut_rightsignature_ut_rsactivities->add($oUTRSActivities->id) == false) {
                                $GLOBALS['log']->fatal("Failed to associate RSActivities (".$oUTRSActivities->id.") with RightSignature (".$oUTRightSignature->id.")");
                            }
                        }
                    }
                }
            }
            else{
                $GLOBALS['log']->fatal("RightSigature : Error occured ".HtmlSpecialChars($oRSignatureClient->client->error));
            }
        }
    }
    if(!empty($sID))
        return true;
}
?>