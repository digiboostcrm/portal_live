{literal}
<style type="text/css">
.btn-success:focus {
    background-color: #bbe6a5;
    color: #f5f5f5;
    border: 1px solid #bbe6a5;
}
.btn-success:hover {
    background-color: #449d44;
    color: #ffffff;
    border: 1px solid #398439;
}
</style>
{/literal}
<div id="utBody" style="background-color: rgb(215, 215, 215); padding:30px 0px;">
<form name="UrdhvaTechRightSignature" method="post" role="form" enctype="multipart/form-data" onSubmit="showAjaxLoading();">
    <input type="hidden" name="module" value="UT_RightSignature">
    <input type="hidden" name="action" value="SendDocumentRS">
    <div class="container" style="width:970px;">
        {if $MESSAGE}
            <div role="alert" class="alert alert-success offset1 span9" style="text-align:center;">
                <strong style="font-size:13px;">{$MESSAGE}</strong>
            </div>
        {/if}
        {if $ERRORMESSAGE}
            <div role="alert" class="alert alert-danger offset1 span9" style="text-align:center;">
                <strong style="font-size:13px;">{$ERRORMESSAGE}</strong>
            </div>
        {/if}
        <div class="divider medium"></div>
        <div id="login-overlay" class="">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><img src="modules/UT_RightSignature/images/rightsignature_32x32.png" /> {$mod.LBL_SEND_DOCUMENT_TITLE}</a>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-left:0px; margin-right:0px;">
                        <div class="col-md-12">
                            <div class="well">
                                    
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <p style="font-weight:bold;">{$mod.LBL_CHOOSE_DOCUMENT}</p>
                                            <p>{$mod.LBL_CHOOSE_DOCUMENT_DESCRIPTION}</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="col-xs-12">
                                        
                                            <div class="col-xs-12">
                                                <div class="form-group col-xs-4">
                                                    <label for="subject" class="control-label">{$mod.LBL_SENDING_TYPE}</label>
                                                    <select name="sending_type" id="sending_type" title="{$mod.LBL_SENDING_TYPE}" required="">
                                                        <option label="" value=""></option>
                                                        <option label="{$mod.LBL_SELECT_RS_TEMPLATE}" value="select_template">{$mod.LBL_SELECT_RS_TEMPLATE}</option>
                                                        <option label="{$mod.LBL_EXTERNAL_FILE}" value="external_file">{$mod.LBL_EXTERNAL_FILE}</option>
                                                    </select>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                            <div id='browsefiles_block' style='display:none;'>
                                                <div class="col-xs-12">
                                                    <div class="form-group col-xs-4">
                                                        <label for="uploadfiles">{$mod.LBL_UPLOAD_FILE}</label>
                                                        <input type="file" id="uploadfiles" name="uploadfiles" style="width:100%">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id='rs_template_block_1' style='display:none;'>
                                                    <div class="col-xs-12">
                                                        <div class="form-group col-xs-4">
                                                            <label for="subject" class="control-label">{$mod.LBL_SELECT_RS_TEMPLATE}</label>
                                                            <select name="rs_template_id" id="rs_template_id" title="{$mod.LBL_SELECT_RS_TEMPLATE}" required="">
                                                                {$rs_template_options}
                                                            </select>
                                                            <span class="help-block"></span>
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div id="appendMergeFieldsRow">
                                    </div>
                                    
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <p style="font-weight:bold;">{$mod.LBL_PEOPLE_INVOLVED}</p>
                                            <p>{$mod.LBL_PEOPLE_INVOLVED_DESCRIPTION}</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                    
                                    
                                        <div id="appendRowsDiv">
                                        </div>
                                        <div class="col-xs-12" id="signer_cc_btn_row">
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <img src="modules/UT_RightSignature/images/rightsignature_32x32.png" width="16"> {$mod.LBL_ADD_SIGNER_BUTTON} <span class="caret"></span>
                                                  </button>
                                                  <ul class="dropdown-menu" style="background:#5d5670;">
                                                    <li><a href="javascript:void(0);" id="btnaddsigner" style="color: #fff;background-color: #5d5670;text-decoration: none;padding: 5px 15px 5px 15px !important;
    display: block;font-size: 14px;border-bottom: 1px solid #757083;text-transform: none;letter-spacing: 0;margin: 0 !important;"> {$mod.LBL_ADD_SIGNER_BUTTON}</a></li>
                                                    <li><a href="javascript:void(0);" id="signer_from_crm" style="color: #fff;background-color: #5d5670;text-decoration: none;padding: 5px 15px 5px 15px !important;
    display: block;font-size: 14px;border-bottom: 1px solid #757083;text-transform: none;letter-spacing: 0;margin: 0 !important;">{$mod.LBL_ADD_SIGNER_FROM_CRM}</a></li>
                                                  </ul>
                                                </div>
                                                <div class="btn-group">
                                                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    {$mod.LBL_ADD_CC_BUTTON} <span class="caret"></span>
                                                  </button>
                                                  <ul class="dropdown-menu" style="background:#5d5670;">
                                                    <li><a href="javascript:void(0);" id="btnaddcc" style="color: #fff;background-color: #5d5670;text-decoration: none;padding: 5px 15px 5px 15px !important;
    display: block;font-size: 14px;border-bottom: 1px solid #757083;text-transform: none;letter-spacing: 0;margin: 0 !important;">{$mod.LBL_ADD_CC_BUTTON}</a></li>
                                                    <li><a href="javascript:void(0);" id='cc_from_crm' style="color: #fff;background-color: #5d5670;text-decoration: none;padding: 5px 15px 5px 15px !important;
    display: block;font-size: 14px;border-bottom: 1px solid #757083;text-transform: none;letter-spacing: 0;margin: 0 !important;">{$mod.LBL_ADD_CC_FROM_CRM}</a></li>
                                                  </ul>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <p style="font-weight:bold;">{$mod.LBL_DESCRIPTION}</p>
                                            <p>{$mod.LBL_DESCRIPTION_MORE}</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <div class="col-xs-12">
                                                <div class="col-xs-12">
                                                    <div class="form-group col-xs-6">
                                                        <label for="subject" class="control-label">{$mod.LBL_SUBJECT}</label>
                                                        <input type="text" class="form-control" id="subject" name="subject" value="" required="" title="{$mod.LBL_SUBJECT}" placeholder="{$mod.LBL_SUBJECT}">
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="form-group col-xs-6">
                                                        <label for="message" class="control-label">{$mod.LBL_MESSAGE}</label>
                                                        <textarea id="message" class="form-control" id="message" name="message"  rows="4" cols="20" title="{$mod.LBL_MESSAGE}" placeholder="{$mod.LBL_MESSAGE}"></textarea>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <div class="form-group">
                                            &nbsp;&nbsp;&nbsp;
                                            </div>
                                        </div>
                                        <div class="col-xs-9">
                                            <button type="submit" name="submit" class="btn btn-success">{$mod.LBL_SEND_DOCUMENT_BUTTON}</button>
                                            <a href="{$sCancelRedirectURL}">{$mod.LBL_CANCEL_BUTTON}</a>
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding:10px;">&nbsp;
                    <span style="float: left;"><a href="http://urdhva-tech.com" target="_blank" title="Urdhva Tech"><img src="modules/UT_RightSignature/images/urdhvatech-horizontal-small.png" alt="Urdhva tech logo" style="width: 130px;"></a></span>
                </div>
            </div>
        </div>
        <div class="divider large"></div>
    </div>
