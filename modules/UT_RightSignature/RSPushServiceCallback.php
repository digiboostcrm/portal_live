<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 08/18/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

  require_once('modules/UT_RightSignature/RightSignatureUtils.php');
  require_once("modules/UT_RightSignature/rightsignature_docupdate.php");
  global $db,$timedate,$mod_strings, $current_language,$sugar_config;
  $aModString_RSActivities = return_module_language($current_language,'UT_RSActivities');
  $aDataCallBack = json_decode(file_get_contents('php://input'),true);
  if(!empty($aDataCallBack) && !empty($aDataCallBack['callbackType']))
  {
      if($aDataCallBack['callbackType'] == 'Document' && !empty($aDataCallBack['id']) 
            && !empty($aDataCallBack['documentState']) && $aDataCallBack['documentState'] != 'created')
      {
          $sSql = "SELECT id, rs_doc_id 
                    FROM ut_rightsignature 
                    WHERE rs_doc_id='".$aDataCallBack['id']."' AND deleted=0 ";
          $sSql = $db->limitQuery($sSql, 0, 1,false,'',false);
          $oRes = $db->query($sSql,true);
          $aRow = $db->fetchByAssoc($oRes);
          if(!empty($aRow['id'])) {
              $status = rightsignature_docupdate_cron($aRow['id']);
          }
      }
  }
  elseif(!empty($aDataCallBack) && empty($aDataCallBack['callbackType'])) //sending_request
  {
      $sSql = "SELECT id, rs_doc_id, sending_request_id  
                    FROM ut_rightsignature 
                    WHERE sending_request_id='".$aDataCallBack['id']."' AND deleted=0 ";
      $oRes = $db->query($sSql,true);
      $aRow = $db->fetchByAssoc($oRes);
      if(!empty($aRow['id']))
      {
          $oUTRightSignature = BeanFactory::getBean('UT_RightSignature',$aRow['id']);
          //"waiting_for_file" | "downloading" | "processing" | "creating_document" | "completed" | "errored"
          $oUTRightSignature->processing_state = $aDataCallBack['status'];
          if($aDataCallBack['status'] == 'completed'){
                if(!empty($aDataCallBack['document_template_id'])){
                    $oUTRightSignature->rs_doc_id = $aDataCallBack['document_template_id'];
                }
          }
          elseif($aDataCallBack['status'] == 'errored'){
              //"waiting_for_file" | "downloading" | "processing" | "creating_document" | "completed" | "errored"
          }
          $oUTRightSignature->save();
          $status = rightsignature_docupdate_cron($aRow['id']);
      }
  }
?>