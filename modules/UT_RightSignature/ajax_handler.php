<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
if(!empty($_REQUEST['id'])) {
    //Update status of selected record
    global $mod_strings,$current_user;
    if(ACLAction::userHasAccess($current_user->id, 'UT_RightSignature', 'edit','module', true, true))
    {
        require_once("modules/UT_RightSignature/rightsignature_docupdate.php");
        $status = rightsignature_docupdate_cron($_REQUEST['id']);
        echo json_encode($mod_strings['LBL_STATUS_UPDATED']);
    }
    else
        echo json_encode($mod_strings['LBL_NOT_AUTHORIZED']);
    exit();
}
?>