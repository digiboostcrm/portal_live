<?php
/**
 * The file used to manage actions for Customer portal views 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */

require_once 'custom/biz/classes/Portalcontroller.php';
$oPortalcontroller = new Portalcontroller();
if(!empty($_REQUEST['method']))
{
    $method = $_REQUEST['method'];
    switch ($method) {
        case 'validateLicence':
            $result = $oPortalcontroller->validateLicence();
            break;
        case 'enableDisableExtension':
            $result = $oPortalcontroller->enableDisableExtension();
            break;
        case 'getModuleFields':
            $result = $oPortalcontroller->getModuleFields();
            break;
        case 'saveListLayout':
            $result = $oPortalcontroller->saveListLayout();
            break;
        case 'saveEditLayout':
            $result = $oPortalcontroller->saveEditLayout();
            break;
        case 'setUrl':
            $result = $oPortalcontroller->setUrl();
            break;
        case 'checkExistEmail':
            $result = $oPortalcontroller->checkExistEmail();
            break;
         case 'check_portaluser':
            $result = $oPortalcontroller->check_portaluser();
            break;
         case 'validfile':
            $result = $oPortalcontroller->validfile();
            break;
         case 'getpaymentmoduleFields':
            $result = $oPortalcontroller->getpaymentmoduleFields();
            break;
        case 'CheckPortalAccesibleGroup':
            $result = $oPortalcontroller->CheckPortalAccesibleGroup();
            break;
        case 'moduleAccessLevel':
            $result = $oPortalcontroller->savemoduleaccesslevel();
            break;
         case 'portalContactsExport':
            $result = $oPortalcontroller->portalContactsExport();
            break;
        case 'generalConfiguration':
            $result = $oPortalcontroller->generalConfiguration();
            break;
        case 'gmailMailbox':
            $result = $oPortalcontroller->gmailMailbox();
            break;
        case 'saveEmailconfig':
            $result = $oPortalcontroller->saveEmailconfig();
            break;
        default:
            break;
    }
}
else
{
    echo 'not valid action';
}
ob_clean();    
echo json_encode($result);
die();
