<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
$job_strings[]='rightsignature_docupdate';
$job_strings[]='rightsignature_gettemplates';
/***
* Update Right Signatuare documents status
* 
*/
function rightsignature_docupdate() {
    $GLOBALS['log']->debug("Get status of the documents from RightSignature START");
    require_once("modules/UT_RightSignature/rightsignature_docupdate.php");
    rightsignature_docupdate_cron();
    $GLOBALS['log']->debug("Get status of the documents from RightSignature END");
    return true;
}
/***
* Get templates from Right Signature
*/
function rightsignature_gettemplates() {
    $GLOBALS['log']->debug("Get status of the documents from RightSignature START");
    require_once("modules/UT_RightSignature/rightsignature_gettemplates.php");
    rightsignature_gettemplates_cron();
    $GLOBALS['log']->debug("Get status of the documents from RightSignature END");
    return true;
}