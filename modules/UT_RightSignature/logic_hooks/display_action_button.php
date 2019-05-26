<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
class UT_RS_DisplayActionButton {
    
    public function UT_RS_DisplayActionButtonProcess() {
        global $app_strings,$currentModule,$sugar_config,$current_user,$theme,$current_language;
        
        require_once('modules/ACLActions/ACLAction.php');
        if(ACLAction::userHasAccess($current_user->id, 'UT_RightSignature', 'edit','module', true, true))
        {
            $utRsModStrings = return_module_language($current_language, 'UT_RightSignature');
            $action = $moduleName = $RecordId = $enableActionMenu = '';
            if(array_key_exists("enable_action_menu",$sugar_config) && $sugar_config['enable_action_menu'])
                $enableActionMenu = json_encode("dropdown");
            else if(array_key_exists("enable_action_menu",$sugar_config) && !$sugar_config['enable_action_menu'])
                $enableActionMenu = json_encode("button");
            else if(!array_key_exists("enable_action_menu",$sugar_config))
                $enableActionMenu = json_encode("dropdown");
            
            $supportedActions = array('DetailView');
            $supportedModules = array('AOS_Quotes','AOS_Contracts','Accounts','Contacts','Leads','Prospects');
            if(array_key_exists('record', $_REQUEST) && !empty($_REQUEST['record'])) $RecordId = $_REQUEST['record'];

            if(!empty($RecordId)) {
                if(array_key_exists('action', $_REQUEST) && !empty($_REQUEST['action'])) $action = $_REQUEST['action'];
                if(array_key_exists('module', $_REQUEST) && !empty($_REQUEST['module'])) $moduleName = $_REQUEST['module'];
                
                $sModuleName = $_REQUEST['module'];
                $sRecordId = $RecordId;
                
                if(in_array($action, $supportedActions) && in_array($moduleName,$supportedModules)) {
                    $base_mod_string = return_module_language("", "UT_RightSignature");
                    $moduleName = json_encode($moduleName);
                    $button_title = json_encode($base_mod_string['LBL_TEMPLATE_DETAIL_BUTTON']);
                    $RecordId = json_encode($RecordId);
                    $modalTitle = json_encode($base_mod_string['LBL_SEND_DOCUMENT_TITLE']);
                    $userTheme = json_encode($theme);
                    $LBLClose = $app_strings['LBL_CLOSE_BUTTON_TITLE'];
                    $error_message='';
                    $isErrorFlag='no';
                    if($sModuleName == 'Accounts' || $sModuleName == 'Contacts' || $sModuleName == 'Leads' || $sModuleName == 'Prospects'){
                        $oBean = BeanFactory::getBean($sModuleName,$sRecordId);
                        if(empty($oBean->email1)){
                            $error_message = $utRsModStrings['LBL_ERRMSG_1'];
                            $isErrorFlag='yes';
                        }
                    }
                    else if($sModuleName == 'AOS_Quotes'){
                        $oBean = BeanFactory::getBean($sModuleName,$sRecordId);
                        if(!empty($oBean->billing_contact_id)){
                            $oSubBean = BeanFactory::getBean("Contacts",$oBean->billing_contact_id);
                            if(empty($oSubBean->email1)){
                                $error_message = sprintf($utRsModStrings['LBL_ERRMSG_JS_1'], $oSubBean->full_name);
                                $isErrorFlag='yes';
                            }
                        }
                        else {
                            $error_message=$utRsModStrings['LBL_ERRMSG_3'];
                            $isErrorFlag='yes';
                        }
                    }
                    else if($sModuleName == 'AOS_Contracts'){
                        $oBean = BeanFactory::getBean($sModuleName,$sRecordId);
                        if(!empty($oBean->contact_id)){
                            $oSubBean = BeanFactory::getBean("Contacts",$oBean->contact_id);
                            if(empty($oSubBean->email1)){
                                $error_message = sprintf($utRsModStrings['LBL_ERRMSG_JS_1'], $oSubBean->full_name);
                                $isErrorFlag='yes';
                            }
                        }
                        else{
                            $error_message=$utRsModStrings['LBL_ERRMSG_3'];
                            $isErrorFlag='yes';
                        }
                    }
                    $button_code = <<<EOQ
                            <script type="text/javascript">
                                var moduleName = $moduleName;
                                var button_title = $button_title;
                                var rsTemplateSelection = '';
                                var RecordId = $RecordId;
                                var modalTitle = $modalTitle;
                                var userTheme = $userTheme;
                                var enableActionMenu = $enableActionMenu;
                                var isErrorFlag = '$isErrorFlag';
                                $(document).ready(function(){
                                    if(userTheme == "SuiteR" || userTheme == "Suite7" || userTheme == "Sugar5") {
                                        if(enableActionMenu == "dropdown")
                                            $('#detail_header_action_menu > li > ul').append("<li><a href='javascript:void(0)' onclick=\"processRSTemplates('"+moduleName+"','"+RecordId+"');\" >"+button_title+"</a></li>");
                                        else if(enableActionMenu == "button") {
                                            var r= $("<li><input type='button' value='"+button_title+"' onclick=\"processRSTemplates('"+moduleName+"','"+RecordId+"');\"/></li>");
                                             $('.actionsContainer > .action_buttons li:last-child').after(r);
                                        }
                                    }
                                    else {  
                                            var rs_button= $("<li><input type='button' value='"+button_title+"' onclick=\"processRSTemplates('"+moduleName+"','"+RecordId+"');\"/></li>");
                                            //tab-actions
                                            var rs_option_container_1 = $("#tab-actions > .dropdown-menu input:last").length;
                                            if(rs_option_container_1 >= 1)
                                                $("#tab-actions > .dropdown-menu li:last-child").after(rs_button);
                                                
                                            var rs_option_container_2 = $('.actionsContainer .buttons').length;
                                            if(rs_option_container_2 >= 1) {
                                                var rs_button= $("<input type='button' value='"+button_title+"' onclick=\"processRSTemplates('"+moduleName+"','"+RecordId+"');\"/>");
                                                $('.actionsContainer .buttons input:last').after(rs_button);
                                            }
                                    }
                                    rsTemplateSelection = "<div id='rs_template_modal_detailview' class='modal fade'>";
                                        rsTemplateSelection += "<div class='modal-dialog'>";
                                            rsTemplateSelection += "<div class='modal-content'>";
                                                rsTemplateSelection += "<div class='modal-header'>";
                                                    rsTemplateSelection += "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                                                    rsTemplateSelection += "<h4 class='modal-title'><img src='modules/UT_RightSignature/images/rightsignature_32x32.png'>&nbsp;"+modalTitle+"</h4>";
                                                rsTemplateSelection += "</div>";

                                                rsTemplateSelection += "<div class='modal-body'>";

                                                rsTemplateSelection += "</div>";

                                                rsTemplateSelection += "<div class='modal-footer'>&nbsp;";
                                                rsTemplateSelection += "<span style='float: left;'><img src='modules/UT_RightSignature/images/urdhvatech-horizontal-small.png' alt='Urdhva tech logo' style='width: 100px;'></span>";
                                                rsTemplateSelection += "<button type='button' class='btn btn-primary' data-dismiss='modal'>$LBLClose</button>";
                                                rsTemplateSelection += "</div>";
                                            rsTemplateSelection += "</div>";
                                        rsTemplateSelection += "</div>";
                                    rsTemplateSelection += "</div>";
                                    $("body").append(rsTemplateSelection);
                                });
                                /***
                                * Fetch RightSignature template
                                ***/
                                function processRSTemplates(moduleName,RecordId) {
                                    var callback = {
                                        success:function(o){
                                            var resp = YAHOO.lang.JSON.parse(o.responseText);
                                            $('#rs_template_modal_detailview .modal-body').html(resp);
                                            $('#rs_template_modal_detailview').modal('show',{backdrop: 'static'});
                                            SUGAR.ajaxUI.hideLoadingPanel();
                                        }
                                    }
                                    if(isErrorFlag == 'yes'){
                                        alert("$error_message");
                                    }
                                    else{
                                        SUGAR.ajaxUI.showLoadingPanel();
                                        YAHOO.util.Connect.asyncRequest('POST', 'index.php?module=UT_RightSignature&action=getRSTemplateList&to_pdf=1&moduleName='+moduleName+'&recordId='+RecordId+'&modalView=DetailView', callback, null);
                                    }
                                    
                                    return false;
                                }
                                /***
                                * Process signature part
                                ***/
                                function processSignature(moduleName,RecordId,template_id) {
                                    var callback = {
                                        success:function(o){
                                            var resp = YAHOO.lang.JSON.parse(o.responseText);
                                            $('#rs_template_modal_detailview .modal-body').html(resp);
                                            $('#rs_template_modal_detailview').modal('show',{backdrop: 'static'});
                                        }
                                    }
                                    var loading_gif = '<img src="modules/UT_RightSignature/images/loading.gif" />';
                                    $('#rs_template_modal_detailview .modal-body').html(loading_gif);
                                    YAHOO.util.Connect.asyncRequest('POST', 'index.php?module=UT_RightSignature&action=sendSignature&to_pdf=1&moduleName='+moduleName+'&recordId='+RecordId+'&template_id='+template_id+'&modalView=DetailView', callback, null);
                                    return false;
                                }
                                $(document).ready(function () {
                                    $(document).delegate("#sending_type", "change", function() {
                                        $("#DivTemplatList").hide();
                                        sending_type = $(this).val();
                                        moduleName = $('option:selected', this).attr('formodule');
                                        RecordId = $('option:selected', this).attr('record_id');
                                        
                                        if(sending_type == 'select_crm_pdf'){
                                            $("#DivTemplatList").show();
                                        }
                                        else if(sending_type == 'select_template'){
                                            SUGAR.ajaxUI.showLoadingPanel();
                                            $('#rs_template_modal_detailview').modal('hide');
                                            location.href="index.php?module=UT_RightSignature&action=SendDocumentRS&prePopModule="+moduleName+"&prePopID="+RecordId+"&prePopulateAction=select_template&return_module="+moduleName+"&return_action=DetailView&return_id="+RecordId;
                                        }
                                        else if(sending_type == 'external_file'){
                                            SUGAR.ajaxUI.showLoadingPanel();
                                            $('#rs_template_modal_detailview').modal('hide');
                                            location.href="index.php?module=UT_RightSignature&action=SendDocumentRS&prePopModule="+moduleName+"&prePopID="+RecordId+"&prePopulateAction=external_file&return_module="+moduleName+"&return_action=DetailView&return_id="+RecordId;
                                        }
                                        
                                    });
                                });
                            </script>
EOQ;
                    echo $button_code;
                }
            }
        }
        
    }
}