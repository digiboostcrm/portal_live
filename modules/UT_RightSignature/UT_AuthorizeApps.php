<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
/*
global $db, $current_user, $sugar_config;
//$SugarUserId = $current_user->id;
$SugarUserId = 1;
function utRedirectToAuth(){
    $queryParams = array(
        'module' => 'UT_RightSignature',
        'action' => 'RightSignatureAuth',
    );
    SugarApplication::redirect('index.php?' . http_build_query($queryParams));
}

if(!empty($_REQUEST['entryPoint']) && $_REQUEST['entryPoint']=='RightSignatureCallback') {
	
    require('modules/UT_RightSignature/oauth-api/library/UT_RightSignatureClient.php');
    //$oRSignatureClient = new UT_RightSignatureClient('',true,$SugarUserId);
    $scope='read write';
    $oRSignatureClient = new UT_RightSignatureClient($scope);
    if(!empty($oRSignatureClient->client->error)){
          $GLOBALS['log']->fatal("Api response has error : Integration Callback to Rightsignature failed.");
          $GLOBALS['log']->fatal(print_r($oRSignatureClient->client->error,true));
          echo "Api response has error : Integration Callback to Rightsignature failed.";
          echo HtmlSpecialChars($oRSignatureClient->client->error);
    }
    else{
        if(!empty($_REQUEST['oauth_token']) && !empty($_REQUEST['oauth_verifier']))
              utRedirectToAuth();
    }
}
else
{
    $GLOBALS['log']->fatal("Incorrect Entrypoint argument provided for Integration");
    //utRedirectToAuth();
}
*/
?>