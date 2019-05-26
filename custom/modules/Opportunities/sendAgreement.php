<?php
require_once('modules/UT_RightSignature/RightSignatureUtils.php');
require_once('modules/UT_RightSignature/oauth-api/library/UT_RightSignatureClient.php');
require_once('modules/Notes/NoteSoap.php');
$json = getJSONobj();
global $db, $app_strings, $mod_strings,$current_user,$app_list_strings, $sugar_config,$timedate;
$aReturn = array(
                'message' => '',
                'status' => 'failed',
           );
require_once('modules/ACLActions/ACLAction.php');
if(!is_admin($current_user) && !ACLAction::userHasAccess($current_user->id, 'UT_RightSignature', 'edit','module', true, true))
{
    $aReturn['message'] = 'You are not authorized, Please contact system administrator';
    $aReturn['status'] = "failed";
    echo $json->encode($aReturn);
    return;
}
if(empty($_REQUEST['record'])) {
    $aReturn['message'] = $mod_strings['LBL_MISSING_RECORD'];
    $aReturn['status'] = "failed";
    echo $json->encode($aReturn);
    return;
}

$iFlag=false;
$sBeanId=$_REQUEST['record'];
$oBean = BeanFactory::getBean($_REQUEST['module'],$sBeanId);

if($_REQUEST['module']=='Opportunities')
{
    if(empty($sugar_config['rs_template_individual_joint'])){
        $aReturn['message'] = $mod_strings['LBL_MISSING_RS_TEMPLATE_JOINT'];
        $aReturn['status'] = "failed";
        echo $json->encode($aReturn);
        return;
        exit;
    }
    if(empty($oBean->account_id)){
        $aReturn['message'] = $mod_strings['LBL_MISSING_JOINT_CONTACT'];
        $aReturn['status'] = "failed";
        echo $json->encode($aReturn);
        return;
        exit;
    }
    $rs_template_id=$sugar_config['rs_template_individual_joint'];
    $oPrimaryAccount = BeanFactory::getBean('Accounts',$oBean->account_id);
    if(empty($oPrimaryAccount->email1)){
        $aReturn['message'] = $mod_strings['LBL_MISSING_JOINT_EMAIL_ADDRESS'];
        $aReturn['status'] = "failed";
        echo $json->encode($aReturn);
        return;
        exit;
    }
    $iFlag = true;
}
else
{
    $aReturn['message'] = $mod_strings['LBL_ACTION_NOT_SUPPORTED'];
    $aReturn['status'] = "failed";
    echo $json->encode($aReturn);
    return;
    exit;
}
//echo "<pre>222222222222222";
//echo "</pre>";
//die("aaaa");

$sNewGuid='';

