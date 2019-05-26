<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/**
 * The file used to set Portal url configuration need to provide url to connect with Customer Portal 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
class ViewGeneralconfiguration extends SugarView {

    function display() {
        global $sugar_version, $db,$app_list_strings;
        require_once('modules/Administration/Administration.php');
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('PortalPlugin');
        //check sugar version and include js file if required

        require_once 'custom/biz/classes/Portalutils.php';
        $checkLicenceSubscription = Portalutils::validateLicenceSubscription();
        //check license status
        if (!$checkLicenceSubscription['success']) {
            parent::display();
            if (!empty($checkLicenceSubscription['message'])) { //license not validated
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkLicenceSubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkLicenceSubscription['message'])) { //plugin disabled
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkLicenceSubscription['message'] . '</div>';
                exit;
            }
            //plugin enabled
            
            $chatEnable = (!empty($administrationObj->settings['PortalPlugin_ChatEnable'])) ? $administrationObj->settings['PortalPlugin_ChatEnable'] : "0";
            $gmailUsername = (!empty($administrationObj->settings['PortalPlugin_GmailUsername'])) ? $administrationObj->settings['PortalPlugin_GmailUsername'] : "";
            $gmailMailbox = (!empty($administrationObj->settings['PortalPlugin_GmailMailbox'])) ? $administrationObj->settings['PortalPlugin_GmailMailbox'] : "";
            $gmailPassword = (!empty($administrationObj->settings['PortalPlugin_GmailPassword'])) ? $administrationObj->settings['PortalPlugin_GmailPassword'] : "";
            $twkkey = (!empty($administrationObj->settings['PortalPlugin_TalkApikey'])) ? $administrationObj->settings['PortalPlugin_TalkApikey'] : "";

            $inboundEmail = "SELECT DISTINCT(email_user),name FROM inbound_email WHERE deleted = '0'";
            $resultOfInboundEmail = $db->query($inboundEmail);

            
            //Chat Configuration start
            $html = "<input type='hidden' name='pre_username' id='pre_username' value='{$gmailUsername}'><input type='hidden' name='pre_mailbox' id='pre_mailbox' value='{$gmailMailbox}'><input type='hidden' name='pre_password' id='pre_password' value='{$gmailPassword}'><input type='hidden' name='pre_twkkey' id='pre_twkkey' value='{$twkkey}'><br><br><table border='0' cellpadding='0' cellspacing='0' width='100%' class='edit view'>
                <tbody><tr><td colspan='100'><h2><div class='moduleTitle'>
                <h4>Portal Chat Configuration </h2>
                <div class='clear'></div></div>
                </h4></td></tr>
                <tr><td colspan='100'>
	            <div class='add_table' style='margin-bottom:5px'>
		        <table id='Configurechat' class='themeSettings edit view' style='margin-bottom:0px; width:100%;' border='0' cellpadding='0' cellspacing='0'>
			    <tbody>
			    <tr><td style='width:10%'>Chat Enable:</td>";
            if ($chatEnable) {
                $html .= "<td><input type='checkbox' id='chat_enable' name='chat_enable' value='{$chatEnable}' checked></td>";
            } else {
                $html .= "<td><input type='checkbox' id='chat_enable' name='chat_enable' value='{$chatEnable}'></td>";
            }
            $html .= "</tr>
			    <tr><td style='width:10%'>Username:</td><td><select name='gmail_username' id='gmail_username' onchange='gmailmailbox();'><option value=''>Select MailAccount</option>";
            while ($row = $db->fetchByAssoc($resultOfInboundEmail)) {
                if ($gmailUsername == $row['email_user']) {
                    $html .= "<option value='{$row['email_user']}' selected>{$row['name']}</option>";
                } else {
                    $html .= "<option value='{$row['email_user']}'>{$row['name']}</option>";
                }
            }
            $html .= "</select>&nbsp;&nbsp;";
            $html .= "<select name='gmail_mailbox' id='gmail_mailbox'><option value=''>Select Mailbox</option>";
            $emailbox = "SELECT mailbox FROM inbound_email WHERE email_user='$gmailUsername' AND deleted = '0'";
            $resultOfmailbox = $db->query($emailbox);
            if (!empty($gmailMailbox) || !empty($gmailUsername)) {
                while ($row = $db->fetchByAssoc($resultOfmailbox)) {
                    $mailbox = explode(',', $row['mailbox']);
                    foreach ($mailbox as $value) {
                        $gmailmailbox[$value] = $value;
                    }
                }
                foreach ($gmailmailbox as $key) {
                    if ($gmailMailbox == $key) {
                        $html .= "<option value='{$key}' selected>{$key}</option>";
                    } else {
                        $html .= "<option value='{$key}'>{$key}</option>";
                    }
                }
                
            }
            $html .= "</select></td>";
            $html .= "</tr>
			    <tr><td style='width:10%'>Password:</td><td><input type='password' id='gmail_password' name='gmail_password' value='{$gmailPassword}'></td></tr>
                            <tr><td style='width:10%'>TawkApiKey:</td><td><input type='text' name='twkkey' id='twkkey' value='{$twkkey}'></td></tr>
                            
                </table>
                </div></td>
            	</tr></tbody></table></div>";

            $html .= "<script type='text/javascript'>
                   function redirectToindex(){
                        location.href = 'index.php?module=Administration&action=index';
                    }
                    function gmailmailbox(){
                       var gmailUsername = $('#gmail_username').val();
                       
                        $.ajax({
                                url:'index.php?module=Administration&action=portalHandler&method=gmailMailbox',
                                type:'POST',
                                data:{'gmailUsername':gmailUsername},
                                success:function(result){
                               $('#gmail_mailbox').html(result);
               
                 }
                          });
                    }
                    function savechatconfig(){
                            $('.validation-message').remove();
                           var chatEnable = $('#chat_enable').is(':checked');
                            var gmailUsername = $('#gmail_username').val();
                            var gmailMailbox = $('#gmail_mailbox').val();
                            var gmailPassword = $('#gmail_password').val();
                            var twkkey = $('#twkkey').val();
                            var pre_username = $('#pre_username').val();
                            var pre_mailbox = $('#pre_mailbox').val();
                            var pre_password = $('#pre_password').val();
                            var pre_twkkey = $('#pre_twkkey').val();
                            flag = true ;
                            if(chatEnable){
                               if(gmailUsername == ''){
                                     $('#gmail_username').closest('td').append('<div class=\'required validation-message\' id=\'username\'>MailAccount is Required.</div>');
                                     flag = false;
                               }
                               if(gmailUsername != '' && gmailMailbox == ''){
                                    $('#gmail_mailbox').closest('td').append('<div class=\'required validation-message\' id=\'mailbox\'>Mailbox is Required.</div>');
                                    flag = false;
                               }
                               if(gmailPassword == ''){
                                    $('#gmail_password').closest('td').append('<div class=\'required validation-message\' id=\'password\'>Password is required.</div>');
                                    flag = false;
                               }
                               if(twkkey == ''){
                                    $('#twkkey').closest('td').append('<div class=\'required validation-message\' id=\'talkkey\'>TalkApikey is required.</div>');
                                    flag = false;
                               }
                            }
                            else if(pre_username != '' && pre_mailbox != '' && pre_twkkey != ''){
                                   if(confirm('Chat Configuration must be required for sync. Are you sure want to disable chat?')){
                                       flag = true ;
                                   }else{
                                          flag = false;
                                   }
                                  
                            }
var registernewemail = $('#newregisteremailid').val();
var forgotpassworemail = $('#forgotpasswordemailid').val();
if(registernewemail == 0  || forgotpassworemail == 0){
                        if(registernewemail == 0 && forgotpassworemail == 0){
                            $('#newregisteremailid').closest('td').append('<div class=\'required validation-message\' id=\'newregisteremailid\'>Register Email Template is required.</div>');
                            $('#forgotpasswordemailid').closest('td').append('<div class=\'required validation-message\' id=\'forgotpasswordemailid\'>Forgot Password Email Template is required.</div>');
                        }else if(registernewemail == 0){
                            $('#newregisteremailid').closest('td').append('<div class=\'required validation-message\' id=\'newregisteremailid\'>Register Email Template is required.</div>');
                        }else{
                            $('#forgotpasswordemailid').closest('td').append('<div class=\'required validation-message\' id=\'forgotpasswordemailid\'>Forgot Password Email Template is required.</div>');
                        }
                    flag = false;
                 }
                 
                 var case_module_check = $('#enable_case_checkbox').is(':checked');
                 var invoice_module_check = $('#enable_invoice_checkbox').is(':checked');
                 var quotes_module_check = $('#enable_quotes_checkbox').is(':checked');
                            if(flag){
                             $.ajax({
                                url:'index.php?module=Administration&action=portalHandler&method=generalConfiguration',
                                type:'POST',
                                data:{'chatEnable':chatEnable,'gmailUsername':gmailUsername,'gmailMailbox':gmailMailbox,'gmailPassword':gmailPassword,'twkkey':twkkey,'registernewuser':registernewemail,'forgotpasswordemail':forgotpassworemail,'case_module_check':case_module_check,'invoice_module_check':invoice_module_check,'quotes_module_check':quotes_module_check},
                                success:function(result){
                                      location.href = 'index.php?module=Administration&action=index'; 
                                }
                                });
                            }
                            else{
                              return false;
                            }
                            }
                       

                  </script>";
                             //Chat Configuration End
                            //Email Template Configuration Start 
                            $newregisteruserEmail = (!empty($administrationObj->settings['PortalPlugin_NewRegisterUserEmail'])) ? $administrationObj->settings['PortalPlugin_NewRegisterUserEmail'] : "0";
        $forgotpasswordEmail = (!empty($administrationObj->settings['PortalPlugin_ForgotPasswordEmail'])) ? $administrationObj->settings['PortalPlugin_ForgotPasswordEmail'] : "0";
        $selet_email_template = $db->query("SELECT id,name FROM email_templates WHERE deleted = 0");
        $register_option_template = "<option value='0'>Select Email Template</option>";
        $forgot_option_template = "<option value='0'>Select Email Template</option>";
        while ($row_template = $db->fetchByAssoc($selet_email_template)) {
            if($newregisteruserEmail == $row_template['id']){
                $register_option_template .= "<option value='{$row_template['id']}' selected>{$row_template['name']}</option>";
            }else{
                $register_option_template .= "<option value='{$row_template['id']}'>{$row_template['name']}</option>";
        }
            if($forgotpasswordEmail == $row_template['id']){
                $forgot_option_template .= "<option value='{$row_template['id']}' selected>{$row_template['name']}</option>";
            }else{
                $forgot_option_template .= "<option value='{$row_template['id']}'>{$row_template['name']}</option>";
            }
        }
                            
                            $html .= '<table id="emailTemplatesId" name="emailTemplatesName" width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
							<tr>
								<th align="left" scope="row" colspan="4">
									<h2><div class="moduleTitle">
                <h4>Set Email Template For New Registration User in Portal</h4>
                    <div class="clear"></div></div>
                </h2>
								</th>
							</tr>

										<tr>
									        <td  scope="row" width="25%">Select Email Template For New Register User : </td>
									        <td  >
										        <slot>
									        		<select tabindex="251" id="newregisteremailid" name="newregisteremail" {$IE_DISABLED}>' . $register_option_template . '</select>
													<input type="button" class="button" onclick="open_email_template_form(this)" value="Create" {$IE_DISABLED}>
													<input type="button" value="Edit" class="button" onclick="edit_email_template_form(this)" name="edit_generatepasswordtmpl" id="edit_generatepasswordtmpl" style="{$EDIT_TEMPLATE}">
												</slot>
									        </td>
									        <td ></td>
									        <td  ></td>
										</tr>
										<tr>
									        <td scope="row" width="25%">Select Forgot Password Email Template : </td>
									        <td>
							        			<slot>
									        		<select tabindex="251" id="forgotpasswordemailid" name="forgotpasswordemail" {$IE_DISABLED}>' . $forgot_option_template . '</select>
													<input type="button" class="button" onclick="open_email_template_form(this)" value="Create">
													<input type="button" value="Edit" class="button" onclick="edit_email_template_form(this)" name="edit_lostpasswordtmpl" id="edit_lostpasswordtmpl" style="{$EDIT_TEMPLATE}">
												</slot>
							        		 </td>
									        <td></td>
									        <td></td>
										</tr>
                                                                                
									</table>';
        $html .= "<script type='text/javascript'>
                   function open_email_template_form(el){
	URL='index.php?module=EmailTemplates&action=EditView&inboundEmail=true&show_js=1';
	windowName = 'email_template';
	windowFeatures = 'width=800' + ',height=600' 	+ ',resizable=1,scrollbars=1';

	win = window.open(URL, windowName, windowFeatures);
	if(window.focus)
	{
		// put the focus on the popup if the browser supports the focus() method
		win.focus();
	}
                    }
                    function edit_email_template_form(el) {
	var id = $(el).parents('tr').find('select').val();
	URL='index.php?module=EmailTemplates&action=EditView&inboundEmail=true&show_js=1';
	if (id) {
            URL+='&record='+id;
            windowName = 'email_template';
            windowFeatures = 'width=800' + ',height=600' 	+ ',resizable=1,scrollbars=1';

            win = window.open(URL, windowName, windowFeatures);
            if(window.focus)
            {
		// put the focus on the popup if the browser supports the focus() method
		win.focus();
            }
	} else{
            alert('Please Select Email template');
        }
	
}
function cancelEdit(el){
    if(confirm('Are you sure ?')){
        window.location='./index.php?module=Administration&action=index';
    }
}
                    </script>";
        
        //Email Template Configuration End
        //Chart Configuration
        $chart_array = json_decode(html_entity_decode($administrationObj->settings['PortalPlugin_PortalChart']));
        foreach($chart_array as $module => $enable){
            if($module == 'case' && $enable == "true"){
                $case_checked = "checked";
            }
            if($module == 'invoice' && $enable == "true"){
                $invoice_checked = "checked";
            }
            if($module == 'quotes' && $enable == "true"){
                $quotes_checked = "checked";
            }
        }
        $html .= '<table id="emailTemplatesId" name="emailTemplatesName" width="50%" border="0" cellspacing="1" cellpadding="0" class="edit view">
							<tr>
								<th align="left" scope="row" colspan="4">
									<h2><div class="moduleTitle">
                <h4>Portal Chart Configuration</h4>
                    <div class="clear"></div></div>
                </h2>
								</th>
							</tr>';
        $html .= '<tr>
                    <td style="width: 13%;"> Case Status Chart : </td><td><input type="checkbox" name="enable_case_checkbox" id = "enable_case_checkbox" '.$case_checked.'></td>
                    </tr>
                    <tr>
                    <td style="width: 13%;">Invoice Status Chart : </td><td><input type="checkbox" name="enable_invoice_checkbox" id = "enable_invoice_checkbox" '.$invoice_checked.'></td>
                    </tr>  
                    <tr>
                    <td style="width: 13%;">Quotes Stage Chart : </td><td><input type="checkbox" name="enable_quotes_checkbox" id = "enable_quotes_checkbox" '.$quotes_checked.'></td>
                    </tr>';
                       
        $html .= '</table>';
        
        $html .= '<br><div style="padding-top: 2px;>
                                                                                    <td scope="row"><input type="button" value="Save" onclick="savechatconfig()"/>
                                                                                    <input type="button" value="Cancel" onclick="cancelEdit(this)" /></td>
                                                                                </div><br>';
        

            
        }
        
        echo $html;
        
        parent::display();
    }

}
