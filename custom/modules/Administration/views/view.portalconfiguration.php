<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

/**
 * The file used for displaying license View for Portal
 * which includes validation of license key and enable/disable plugin.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
class ViewPortalConfiguration extends SugarView {

    function display() {
        global $sugar_version;
        require_once('modules/Administration/Administration.php');
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('PortalPlugin');
        $LastValidation = $administrationObj->settings['PortalPlugin_LastValidation'];
        $ModuleEnabled = !empty($administrationObj->settings['PortalPlugin_ModuleEnabled']) ? $administrationObj->settings['PortalPlugin_ModuleEnabled'] : 0;
        $url_instance = (!empty($administrationObj->settings['PortalPlugin_PortalInstance'])) ? $administrationObj->settings['PortalPlugin_PortalInstance'] : "";
        $url = (!empty($administrationObj->settings['PortalPlugin_PortalInstance_url'])) ? $administrationObj->settings['PortalPlugin_PortalInstance_url'] : "";
        $licenseKey = (!empty($administrationObj->settings['PortalPlugin_LicenseKey'])) ? $administrationObj->settings['PortalPlugin_LicenseKey'] : "";
        //check sugar version and include js file if required
        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="custom/biz/js/jquery/jquery-1.11.0.min.js"></script>';
        }

        $html = "<input type='hidden' name='forntend_framework' id='forntend_framework' value='{$url_instance}'>";
        $html .= '<div id="main"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody><tr><td colspan="100"><h2><div class="moduleTitle">
                <h2>Portal Configuration </h2>
                <div class="clear"></div></div>
                </h2></td></tr>
                <tr><td colspan="100">
	            <div class="add_table" style="margin-bottom:5px">
		        <table id="ConfigureSurvey" class="themeSettings edit view" style="margin-bottom:0px;" border="0" cellpadding="0" cellspacing="0">
			    <tbody>
			    <tr><th align="left" colspan="4" scope="row"><h4>Validate License</h4></th></tr>
			    <tr>
			    <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic"> License Key : </label></td>
            	<td nowrap="nowrap" style="width: 19%;"><input name="licence_key" id="licence_key" size="30" maxlength="255" value="' . $licenseKey . '" title="" accesskey="9" type="text"></td>
            	<td nowrap="nowrap" style="width: 20%;"><input title="Validate" id="Validate" class="button primary" onclick="validateLicence(this);" name="validate" value="Validate" type="button">&nbsp;<input title="Clear" id="clearkey" class="button primary" onclick="clearKey();" name="clear" value="Clear" type="button"></td>
            	<td nowrap="nowrap" style="width: 55%;">&nbsp;</td>
            	</tr></tbody></table></div>';
        $display_enable = "display:none;";
        $display_validate = "display:block;";
        if ($LastValidation == 1) {
            $display_enable = "display:block;";
            $display_validate = "display:none;";
        }
        $html .= '<table class="actionsContainer" style="' . $display_validate . '" border="0" cellpadding="1" cellspacing="1">
		        <tbody><tr><td>
				<input title="Back" accesskey="l" class="button" onclick="redirectToindex();" name="button" value="Back" type="button">
			    </td></tr>
            	</tbody></table>
                </td></tr></tbody></table>';
        //    Enable/Disable Plugin Section
        $html .= '<div class="add_table" id="enableDiv" style="margin-bottom:5px;' . $display_enable . '">
		        <table id="ConfigureSurvey" class="themeSettings edit view" style="margin-bottom:0px;" border="0" cellpadding="0" cellspacing="0">
			    <tbody>
			    <tr><th align="left" colspan="4" scope="row"><h4>Enable/Disable Module</h4></th></tr>
                <tr>
			    <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic"> Enable/Disable : </label></td>
            	<td nowrap="nowrap" style="width: 15%;">
            	<select name="enable" id="enable" onchange="enableurlconfig();">';
        if ($ModuleEnabled) {
            $html .= '<option value="1" selected="">Enable</option>
            	     <option value="0">Disable</option>';
        } else {
            $html .= '<option value="1">Enable</option>
            	     <option value="0" selected="">Disable</option>';
        }
        $html .= '</select>
            	</td>
            	<td nowrap="nowrap" style="width: 20%;">&nbsp;</td>
            	<td nowrap="nowrap" style="width: 55%;">&nbsp;</td>
            	</tr>
                </tbody></table>
	            </div></div>';

        $html .= '<table class="actionsContainerEnableDiv" style="' . $display_enable . '" border="0" cellpadding="1" cellspacing="1">
		        <tbody><tr><td>
				<input title="Save" accesskey="a" class="button primary" onclick="enableWPPortalExtension(this);" name="button" value="Save" type="submit">
				<input title="Cancel" accesskey="l" class="button" onclick="redirectToindex();" name="button" value="Cancel" type="button">
			    </td></tr>
            	</tbody></table>
                </td></tr></tbody></table>';



        $html .= '<div id="urlconfig" style="display:none;"><table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody><tr><td colspan="100"><h2><div class="moduleTitle">
                <h2>Customer Portal URL Configuration </h2>
                <div class="clear"></div></div>
                </h2></td></tr>
                <tr><td colspan="100">
	            <div class="add_table" style="margin-bottom:5px">
		        <table id="ConfigureSurvey" class="themeSettings edit view" style="margin-bottom:0px;" border="0" cellpadding="0" cellspacing="0">
			    <tbody>
			    <tr><th align="left" colspan="3" scope="row"><h4>Enter Wordpress Portal URL Which is used by Sugar/SuiteCRM when contact is created for creating Portal user.</h4></th></tr>
			    <tr><td nowrap="nowrap" style="width: 15%;"><input name="url" id="url" size="30" maxlength="255" value="' . $url . '" title="" accesskey="9" type="text"></td>
            	<td nowrap="nowrap" style="width: 20%;"><input title="Clear" id="clearkey_url" class="button primary" onclick="clearKey_url();" name="clear" value="Clear" type="button"></td>
            	<td nowrap="nowrap" style="width: 55%;">&nbsp;</td>
            	</tr></tbody></table></div></div>';


        $html .= '<script type="text/javascript">
                    $("document").ready(function(){
                    var enable=$("#enable").val();
                    enableurlconfig();
                    $("#error_span").remove();
                    });
                    
                    function clearKey(){
                        $("#licence_key").val("");
                        $("#error_span").html("");
                    }
                    function clearKey_url(){
                    $("#url").val("");
                    $("#error_span").html("");
                    }
                    function redirectToindex(){
                        location.href = "index.php?module=Administration&action=index";
                    }
                   function enableurlconfig(){
                      var enable=$("#enable").val();     
                      var forntend_framework = $("#forntend_framework").val();
                           if(enable == 1 && forntend_framework == "wordPress"){
                           
                                $("#urlconfig").css({display: "block"});
                                $("#urlconfig").appendTo("#enableDiv");
                           }
                           else{
                                 $("#urlconfig").css({display: "none"}); 
                           }
                   }
                   
                   function validateLicence(element){
                    $("#error_span").remove();
                    var key = $("#licence_key");
                        if(key.val().trim() == ""){
                            $("#enableDiv").hide();
                            $("#urlconfig").hide();
                            $("#enable").val("0");
                            $("#clearkey").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please enter valid license key.</span>")
                            key.focus();
                            return false;
                        }else{
                             $.ajax({
                                url:"index.php?module=Administration&action=portalHandler&method=validateLicence",
                                type:"POST",
                                data:{"k": key.val()},
                                beforeSend : function(){
                                    $("#clearkey").after("<img style=\'color:red;padding-left: 10px;vertical-align: middle;\' id=\'survey_loader\' src= "+SUGAR.themes.loading_image+">");
                                    $(element).attr("disabled","disabled");
                                },
                                complete : function(){
                                    $("#survey_loader").remove();
                                    $(element).removeAttr("disabled");
                                },
                                success:function(result){
                                var licObj = JSON.parse(result);
                                var errMsg = licObj.msg;
                                $("#survey_loader").remove();
                                $("#enSelect option[value=0]").prop("selected", true);
                                        if(licObj.suc){
                                            $("#enableDiv").show();
                                            $(".actionsContainerEnableDiv").show();
                                            $(".actionsContainer").hide();
                                            $("#enable").val("'.$ModuleEnabled.'").trigger("change");
                                            if(!$("#enable").val()){
                                            $("#urlconfig").hide();
                                            }
                                            var fbFramwork = licObj.framework;
                                            var framework = fbFramwork.substr(fbFramwork.indexOf(",") + 1);
                                            $("#forntend_framework").val(framework);
                                            
                                            $("#clearkey").after("<span style=\'color:green;padding-left: 10px;\' id=\'error_span\'>License validated successfully.</span>");
                                        }else{
                                            $("#clearkey").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'></span>");
                                            $("#error_span").html(errMsg);
                                            $("#enableDiv").hide();
                                            $("#enable").val("0");
                                            $("#urlconfig").hide();
                                            $(".actionsContainerEnableDiv").hide();
                                            $(".actionsContainer").show();
                                        }
                                    }
                                });
                            }
                        }
                    function enableWPPortalExtension(element){
                            var enabled = parseInt($("#enable").val());
                             $("#error_span").remove();
                            var portal_url = $("#url").val();
                            var forntend_framework = $("#forntend_framework").val();
                            if(enabled) {
                                var re = new RegExp(\'^(http|https)://.*$\');
                                
                                // if wordpress is selected
                                if(portal_url.trim() == "" && forntend_framework == "wordPress"){
                                $("#clearkey_url").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please enter your Portal URL.</span>");
                                    $("#url").focus();
                                return false;
                                }
                                else if (re.test(portal_url.trim()) == false && forntend_framework == "wordPress") {
                                    $("#clearkey_url").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please enter valid Portal URL.</span>");
                                    $("#url").focus();
                                    return false;
                                }
                            }
                                
                             $.ajax({
                                url:"index.php?module=Administration&action=portalHandler&method=enableDisableExtension",
                               data :{"enabled" : enabled,"url": portal_url,"forntend_framework":forntend_framework },
                                type:"POST",
                                 beforeSend : function(){
                                    $("#clearkey_url").after("<img style=\'color:red;padding-left: 10px;vertical-align: middle;\' id=\'survey_loader\' src= "+SUGAR.themes.loading_image+">");
                                    $(element).attr("disabled","disabled");
                                },
                                complete : function(){
                                    $("#survey_loader").remove();
                                    $(element).removeAttr("disabled");
                                },
                                success:function(result){
                                var obj = JSON.parse(result);
                                    $("#survey_loader").remove();
                                    $("#enSelect option[value=0]").prop("selected", true);
                                    var enableV = parseInt($("#enable").val());
                                    if(enableV){
                                    alert("Module enabled successfully.");
                                    }else{
                                        alert("Module disabled successfully.");
                                    }
                                    if(obj.status && enabled == "1"){
                                    alert("UserGroup has been changed to Default.");
                                    }
                                    if(obj.allow == "Sugar7"){
                                    javascript:parent.SUGAR.App.router.navigate("bc_wp_user_group", {trigger: true});
                                    }else{
                                    location.href = "index.php?module=bc_user_group&action=index";
                                    }
                                  }
                                  
                            });
                            
                        }
                  </script>';

        parent::display();
        echo $html;
    }

}
