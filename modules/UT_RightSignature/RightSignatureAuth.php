<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
    require_once('modules/UT_RightSignature/RightSignatureUtils.php');
    
    global $db, $mod_strings,$current_user,$app_list_strings, $sugar_config;
    $base_mod_string = return_module_language("", "UT_RightSignature");
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
    // checking if current user is not admin then do not procced further
    if(!$current_user->is_admin){
        echo $base_mod_string['LBL_NOT_AUTHORIZED'];
        return;
    }
    $sugar_smarty   = new Sugar_Smarty();
    
    $sSql = "SELECT * FROM ut_rightsignature_api_keys WHERE deleted=0 ";
    $sSql = $db->limitQuery($sSql, 0, 1,false,'',false);
    $oRes = $db->query($sSql,true);
    $aRow = $db->fetchByAssoc($oRes);
    if(empty($aRow)){
        $_SESSION['urdhvarightsignature']['errormsg']=$base_mod_string['LBL_UT_AUTH_KEY_SECRET_MISSING'];
        $url = "index.php?module=UT_RightSignature&action=RightSignatureAppKeys";
        SugarApplication::redirect($url);
    }

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
    
    $isSetRightSignatureKey = false;
    $isSetRightSignatureAuth = false;

    $aAppKeys = getApplicationKey();
    $aOAuthDBSettings = getOAuthDBSettings();

    if(!empty($aAppKeys))
        $isSetRightSignatureKey = true;
    if(!empty($aOAuthDBSettings))
        $isSetRightSignatureAuth = true;
    
    $sugar_smarty->assign('mod', $base_mod_string);
    $sugar_smarty->assign('app', $app_strings);
	$sugar_smarty->assign('applist' , $app_list_strings['moduleList']);
    $sugar_smarty->assign('rs_consumer_key' , $aRow['consumer_secret']);
    $sugar_smarty->assign('rs_consumer_secret' , $aRow['consumer_secret']);

    $sugar_smarty->assign('isSetRightSignatureKey' , $isSetRightSignatureKey);
    $sugar_smarty->assign('isSetRightSignatureAuth' , $isSetRightSignatureAuth);
    
    $sugar_smarty->assign('site_url' , $sugar_config['site_url']);
    $sRedirectURL = $sugar_config['site_url'].'/UT_RSCallback.php';
    $sugar_smarty->assign('redirecturl_information' , $sRedirectURL);
    $sugar_smarty->display('modules/UT_RightSignature/tpls/RightSignatureAuth.tpl');
    