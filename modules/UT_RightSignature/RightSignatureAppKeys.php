<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
    global $db, $mod_strings,$current_user,$app_list_strings,$sugar_config;
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
    $aFinalRes = array(
        'consumer_key'=>'',
        'consumer_secret'=>'',
    );
    $sugar_smarty   = new Sugar_Smarty();
    $sSelSql = "SELECT * FROM ut_rightsignature_api_keys WHERE deleted=0 ";
    $sSelSql = $db->limitQuery($sSelSql, 0, 1,false,'',false);
    $oRes = $db->query($sSelSql,true);
    $aRow = $db->fetchByAssoc($oRes);
    
    if(!empty($_POST) && !empty($_POST['consumer_key']) && !empty($_POST['consumer_secret']))
    {
        $aFinalRes['consumer_key'] = $_POST['consumer_key'];
        $aFinalRes['consumer_secret'] = $_POST['consumer_secret'];
        
        if(empty($aRow)) {
            $sGuid = create_guid();
            $sInsertSql = "INSERT INTO ut_rightsignature_api_keys (id, consumer_key, consumer_secret, deleted) 
                        VALUES ('".$sGuid."', '".$aFinalRes['consumer_key']."', '".$aFinalRes['consumer_secret']."', 0);";
            $db->query($sInsertSql,true);
        }
        else
        {
            $sUpdateSql = "UPDATE ut_rightsignature_api_keys SET consumer_key='".$_POST['consumer_key']."', consumer_secret='".$_POST['consumer_secret']."' WHERE id='".$aRow['id']."'";
            $db->query($sUpdateSql,true);
        }
        $_SESSION['urdhvarightsignature']['message'] = $base_mod_string['LBL_SETTINGS_DONE'];
        
        //BEGIN : Delete all oauth_session for all users of the application if any of its keys are empty
        $sSelSql = "SELECT * FROM ut_rightsignature_api_keys WHERE deleted=0 ";
        $sSelSql = $db->limitQuery($sSelSql, 0, 1,false,'',false);
        $oTRs = $db->query($sSelSql,true);
        $aTrRow = $db->fetchByAssoc($oTRs);
        if(empty($aTrRow['consumer_key']) || empty($aTrRow['consumer_secret'])) {
            $sDeleteSQL = "DELETE FROM ut_oauth_session WHERE server='RightSignature'";
           //$db->query($sDeleteSQL,true);
        }
        //ENDS : Delete all oauth_session for all users of the application if its keys are empty
        // Redirect to oAuth Page.
        $url = "index.php?module=UT_RightSignature&action=RightSignatureAuth";
        SugarApplication::redirect($url);
    }
    if(!empty($aRow) && !empty($aRow['consumer_key']) && !empty($aRow['consumer_secret']))
    {
        $aFinalRes['consumer_key'] = $aRow['consumer_key'];
        $aFinalRes['consumer_secret'] = $aRow['consumer_secret'];
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
    $sugar_smarty->assign('mod', $base_mod_string);
    $sugar_smarty->assign('app', $app_strings);
	$sugar_smarty->assign('applist' , $app_list_strings['moduleList']);
    $sugar_smarty->assign('rs_consumer_key' , $aFinalRes['consumer_key']);
    $sugar_smarty->assign('rs_consumer_secret' , $aFinalRes['consumer_secret']);
    $sRedirectURL = $sugar_config['site_url'].'/UT_RSCallback.php';
    $sugar_smarty->assign('redirecturl_information' , $sRedirectURL);
    
    $sugar_smarty->display('modules/UT_RightSignature/tpls/RightSignatureAppKeys.tpl');