</form>
</div>

{literal}
<script>
var prePopFromDetailViewModule = '{/literal}{$prePopFromDetailViewModule}{literal}';
var prePopFromDetailViewId = '{/literal}{$prePopFromDetailViewId}{literal}';
var prePopFromDetailViewName = '{/literal}{$prePopFromDetailViewName}{literal}';
var prePopFromDetailViewEmail = '{/literal}{$prePopFromDetailViewEmail}{literal}';
var prePopulateAction = '{/literal}{$prePopulateAction}{literal}';
</script>
{/literal}
<script src="modules/UT_RightSignature/js/jquery.blockUI.js"></script>
<script src="modules/UT_RightSignature/js/rightsignature.js"></script>

<div class="col-xs-12" id="closerHidden" style="display:none;">
    <div class="form-group">
       <div class="col-xs-12">
          <div class="form-inline">
                  <div class="form-group ">
                     <div class="col-xs-3">
                        <img id="img_signer_cc---" src="modules/UT_RightSignature/images/rightsignature_32x32.png" width="16">
                     </div>
                  </div>
                  <div class="form-group">
                     <div class="col-xs-3">
                      <input type="text" id="receipient_name___" name="receipient_name___" class="form-control input-sm" placeholder="{$mod.LBL_RECIPIENT_NAME}">
                      <input type="hidden" id="must_sign|||" name="must_sign|||" class="form-control input-sm" value="">
                      <input type="hidden" id="sugar_module___" name="sugar_module___" value="">
                      <input type="hidden" id="sugar_module_id___" name="sugar_module_id___" value="">
                     </div>
                  </div>
                  <div class="form-group">
                    <div class="col-xs-3">
                      <input type="text" id="receipient_email___" name="receipient_email___" class="form-control input-sm" placeholder="{$mod.LBL_RECIPIENT_EMAIL}">
                    </div>
                  </div>
                  <div class="form-group ">
                     <div class="col-xs-3">
                        <span style="cursor:pointer; padding-left: 15px;" class="removeRow" rowIndex="" ><img src="themes/SuiteR/images/delete_inline.gif"></span>
                     </div>
                  </div>
          </div>
          <p class="help-block"></p>
       </div>
     </div>
</div>

