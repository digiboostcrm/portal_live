<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
if(!defined('sugarEntry') || !sugarEntry) define('sugarEntry', true);
include_once("include/entryPoint.php");
//$array_get = json_decode(file_get_contents('php://input'));
//error_log("handle_callback =============== ");
//error_log(print_r($array_get,true));
//define('sugarEntry',true);
//set_include_path("../../");
//require_once('config.php');
//require_once('config_override.php');
//require_once('include/database/DBManager.php');
//require_once('include/database/DBManagerFactory.php');
//require_once('include/SugarLogger/LoggerManager.php');
//$GLOBALS['log'] = LoggerManager::getLogger('SugarCRM');
global $current_user, $db, $sugar_config;
//$db = DBManagerFactory::getInstance();
//$oAcc = BeanFactory::getBean('Accounts','47cdd986-ff4c-fa25-d0e8-58909119eafb');
$oUser = new User();
$oUser->retrieve("1"); // master
$current_user = $oUser;

$SugarUserId = 1;
function utRedirectToAuth(){
    $queryParams = array(
        'module' => 'UT_RightSignature',
        'action' => 'RightSignatureAuth',
    );
    SugarApplication::redirect('index.php?' . http_build_query($queryParams));
}
require('modules/UT_RightSignature/oauth-api/library/UT_RightSignatureClient.php');
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
    else if(!empty($_REQUEST['code']) && !empty($_REQUEST['state']))
          utRedirectToAuth();
}
?>