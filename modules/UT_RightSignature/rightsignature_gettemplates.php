<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
//rightsignature_gettemplates_cron();
function rightsignature_gettemplates_cron()
{
    require_once('modules/UT_RightSignature/RightSignatureUtils.php');
    require_once('modules/UT_RightSignature/oauth-api/library/UT_RightSignatureClient.php');
    global $db,$timedate,$app_list_strings, $sugar_config,$current_user;
    
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
    
    $iFlag = true;
    $newArray = array();
    $query=NULL;
    $perPage=20;
    $page=1;
    $iTotalPage = 1;
    $iCurrentPage = 1;
    $oRSignatureClient = new UT_RightSignatureClient('',true,1);
    do
    {
        $oResponse = $oRSignatureClient->getTemplates($query, $perPage,$page);
        if(empty($oRSignatureClient->client->error))
        {
            $aResult = convertAllObjectToArray($oResponse);
            if(!empty($aResult['reusable_templates'])) {
                $page = $page+1;
                foreach($aResult['reusable_templates'] as $sKey => $aTemplate){
                    $newArray[$aTemplate['id']] = $aTemplate['name'];
                }
            }
            else {
                $iFlag = false;
            }
        }
        else {
            $iFlag = false;
        }
    }while($iFlag==true);
    
    if(!empty($newArray)){
        $dropdown_name='rightsignature_templates_list';
        update_rs_dropdown($dropdown_name, $newArray);
    }
}
?>