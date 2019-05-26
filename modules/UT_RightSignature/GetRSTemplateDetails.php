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
$json = getJSONobj();
global $db, $mod_strings;
$aReturn = array(
                'message' => '',
                'status' => 'failed',
                'data' => array(
                            'roles_html'=>'',
                            'mergefields_html'=>'',
                          ),
           );
if(empty($_REQUEST['rs_template_id'])) {
    $aReturn['message'] = $mod_strings['LBL_RS_TEMPLATEID_NOT_FOUND'];
    $aReturn['status'] = "failed";
    echo $json->encode($aReturn);
    return;
}
$rs_template_id = $_REQUEST['rs_template_id'];
$iCount = $_REQUEST['count'];
$iCCCount = 0;
$sHTML=$sMergeFieldsHTML=$sFinalMergeFieldHTML=$sCCHtml='';
$iPrePopulateSignerFromDetailView = false;
$iPrePopulatedSignerSet=false;
if(!empty($_REQUEST['prePopFromDetailViewModule']) && !empty($_REQUEST['prePopFromDetailViewId']) && !empty($_REQUEST['prePopFromDetailViewName']))
    $iPrePopulateSignerFromDetailView = true;

$oRSignatureClient = new UT_RightSignatureClient();
$oResponse = $oRSignatureClient->getTemplateDetails($rs_template_id);
if(empty($oRSignatureClient->client->error))
{
    $aResult = convertAllObjectToArray($oResponse);
    if(!empty($aResult['reusable_template']))
    {
        $guid = $aResult['reusable_template']['id'];
        $sDocumentName = $aResult['reusable_template']['name'];
        $sMessage = '';
        $aReturn['data']['guid']=$guid;
        $aReturn['data']['subject']=$sDocumentName;
        $aReturn['data']['description']=$sMessage;
        $sFinalHTML='';
        
        if(!empty($aResult['reusable_template']['expires_in']))
            $expires_in=$aResult['reusable_template']['expires_in'];
        else
            $expires_in=30;
            
        if(!empty($aResult['reusable_template']['roles'])){
            if(!empty($aResult['reusable_template']['signer_sequencing'])){
                $sHTML .= '<input type="hidden" id="signer_sequencing" name="signer_sequencing" class="form-control input-sm" value="'.$aResult['reusable_template']['signer_sequencing'].'">';
            }
            $sHTML .= '<input type="hidden" id="expires_in" name="expires_in" class="form-control input-sm" value="'.$expires_in.'">';
            foreach($aResult['reusable_template']['roles'] as $sKey => $aRolesComponent)
            {
                $aReturn['data']['description']=$aRolesComponent['message'];
                $iCount++;
                $sImgSrc = "";
                $prePopFromDetailViewModule = $prePopFromDetailViewId = $prePopFromDetailViewName = $prePopFromDetailViewEmail = '';
                if($iPrePopulateSignerFromDetailView == true && $iPrePopulatedSignerSet == false)
                {
                    $prePopFromDetailViewModule = $_REQUEST['prePopFromDetailViewModule'];
                    $prePopFromDetailViewId = $_REQUEST['prePopFromDetailViewId'];
                    $prePopFromDetailViewName = $_REQUEST['prePopFromDetailViewName'];
                    $prePopFromDetailViewEmail = $_REQUEST['prePopFromDetailViewEmail'];
                    $iPrePopulatedSignerSet = true;
                }
                $sImgSrc = "rightsignature_32x32.png";
                $sHTML .= '<div class="col-xs-12" id="emlRow_'.$iCount.'" name="emlRow_'.$iCount.'">
<div class="form-group">
   <div class="col-xs-12">
      <div class="form-inline">
              <div class="form-group">
                 <div class="col-xs-3">
                    <img id="img_signer_cc---'.$iCount.'" src="modules/UT_RightSignature/images/'.$sImgSrc.'" width="16">
                 </div>
              </div>
              <div class="form-group">
                 <div class="col-xs-3">
                  <label class="sronly">'.$aRolesComponent['name'].'</label>
                  <input type="text" id="receipient_name___'.$iCount.'" name="receipient_name___'.$iCount.'" value="'.$prePopFromDetailViewName.'" class="form-control input-sm" placeholder="'.$mod_strings['LBL_RECIPIENT_NAME'].'" required data-validation-required-message="'.$mod_strings['LBL_RECIPIENT_NAME'].'" >
                  <input type="hidden" id="must_sign|||'.$iCount.'" name="must_sign|||'.$iCount.'" class="form-control input-sm" value="">
                  <input type="hidden" id="sequence|||'.$iCount.'" name="sequence|||'.$iCount.'" class="form-control input-sm" value="'.$aRolesComponent['sequence'].'">
                  <input type="hidden" id="role|||'.$iCount.'" name="role|||'.$iCount.'" class="form-control input-sm" value="'.$aRolesComponent['name'].'">
                  <input type="hidden" id="sugar_module___'.$iCount.'" name="sugar_module___'.$iCount.'" value="'.$prePopFromDetailViewModule.'">
                  <input type="hidden" id="sugar_module_id___'.$iCount.'" name="sugar_module_id___'.$iCount.'" value="'.$prePopFromDetailViewId.'">
                 </div>
              </div>
              <div class="form-group">
                <div class="col-xs-3">
                  <label class="sronly"></label>
                  <input type="text" id="receipient_email___'.$iCount.'" name="receipient_email___'.$iCount.'" value="'.$prePopFromDetailViewEmail.'" class="form-control input-sm" placeholder="'.$mod_strings['LBL_RECIPIENT_EMAIL'].'" required data-validation-required-message="'.$mod_strings['LBL_RECIPIENT_EMAIL'].'" >
                </div>
              </div>
              <div class="form-group ">
                     <div class="col-xs-3">
                        <span class="id-ff multiple">
                            <button type="button" name="btnSelectSignerFromTemplate" id="btnSelectSignerFromTemplate" clickedCount="'.$iCount.'" title="Select a signer from CRM" class="btnSelectSignerFromTemplate button firstChild" value="'.$mod_strings['LBL_ARROW_SIGNER_FROM_CRM'].'">
                            <img src="themes/SuiteP/images/id-ff-select.png">
                            </button>
                        </span>
                     </div>
                  </div>
      </div>
      <p class="help-block"></p>
   </div>
 </div>
</div>';
            }
        }
        if(!empty($aResult['reusable_template']['shared_with'])){
            //$sCCHtml='';
            $sImgSrc = "cc.png";
            foreach($aResult['reusable_template']['shared_with'] as $sKey => $aCC)
            {
                $iCount++;
                $sHTML .= '<div class="col-xs-12" id="emlRow_'.$iCount.'" name="emlRow_'.$iCount.'">
<div class="form-group">
   <div class="col-xs-12">
      <div class="form-inline">
              <div class="form-group">
                 <div class="col-xs-3">
                    <img id="img_signer_cc---'.$iCount.'" src="modules/UT_RightSignature/images/'.$sImgSrc.'" width="16">
                 </div>
              </div>
              <div class="form-group">
                 <div class="col-xs-3">
                  <label class="sronly">CC</label>
                  <input type="text" id="receipient_name___'.$iCount.'" name="receipient_name___'.$iCount.'" value="" class="form-control input-sm" placeholder="'.$mod_strings['LBL_RECIPIENT_NAME'].'" required data-validation-required-message="'.$mod_strings['LBL_RECIPIENT_NAME'].'" >
                  <input type="hidden" id="cc|||'.$iCount.'" name="cc|||'.$iCount.'" class="form-control input-sm" value="">
                  <input type="hidden" id="sugar_module___'.$iCount.'" name="sugar_module___'.$iCount.'" value="">
                  <input type="hidden" id="sugar_module_id___'.$iCount.'" name="sugar_module_id___'.$iCount.'" value="">
                 </div>
              </div>
              <div class="form-group">
                <div class="col-xs-3">
                  <label class="sronly"></label>
                  <input type="text" id="receipient_email___'.$iCount.'" name="receipient_email___'.$iCount.'" value="'.$aCC.'" class="form-control input-sm" placeholder="'.$mod_strings['LBL_RECIPIENT_EMAIL'].'" required data-validation-required-message="'.$mod_strings['LBL_RECIPIENT_EMAIL'].'" >
                </div>
              </div>
              <div class="form-group ">
                     <div class="col-xs-3">
                        <span class="id-ff multiple">
                            <button type="button" name="btnSelectSignerFromTemplate" id="btnSelectSignerFromTemplate" clickedCount="'.$iCount.'" title="Select a signer from CRM" class="btnSelectSignerFromTemplate button firstChild" value="'.$mod_strings['LBL_ARROW_SIGNER_FROM_CRM'].'">
                            <img src="themes/SuiteP/images/id-ff-select.png">
                            </button>
                        </span>
                     </div>
                  </div>
      </div>
      <p class="help-block"></p>
   </div>
 </div>
</div>';
            }
        }
        $sFinalMergeFieldHTML='';
        if(!empty($aResult['reusable_template']['merge_field_components']))
        {
            foreach($aResult['reusable_template']['merge_field_components'] as $idx => $aMergeComponent)
            {
                $sMergeFieldsHTML.='<div class="col-xs-12">
                    <div class="form-group col-xs-6">
                        <label for="subject" class="control-label">'.$aMergeComponent['name'].'</label>
                        <input type="text" class="form-control" name="mergeid|||'.$aMergeComponent['id'].'" value="" required="" title="'.$aMergeComponent['name'].'" placeholder="'.$aMergeComponent['name'].'">
                        <span class="help-block"></span>
                    </div>
                </div>';
            }
            $sFinalMergeFieldHTML='<div class="col-xs-3 mergefield_block">
                                    <div class="form-group">
                                        <p style="font-weight:bold;">Merge Fields</p>
                                        <p>'.$mod_strings['LBL_REQUIRE_ADDITIONAL_INFO'].'</p>
                                    </div>
                                </div>
                                <div class="col-xs-9 mergefield_block">
                                    <div class="col-xs-12">
                                    '.$sMergeFieldsHTML.'
                                    </div>
                                </div>
                                <hr class="mergefield_block">';
            
        }
        $aReturn['count'] = $iCount;
        $aReturn['data']['roles_html']=$sHTML;
        $aReturn['data']['mergefields_html']=$sFinalMergeFieldHTML;
        $aReturn['message'] = "";
        $aReturn['status'] = "success";
    }
    else{
        $GLOBALS['log']->fatal("No templates exists on RightSignature. Please create templates on RightSignature");
        $aReturn['message'] = $mod_strings['LBL_RS_NO_TEMPLATES'];
        $aReturn['status'] = "failed";
    }
}
else{
    $GLOBALS['log']->fatal("RightSignature Client Error : ".HtmlSpecialChars($oRSignatureClient->client->error));
    $aReturn['message'] = "RightSignature Client Error : ".HtmlSpecialChars($oRSignatureClient->client->error);
    $aReturn['status'] = "failed";
}
echo $json->encode($aReturn);
exit();
?>