<div id='rs_signerfromcrm_modal' class='modal fade'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'><img src='modules/UT_RightSignature/images/rightsignature_32x32.png'>&nbsp;Select a signer from CRM</h4>
            </div>
            <div class='modal-body'>
                <form id="form_signerfromcrm" name="form_signerfromcrm" method="post">
                    <input type="hidden" id="txtclickedaction" name="txtclickedaction" value="">
                    <input type="hidden" id="for_template" name="for_template" value="0">
                    <input type="hidden" id="clickedCount_txt" name="clickedCount_txt" value="-1">
                    <table width="100%">
                    <tr>
                    <td>
                    {$mod.LBL_RELATED_TO}
                    </td>
                    <td>
                        <select name="parent_type" tabindex="" id="parent_type" title="" onchange="document.form_signerfromcrm.parent_name.value='';document.form_signerfromcrm.parent_id.value=''; changeParentQS('parent_name'); checkParentType(document.form_signerfromcrm.parent_type.value, document.form_signerfromcrm.btn_parent_name);">
                            {$sSupportedModuleOption}
                        </select>
                        <input name="parent_name" id="parent_name" class="sqsEnabled yui-ac-input" tabindex="" size="" value="" autocomplete="off" type="text">
                        <input name="email_address" id="email_address" class="" tabindex="" size="" value="" type="hidden">
                        
                        <div id="form_signerfromcrm_parent_name_results" class="yui-ac-container">
                            <div class="yui-ac-content" style="display: none;">
                            <div class="yui-ac-hd" style="display: none;"></div>
                            <div class="yui-ac-bd">
                                <ul>
                                <li style="display: none;"></li>
                                </ul>
                            </div>
                            <div class="yui-ac-ft" style="display: none;"></div>
                            </div>
                        </div>
                        <input name="parent_id" id="parent_id" value="" type="hidden">
                        <span class="id-ff multiple">
                            <button type="button" name="btn_parent_name" id="btn_parent_name" tabindex="" title="Select" class="button firstChild" value="Select" 
                                onclick="">
                            <img src="themes/SuiteP/images/id-ff-select.png">
                            </button>
                            <button type="button" name="btn_clr_parent_name" id="btn_clr_parent_name" tabindex="" title="Clear" class="button lastChild" onclick="this.form.parent_name.value = ''; this.form.parent_id.value = '';" value="Clear">
                            <img src="themes/SuiteP/images/id-ff-clear.png">
                            </button>
                        </span>
                        {literal}
                        <script type="text/javascript">
                        document.getElementsByName('btn_parent_name')[0].attributes['onclick'].value = "javascript:open_popup(document.form_signerfromcrm.parent_type.value, 600, 400, '', true, false, {'call_back_function':'set_return','form_name':'form_signerfromcrm','field_to_name_array':{'id':'parent_id','name':'parent_name','email1':'email_address'}} , 'single', true);";
                        
                        if (typeof(changeParentQS) == 'undefined')
                        {
                            function changeParentQS(field) {
                                if(typeof sqs_objects == 'undefined') {
                                   return;
                                }
                                field = YAHOO.util.Dom.get(field);
                                var form = field.form;
                                var sqsId = form.id + "_" + field.id;
                                var typeField =  form.elements.parent_type;
                                var new_module = typeField.value;

                                //Update the SQS globals to reflect the new module choice
                                if (typeof(QSFieldsArray[sqsId]) != 'undefined')
                                {
                                    QSFieldsArray[sqsId].sqs.modules = new Array(new_module);
                                }
                                if(typeof QSProcessedFieldsArray != 'undefined')
                                {
                                   QSProcessedFieldsArray[sqsId] = false;
                                }
                                if(sqs_objects[sqsId] == undefined){
                                    return;
                                }
                                sqs_objects[sqsId]["modules"] = new Array(new_module);
                                if(typeof(disabledModules) != 'undefined' && typeof(disabledModules[new_module]) != 'undefined') {
                                    sqs_objects[sqsId]["disable"] = true;
                                    field.readOnly = true;
                                } else {
                                    sqs_objects[sqsId]["disable"] = false;
                                    field.readOnly = false;
                                }
                                enableQS(false);
                            }
                        }
                        var disabledModules=[];
                        if(typeof sqs_objects == 'undefined')
                        {
                            var sqs_objects = new Array();
                        }

                        sqs_objects['form_signerfromcrm_parent_name']={"form":"form_signerfromcrm","method":"query","modules":["Accounts"],"group":"or","field_list":["name","id","email1"],"populate_list":["parent_name","parent_id","email_address"],"required_list":["parent_id"],"conditions":[{"name":"name","op":"like_custom","end":"%","value":""}],"order":"name","limit":"30","no_match_text":"No Match"};
                        enableQS(true);
                        //change this in case it wasn't the default on editing existing items.
                        $(document).ready(function(){
                            changeParentQS("parent_name");
                        });
                        </script>
                        {/literal}
                    </td>
                    </tr>
                    </table>
                </form>
            </div>
            <div class='modal-footer'>&nbsp;
            <span style='float: left;'><img src='modules/UT_RightSignature/images/urdhvatech-horizontal-small.png' alt='Urdhva tech logo' style='width: 100px;'></span>
            <button type='button' class='btn btn-primary' id="btnSelectSigner">{$mod.LBL_SELECT}</button>
            <button type='button' class='btn btn-primary' data-dismiss='modal'>{$mod.LBL_CLOSE}</button>
            </div>
        </div>
    </div>
</div>