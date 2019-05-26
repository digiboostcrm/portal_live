<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
global $db,$app_strings,$app_list_strings,$mod_strings;
$module = $recordId = $modalView = '';
$module = $_REQUEST['moduleName'];
$recordId = $_REQUEST['recordId'];
$modalView = $_REQUEST['modalView'];
global $db;
$error_message = '';
$base_mod_string = return_module_language("", "UT_RightSignature");
if($modalView == "DetailView") {
    switch($module){
        case 'Accounts':
        case 'Contacts':
        case 'Leads':
              $oBean = BeanFactory::getBean($module,$recordId);
              if(empty($oBean->email1))
              $error_message=$mod_strings['LBL_ERRMSG_1'];
            break;
        case 'AOS_Quotes':
        case 'AOS_Invoices':
            $oBean = BeanFactory::getBean($module,$recordId);
            if(!empty($oBean->billing_contact_id)){
                $oSubBean = BeanFactory::getBean("Contacts",$oBean->billing_contact_id);
                if(empty($oSubBean->email1))
                    $error_message = sprintf($mod_strings['LBL_ERRMSG_2'], $oSubBean->full_name);
            }
            else {
                $error_message=$mod_strings['LBL_ERRMSG_3'];
            }
           break;
         case 'AOS_Contracts':
            $oBean = BeanFactory::getBean($module,$recordId);
            if(!empty($oBean->contact_id)){
                $oSubBean = BeanFactory::getBean("Contacts",$oBean->contact_id);
                if(empty($oSubBean->email1))
                    $error_message = sprintf($mod_strings['LBL_ERRMSG_2'], $oSubBean->full_name);
            }
            else
                $error_message=$mod_strings['LBL_ERRMSG_3'];
           break;
    }
    $selectTemplatesForm = $selectTemplatesRows = "";
    $selectTemplatesForm = '<div id="DivTemplatList" style="display:none;">
                            <hr style="margin-top:10px; margin-top:10px;">
                            <div style="padding: 5px 20px; overflow: auto; height: 200px;">';
    if(empty($error_message)){
        
        $selectTemplatesForm .= '<form id="ut_pdfgenerate_form" method="post">';
        $sTHead .= '<table class="list view table-responsive subpanel-table footable footable-1 breakpoint-lg" data-empty="No results found." style="display: table;" cellspacing="0" cellpadding="0" border="0">
                        <thead>
                            <tr class="footable-header">
                                <th data-breakpoints="" data-type="html" style="display: table-cell; text-align:center;color:white;">
                                    '.$base_mod_string['LBL_CRM_TEMPLATES'].'
                                </th>
                            </tr>
                        </thead>';
        if(!empty($module)) {
            $sql = "SELECT aos_pdf_templates.id,aos_pdf_templates.name FROM aos_pdf_templates WHERE aos_pdf_templates.type = '".$module."' AND  aos_pdf_templates.active =1 AND aos_pdf_templates.deleted = 0 ORDER BY aos_pdf_templates.date_entered DESC";
            $result = $db->query($sql,true);
            $iclsCnt=1;
            while ($row = $db->fetchByAssoc($result)) {
                if($iclsCnt%2==0)
                    $sRowCls = 'oddListRowS1';//even
                else
                    $sRowCls = 'evenListRowS1';//odd
                $selectTemplatesRows .= '<tr class="'.$sRowCls.'" style="padding:10px;">';
                $selectTemplatesRows .= '<td style="display: table-cell;" align="left"><b><a href="javascript:void(0)" onclick="processSignature(\''.$module.'\',\''.$recordId.'\',\''.$row['id'].'\')">'.$row['name'].'</a></b></td>';
                $selectTemplatesRows .= '</tr>';
                $iclsCnt++;
            }
        }
        if(empty($selectTemplatesRows))
            $selectTemplatesRows .= '<tr class="evenListRowS1" ><td style="display: table-cell;" colspan="2"><font class="overdueTask">'.(str_replace ("{module_name}", $app_list_strings['moduleList'][$module], $base_mod_string["LBL_NO_PDF_TEMPLATE_FOUND_ERROR"])).'</font></td></tr>';
        $sTBody = '<tbody>';
        $sTBody .= $selectTemplatesRows;
        $sTBody .= '</tbody>
                    </table>';
        $sTplTableHTML = $sTHead.$sTBody;
        $selectTemplatesForm .=$sTplTableHTML.'</form>';
        
    }
    else {
       $selectTemplatesForm .= "<p style='text-align: center;'><font class='overdueTask'>{$error_message}</font></p>"; 
    }
    $selectTemplatesForm .= '</div></div>';
    
    if($module == 'Accounts' || $module == 'Contacts' || $module == 'Leads' || $module == 'Prospects')
    {
        $sAllowedOption='<option label="" value=""></option>
                        <option label="'.$mod_strings['LBL_SELECT_CRM_PDF'].'" value="select_crm_pdf">'.$mod_strings['LBL_SELECT_CRM_PDF'].'</option>
                        <option label="'.$mod_strings['LBL_SELECT_RS_TEMPLATE'].'" value="select_template" formodule="'.$module.'" record_id="'.$recordId.'">'.$mod_strings['LBL_SELECT_RS_TEMPLATE'].'</option>
                        <option label="'.$mod_strings['LBL_EXTERNAL_FILE'].'" value="external_file" formodule="'.$module.'" record_id="'.$recordId.'">'.$mod_strings['LBL_EXTERNAL_FILE'].'</option>';
    }
    else if($module == 'AOS_Quotes' || $module == 'AOS_Contracts'){
        $sAllowedOption='<option label="" value=""></option>
                        <option label="'.$mod_strings['LBL_SELECT_CRM_PDF'].'" value="select_crm_pdf">'.$mod_strings['LBL_SELECT_CRM_PDF'].'</option>';
    }
    $sOuter='<div style="padding: 5px 20px; height: 75px;">
                <div class="col-xs-12">
                    <div class="form-group col-xs-6">
                        <label for="subject" class="control-label">'.$mod_strings['LBL_SENDING_TYPE'].'</label>
                        <select name="sending_type" id="sending_type" title="'.$mod_strings['LBL_SENDING_TYPE'].'" required="">
                            '.$sAllowedOption.'
                        </select>
                        <span class="help-block"></span>
                    </div>
                </div>
                
            </div>'.$selectTemplatesForm;
    echo json_encode($sOuter);
}