if($iFlag)
{
    $sNewGuid='';
    $SugarUserId = $current_user->id;
    $oRSignatureClient = new UT_RightSignatureClient();
    
    $sDocumentName = '';
    $oResponsedetail = $oRSignatureClient->getTemplateDetails($rs_template_id);

    if(empty($oRSignatureClient->client->error))
    {
        $aResultDet = convertAllObjectToArray($oResponsedetail);
        if(!empty($aResultDet['reusable_template']))
        {
            //$guid = $aResultDet['reusable_template']['id'];
            $sDocumentName = $aResultDet['reusable_template']['name'];
            $sMessage = '';
        }
    }
    else{
        $aReturn['message'] = HtmlSpecialChars($oRSignatureClient->client->error);
        $aReturn['status'] = "failed";
        echo $json->encode($aReturn);
        return;
        exit;
    }
    //$sDocumentName = $_POST['subject'];
    $sSharedWith = array();
    //$sMessage = $_POST['message'];
    $sMessage = '';
    $bInPerson = NULL;
    $sCallbackURL = $sugar_config['site_url'].'/index.php?entryPoint=RSPushServiceCallback';
    $merge_field_values = array();
    $aRoles = array();
    $iExpiresIn = 30;
    $iPin = NULL;
    $aTags = array();
    $aSugarRecordInfo = array();
    
    $aFinalMergeField = array();
    $aFinalRoles = array();
    $aCCReceipients=array();

    if(!empty($rs_template_id))
    {
        $signer_sequencing = '';
        if(!empty($aResultDet['reusable_template']['merge_field_components']))
        {
            foreach($aResultDet['reusable_template']['merge_field_components'] as $idx => $aMergeComponent)
            {
                if(empty($aMergeComponent['name']))
                    continue;
                
                if($aMergeComponent['name'] == 'contract_month')
                    $sMergValue = $oBean->term_c;
                else if($aMergeComponent['name'] == 'sales_rep_name')
                    $sMergValue = $oBean->assigned_user_name;
                else if($aMergeComponent['name'] == 'monthly_recurring_fee'){
                    //$sMergValue = $oBean->amount;
			$sMergValue = currency_format_number($oBean->amount, $params = array('currency_symbol' => false));
		}
                else if($aMergeComponent['name'] == 'onetimefee'){
                    //$sMergValue = $oBean->setup_fee_c;
			$sMergValue = currency_format_number($oBean->setup_fee_c, $params = array('currency_symbol' => false));
		}
                else if($aMergeComponent['name'] == 'website_cost'){
                    //$sMergValue = $oBean->website_design_amount_c;
			$sMergValue = currency_format_number($oBean->website_design_amount_c, $params = array('currency_symbol' => false));
		}
                else if($aMergeComponent['name'] == 'free_days')
                    $sMergValue = $oBean->free_time_c;
                else if($aMergeComponent['name'] == 'website')
                    $sMergValue = $oBean->website_design_description_c;
                else if($aMergeComponent['name'] == 'fb_cost'){
                    //$sMergValue = $oBean->social_engagement_c;
			$sMergValue = currency_format_number($oBean->social_engagement_c, $params = array('currency_symbol' => false));
		}
                else if($aMergeComponent['name'] == 'serv_begin_on')
                    $sMergValue = $oBean->online_date_c;
                else if($aMergeComponent['name'] == 'company_name')
                    $sMergValue = $oPrimaryAccount->name;
                else if($aMergeComponent['name'] == 'service_date_start')
                    $sMergValue = $oBean->contract_date_c;
                
                 $aTempMergeField=array(
                    'id' => $aMergeComponent['id'],
                    'value' => $sMergValue
                    );
                $aFinalMergeField[]=$aTempMergeField;
            }
        }
        if(!empty($aResultDet['reusable_template']['roles'])){
            if(!empty($aResultDet['reusable_template']['signer_sequencing'])){
                $signer_sequencing = $aResultDet['reusable_template']['signer_sequencing'];
            }
            foreach($aResultDet['reusable_template']['roles'] as $sKey => $aRolesComponent)
            {
                if($aRolesComponent['name'] == 'Customer'){
                    $aSugarRecordInfo[] = array(
                                            'role' =>$aRolesComponent['name'],
                                            'name' => $oPrimaryAccount->name,
                                            'email' => $oPrimaryAccount->email1,
                                            'parent_id' => $oPrimaryAccount->id,
                                            'parent_type' => $oPrimaryAccount->module_dir,
                                        );
                    $sOwnerName=$oPrimaryAccount->name;
                    $sOwnerEmail=$oPrimaryAccount->email1;
                }
                else if($aRolesComponent['name'] == 'CompanyExecutive'){
                    //$sOwnerName='Alexandrea Hatfield';
//                    $sOwnerEmail='ahatfield@citymarketing.io';
                    $sOwnerName='Alexandrea Hatfield';
                    $sOwnerEmail='ahatfield@citymarketing.io';
                }
                $aTempRoles=array();
                $aTempRoles['name'] = $aRolesComponent['name'];
                $aTempRoles['signer_name'] = $sOwnerName;
                $aTempRoles['signer_email'] = $sOwnerEmail;
                if(!empty($signer_sequencing)){
                    $aTempRoles['sequence'] = $aRolesComponent['sequence'];
                }
                $aTempRoles['message'] = $aRolesComponent['message'];
                $aFinalRoles[]=$aTempRoles;
            }
        }
        
        if(!empty($aFinalMergeField)){
            $merge_field_values=$aFinalMergeField;
        }   
        if(!empty($aFinalRoles)){
            $aRoles=$aFinalRoles;
        }

//echo "<pre>merge_field_values = ";
//print_r($merge_field_values);
//echo "</pre>";
//echo "<pre>aRoles = ";
//print_r($aRoles);
//echo "</pre>";
//echo "<pre>aCCReceipients = ";
//print_r($aCCReceipients);
//echo "</pre>";
//echo "<pre>bInPerson = ";
//print_r($bInPerson);
//echo "</pre>";
//echo "<pre>sCallbackURL = ";
//print_r($sCallbackURL);
//echo "</pre>";
//echo "<pre>iPin = ";
//print_r($iPin);
//echo "</pre>";
//die("aaaaSTOP111111");

            $oResponse1 = $oRSignatureClient->sendTemplate($rs_template_id, $sDocumentName, $merge_field_values, $aRoles, $iExpiresIn, $aCCReceipients, $sMessage, $bInPerson, $sCallbackURL, $iPin, $aTags);
            if(empty($oRSignatureClient->client->error))
            {
//$GLOBALS['log']->fatal("1111111111111");
                $aResult = convertAllObjectToArray($oResponse1);
                if(!empty($aResult['document']['id'])) {
                    $sNewGuid = $aResult['document']['id'];
                }
            }
            else
            {
//$GLOBALS['log']->fatal("222222222222");
                $aReturn['message'] = HtmlSpecialChars($oRSignatureClient->client->error);
                $aReturn['status'] = "failed";
                echo $json->encode($aReturn);
                return;
                exit;
            }
        }
        else{
//$GLOBALS['log']->fatal("333333333333333");
            $GLOBALS['log']->fatal("No templates exists on RightSignature. Please create templates on RightSignature");
            $aReturn['message'] = HtmlSpecialChars($oRSignatureClient->client->error);
            $aReturn['status'] = "failed";
            echo $json->encode($aReturn);
            return;
            exit;
        }
}

