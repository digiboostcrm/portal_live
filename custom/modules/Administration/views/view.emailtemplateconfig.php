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
class ViewEmailtemplateconfig extends SugarView {

    function display() {
        global $db;

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
            }
        $objAdministration = new Administration();
        $objAdministration->retrieveSettings('PortalPlugin');
        $newregisteruserEmail = (!empty($objAdministration->settings['PortalPlugin_NewRegisterUserEmail'])) ? $objAdministration->settings['PortalPlugin_NewRegisterUserEmail'] : "0";
        $forgotpasswordEmail = (!empty($objAdministration->settings['PortalPlugin_ForgotPasswordEmail'])) ? $objAdministration->settings['PortalPlugin_ForgotPasswordEmail'] : "0";
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
        $html = '<table id="emailTemplatesId" name="emailTemplatesName" width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
							<tr>
								<th align="left" scope="row" colspan="4">
									<h4>
										Set Email Template For New Registration User in Portal
									</h4>
								</th>
							</tr>

										<tr>
									        <td  scope="row" width="35%">Select Email Template For New Register User : </td>
									        <td  >
										        <slot>
									        		<select tabindex="251" id="newregisteremailid" name="newregisteremail" {$IE_DISABLED}>' . $register_option_template . '</select>
													<input type="button" class="button" onclick="open_email_template_form(this)" value="Create Email Template" {$IE_DISABLED}>
													<input type="button" value="Edit Email Template" class="button" onclick="edit_email_template_form(this)" name="edit_generatepasswordtmpl" id="edit_generatepasswordtmpl" style="{$EDIT_TEMPLATE}">
												</slot>
									        </td>
									        <td ></td>
									        <td  ></td>
										</tr>
										<tr>
									        <td scope="row">Select Forgot Password Email Template : </td>
									        <td>
							        			<slot>
									        		<select tabindex="251" id="forgotpasswordemailid" name="forgotpasswordemail" {$IE_DISABLED}>' . $forgot_option_template . '</select>
													<input type="button" class="button" onclick="open_email_template_form(this)" value="Create Email Template">
													<input type="button" value="Edit Email Template" class="button" onclick="edit_email_template_form(this)" name="edit_lostpasswordtmpl" id="edit_lostpasswordtmpl" style="{$EDIT_TEMPLATE}">
												</slot>
							        		 </td>
									        <td></td>
									        <td></td>
										</tr>
                                                                                <tr>
                                                                                    <td scope="row"><input type="button" value="Save" onclick="saveEmailtemplate(this)"/>
                                                                                    <input type="button" value="Cancel" onclick="cancelEdit(this)" /></td>
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
function saveEmailtemplate(el){
debugger;
var registernewemail = $('#newregisteremailid').val();
var forgotpassworemail = $('#forgotpasswordemailid').val();
if(registernewemail != 0  && forgotpassworemail != 0){
$.ajax({
                                url:'index.php?module=Administration&action=portalHandler&method=saveEmailconfig',
                                type:'POST',
                                data:{'registernewuser':registernewemail,'forgotpasswordemail':forgotpassworemail},
                                success:function(result){
                                    if(result){
                                    alert('Email template Save Successfully.');
                                        window.location='./index.php?module=Administration&action=index';
                                    }
               
                                }
                 });
                 } else {
                    alert('Please Select Templates in both fields');
                 }
}
function cancelEdit(el){
    if(confirm('Are you sure ?')){
        window.location='./index.php?module=Administration&action=index';
    }
}
                    </script>";
        parent::display();
        echo $html;
    }

}
}
