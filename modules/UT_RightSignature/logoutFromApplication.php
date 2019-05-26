<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
  require_once('modules/UT_RightSignature/RightSignatureUtils.php');
  global $db,$mod_strings,$current_user;
  $aReturn = array(
        "status" => "failed",
        "data" => array());
  $isSetSocialKey = $isSetSocialAuth = false;
  $sHtml = '';
  if(!empty($_REQUEST['social'])){
      $sSocial = $_REQUEST['social'];
      //$sDeleteOAuthSessionSQL = "DELETE FROM ut_oauth_session WHERE server='RightSignature' AND user='".$current_user->id."'";
      $sDeleteOAuthSessionSQL = "DELETE FROM ut_oauth_session WHERE server='RightSignature' AND user_fld='1'";
      $db->query($sDeleteOAuthSessionSQL,true);
      
      $aAppKeys = getApplicationKey();
      $aOAuthDBSettings = getOAuthDBSettings($current_user->id);
      if(!empty($aAppKeys))
        $isSetSocialKey = true;
      if(!empty($aOAuthDBSettings))
        $isSetSocialAuth = true;
      
      $sLabel="";
      if($sSocial == "rightsignature"){
          $sLogInLabel = $mod_strings['LBL_SIGNIN_RIGHTSIGNATURE'];
          $sLogoutLabel = $mod_strings['LBL_SIGNOUT_RIGHTSIGNATURE'];
      }
      
      if ($isSetSocialKey==true && $isSetSocialAuth==true){
            $sHtml='<a class="btn btn-primary actionsociallogout" social="'.$sSocial.'">
                <img src="modules/UT_RightSignature/images/rightsignature_32x32.png" /> '.$sLogoutLabel.'
            </a>';
      }
      elseif ($isSetSocialKey==true && $isSetSocialAuth==false){
            $sHtml='<a class="btn btn-primary actionsociallogin" social="'.$sSocial.'" data-toggle="modal" data-target="#myRSModal">
                <img src="modules/UT_RightSignature/images/rightsignature_32x32.png" /> '.$sLogInLabel.'
            </a>';
      }
      elseif ($isSetSocialKey==false){
            $sHtml='<div class="alert alert-danger" style="font-size:14px;">
              <i class="fa fa-warning fa-lg"></i>&nbsp;&nbsp;<strong>Opps!</strong> The <a href="index.php?module=UT_RightSignature$action=RightSignatureAppKeys" class="alert-link">Application keys</a> are missing.
            </div>';
      }
      $aReturn['status'] = "success";
      $aReturn['data']['html'] = $sHtml;
      $aReturn['data']['social'] = $sSocial;
  }
  echo json_encode($aReturn);
?>