if(!empty($sNewGuid))
{
//$GLOBALS['log']->fatal("44444444444444");    
            $oUTRightSignature = BeanFactory::getBean('UT_RightSignature');
            $id = create_guid();
            $oUTRightSignature->new_with_id = true;
            $oUTRightSignature->id = $id;
            $oUTRightSignature->document_name = 'RS : ';
            $oUTRightSignature->sending_type = 'select_template';
            
            $oUTRightSignature->rightsignature_templates = $rs_template_id;
            $oUTRightSignature->rs_doc_id = $sNewGuid; // This is the RS document id, So we can call and get the Document Details
            
            
            $oUTRightSignature->recipient_name = '-- Multiple Recipient --';
            $oUTRightSignature->email_address = '-- Multiple Recipient --';
            $oUTRightSignature->subject = $sDocumentName;
            $oUTRightSignature->message = $sMessage;
//$GLOBALS['log']->fatal("55555555555555");            
                $oResponseDocDetail = $oRSignatureClient->getDocumentDetails($sNewGuid);
//$GLOBALS['log']->fatal("666666666666666666");
                if(empty($oRSignatureClient->client->error)){
//$GLOBALS['log']->fatal("7777777777777");
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
//$GLOBALS['log']->fatal("8888888888888");                    
                    // Begin : For Original Document
                    if(!empty($aResultDocDetail['document']['original_file_url'])) {
//$GLOBALS['log']->fatal("999999999999999");
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
//$GLOBALS['log']->fatal("aaaa1111111111");
                    // END : For Original Document
                    if(!empty($aResultDocDetail['document']['original_file_url']))
                        $oUTRightSignature->pdf_url = $aResultDocDetail['document']['original_file_url'];
                    // Begin : For Signed Document
                    if(!empty($aResultDocDetail['document']['signed_pdf_url'])) {
//$GLOBALS['log']->fatal("aaaa2222222222222");
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
//$GLOBALS['log']->fatal("aaaa33333333333333");                    
                    $oUTRightSignature->save();
                    $oUTRightSignature->load_relationship("ut_rightsignature_ut_rsactivities");

                    if(!empty($aResultDocDetail['document']['audits'])){
//$GLOBALS['log']->fatal("aaaa4444444444444444");
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
//$GLOBALS['log']->fatal("aaaa555555555555555");
                    $_SESSION['urdhvarightsignature']['errormsg'] = HtmlSpecialChars($oRSignatureClient->client->error);
                    SugarApplication::appendErrorMessage(HtmlSpecialChars($oRSignatureClient->client->error));
                    SugarApplication::redirect('index.php?module=UT_RightSignature&action=SendDocumentRS');
                    exit;
               }
           
           
           if(!empty($aSugarRecordInfo)) {
//$GLOBALS['log']->fatal("aaaa666666666666");
//$GLOBALS['log']->fatal(print_r($aSugarRecordInfo,true));
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
                        //else if($field_value['parent_type'] == 'Contacts'){
//                            $sRelationShipName='ut_rightsignature_contacts_1';
//                            $oParObj = BeanFactory::getBean('Contacts',$field_value['parent_id']);
//                        }
                        if(!empty($sRelationShipName)){
//$GLOBALS['log']->fatal("aaaa777777777777777777");
                            $oParObj->load_relationship($sRelationShipName);
                            if ($oParObj->$sRelationShipName->add($oUTRightSignature->id) == false)
                                $GLOBALS['log']->fatal("Failed to associate (".$field_value['parent_id'].") with RightSignature (".$oUTRightSignature->id.")");
                        }
                        
                    }
                }
           }
    //$_SESSION['urdhvarightsignature']['message'] = $mod_strings['LBL_DOC_SENT_FOR_SIGNATURE'].' <a href="index.php?module=UT_RightSignature&action=DetailView&record='.$oUTRightSignature->id.'"> here</a>';
    $aReturn['message'] = '';
    $aReturn['status'] = "success";
}

//function getMergeFieldAndRoles($aResult,$sModule,$sFor,$oBean,$oJointContact,$oAuth1Contact,$oAuth2Contact)
function getMergeFieldAndRoles($aResult, $oBean, $oPrimaryAccount)
{
    $roles = $aSugarRecordInfo = $merge_fields = array();
    
    if(!empty($aResult['template']['roles']))
    {
        foreach($aResult['template']['roles'] as $idx => $aRole)
        {
            if($aRole['is_sender']=='true')
                continue;
            if($aRole['must_sign']=='true')
            {
                if($aRole['name'] == 'Customer'){
                    $roles[$aRole['name']]=array(
                                    'name'=>$oPrimaryAccount->name,
                                    'email'=>$oPrimaryAccount->email1,
                                    'locked' => 'true',
                                    );
                    $aSugarRecordInfo[] = array(
                                            'role' =>$aRole['name'],
                                            'name' => $oPrimaryAccount->name,
                                            'email' => $oPrimaryAccount->email1,
                                            'parent_id' => $oPrimaryAccount->id,
                                            'parent_type' => $oPrimaryAccount->module_dir,
                                        );
                }
                else if($aRole['name'] == 'Owner'){
                    $roles[$aRole['name']]=array(
                                    'name'=>'Dhaivat Naik',
                                    'email'=>'contact@urdhva-tech.com',
                                    'locked' => 'true',
                                    );
                }

            }
        }
    }
    if(!empty($aResult['template']['merge_fields']))
    {
        foreach($aResult['template']['merge_fields'] as $idx => $aMergeField)
        {
            if(empty($aMergeField['name']))
                continue;

            // For Primary contact
            if($aMergeField['name'] == 'salutation')
                $sValue = $oBean->salutation;
            else if($aMergeField['name'] == 'first_name')
                $sValue = $oBean->first_name;
            else if($aMergeField['name'] == 'last_name')
                $sValue = $oBean->last_name;
            else if($aMergeField['name'] == 'address')
                $sValue = $oBean->primary_address_street. ' '.$oBean->primary_address_city;
            else if($aMergeField['name'] == 'country')
                $sValue = $oBean->primary_address_country;
            else if($aMergeField['name'] == 'postcode')
                $sValue = $oBean->primary_address_postalcode;
            else if($aMergeField['name'] == 'home_phone')
                $sValue = $oBean->phone_work;
            else if($aMergeField['name'] == 'mobile_phone')
                $sValue = $oBean->phone_mobile;
            else if($aMergeField['name'] == 'email_address')
                $sValue = $oBean->email1;
            else if($aMergeField['name'] == 'date_of_birth')
                $sValue = $oBean->birthdate;
            else if($aMergeField['name'] == 'occupation')
                $sValue = $oBean->occupation_c;
            // For Joint Contact
            if($aMergeField['name'] == 'j_salutation')
                $sValue = $oPrimaryAccount->salutation;
            else if($aMergeField['name'] == 'j_first_name')
                $sValue = $oPrimaryAccount->first_name;
            else if($aMergeField['name'] == 'j_last_name')
                $sValue = $oPrimaryAccount->last_name;
            else if($aMergeField['name'] == 'j_address')
                $sValue = $oPrimaryAccount->primary_address_street. ' '.$oPrimaryAccount->primary_address_city;
            else if($aMergeField['name'] == 'j_country')
                $sValue = $oPrimaryAccount->primary_address_country;
            else if($aMergeField['name'] == 'j_postcode')
                $sValue = $oPrimaryAccount->primary_address_postalcode;
            else if($aMergeField['name'] == 'j_home_phone')
                $sValue = $oPrimaryAccount->phone_work;
            else if($aMergeField['name'] == 'j_mobile_phone')
                $sValue = $oPrimaryAccount->phone_mobile;
            else if($aMergeField['name'] == 'j_email_address')
                $sValue = $oPrimaryAccount->email1;
            else if($aMergeField['name'] == 'j_date_of_birth')
                $sValue = $oPrimaryAccount->birthdate;
            
            $merge_fields[$aMergeField['name']] = array(
                                    'value' => $sValue,
                                    'locked' => 'true',
                                  );
        }
    }
    $aReturn['roles'] = $roles;
    $aReturn['sugarrecordinfo'] = $aSugarRecordInfo;
    $aReturn['merge_fields'] = $merge_fields;
    return $aReturn;
}
echo $json->encode($aReturn);
exit();
?>
