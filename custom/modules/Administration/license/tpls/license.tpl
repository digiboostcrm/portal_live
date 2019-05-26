
<!-- BEGIN: main -->
{literal}
<script type="text/javascript">

    // Only do anything if jQuery isn't defined
    if (typeof(jQuery) == 'undefined') {

        if (typeof($) == 'function') {
            // warning, global var
            thisPageUsingOtherJSLibrary = true;
        }

        function getScript(url, success) {
            var script     = document.createElement('script');
            script.src = url;

            var head = document.getElementsByTagName('head')[0],
            done = false;

            // Attach handlers for all browsers
            script.onload = script.onreadystatechange = function() {
                if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {

                    done = true;

                    // callback function provided as param
                    success();

                    script.onload = script.onreadystatechange = null;
                    head.removeChild(script);

                };
            };

            head.appendChild(script);
        };

        getScript('{/literal}{$file_path}{literal}', function() {

            if (typeof jQuery=='undefined') {
                // Super failsafe - still somehow failed...
            } else {
                if (thisPageUsingOtherJSLibrary) {
                    // Run your jQuery Code
                } else {
                    // Use .noConflict(), then run your jQuery Code
                }

            }

        });

    }
    function setURL(element){
                    $("#error_span").remove();
                    var key = $("#url");
                    var portal_selected='wordPress';
                    if(portal_selected.length == 0){
                           $("#clearkey_url").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please Select One Portal.</span>")
                            key.focus();
                            return false;
                    }
                        if(key.val().trim() == "" && portal_selected == "wordPress"){
                            $("#clearkey_url").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please enter your Portal URL.</span>")
                            key.focus();
                            return false;
                        }else{
                            var re = new RegExp('^(http|https)://.*$');
                            if (re.test(key.val().trim()) == false && portal_selected == "wordPress") {
                                $("#clearkey_url").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please enter valid Portal URL.</span>")
                                key.focus();
                                return false;
                            }
                             $.ajax({
                                url:"index.php?module=Administration&action=portalHandler&method=setUrl",
                                type:"POST",
                                data:{"k": key.val(),"portal_selected":"wordPress"},
                                beforeSend : function(){
                                    $("#clearkey_url").after("<img style=\'color:red;padding-left: 10px;vertical-align: middle;\' id=\'survey_loader\' src= "+SUGAR.themes.loading_image+">");
                                    $(element).attr("disabled","disabled");
                                },
                                complete : function(){
                                    $("#survey_loader").remove();
                                    $(element).removeAttr("disabled");
                                },
                                success:function(result){
                                $("#survey_loader").remove();
                                $("#enSelect option[value=0]").prop("selected", true);
                                        if(result){
                                            alert("Customer Portal URL successfully entered in Sugar configuration.");
                                            //$("#clearkey_url").after("<span style=\'color:green;padding-left: 10px;\' id=\'error_span\'>Customer Portal URL successfully entered in Sugar config.</span>");
                                            location.href = "index.php?module=Administration&action=index";
                                        }else{
                                            $("#clearkey_url").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>It seems some error occurs while inserting URL.</span>");
                                        }
                                    }
                                });
                            }
                        }

    function clearKey(){
                    $("#url").val("");
                    $("#error_span").html("");
   }
 
    function checkurlconfigdisplay(){
     var LastValidationCheck=$("#LastValidationCheck").val();     
        if(LastValidationCheck == 1){
        $("#urlconfig").css({display: "block"});
             
     }
      else{
            
             $("#url").val("");
             $("#urlconfig").css({display: "none"}); 
     }
    
    }
    function outfitters_validate_license()
    {
        var curl_not_enabled = '{/literal}{$CURL_NOT_ENABLED}{literal}';
        if(curl_not_enabled) {
{/literal}{if $IS_SUGAR_6 == true}{literal}
        $('#urlconfig').css({display: "none"});
            alert(curl_not_enabled);
{/literal}{else}{literal}
            var app = window.parent.SUGAR.App;
            app.alert.show('License_error', {
                level: 'error',
                title: app.lang.get('ERR_LICENSE_CONNECTION', 'AddonBoilerplate'),
                messages: curl_not_enabled,
                autoClose: false
            });
{/literal}{/if}{literal}

            return;
        }
        var licKey = $('#outfitters_license_key').val();
        if(!licKey) {
            return false;
        }
        $('#outfitters_licensed_users').html('');
        $('#btn-outfitters-validate-license').hide();
        $('#outfitters_validation_success').hide();
        $('#outfitters_validation_fail').hide();
        $('#outfitters_license_passed').hide();
        $('#outfitters_license_increase').hide();
        $('#outfitters_validating_license').show();
        $.ajax('index.php?module={/literal}{$MODULE}{literal}&action=portalHandler&method=validateLicence&to_pdf=1',{
            type: 'POST',
            dataType: 'json',
            data: {
                sub_method: 'validate',
                key: licKey
            },
            success: function(response){
                //hide loading
                $('#outfitters_validating_license').hide();
                $('#btn-outfitters-validate-license').show();
                if (response){
                    if(response.validated == true) {
                        $('#outfitters_validation_success').show();
{/literal}
{if $validate_users == true && $manage_licensed_users == true}
                        $('.validation-required').hide();
                        $('.manage-users').show();
{/if}
{literal}
    
    $('#urlconfig').css({display: "block"});
   
    $("#url").val('');
                    } else {
                        $('#outfitters_fail_message').html('Invalid key');
                        $('#outfitters_validation_fail').show();
                        
                    }
                    if(response.licensed_user_count != undefined) {
                        $('#outfitters_licensed_users').html(response.licensed_user_count);
                        $('#licensed_users').val(response.licensed_user_count);
                        outfitters_recalculate_users();

                        if(response.validated_users == true) {
                            $('#outfitters_license_passed').show();
                        } else {
                            $('#btn-outfitters-increase').html('Increase to {/literal}{$current_users}{literal} users');
                            $('#outfitters_license_increase').show();
                        }
                    } else {
                        $('#outfitters_licensed_users').html('0');
                        $('#licensed_users').val(0);
                    }
                } else {
                    alert('Unexpected data returned from the server.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#outfitters_validating_license').hide();
                $('#btn-outfitters-validate-license').show();
                $('#outfitters_fail_message').html($.parseJSON(jqXHR.responseText));
                $('#outfitters_validation_fail').show();
                $('#urlconfig').css({display: "none"});
                alert('Error: '+$.parseJSON(jqXHR.responseText));
            }
        });

        return false;
    }

    function outfitters_increase_license(increase_to)
    {
        var curl_not_enabled = '{/literal}{$CURL_NOT_ENABLED}{literal}';
        if(curl_not_enabled) {
{/literal}{if $IS_SUGAR_6 == true}{literal}
            alert(curl_not_enabled);
{/literal}{else}{literal}
            var app = window.parent.SUGAR.App;
            app.alert.show('License_error', {
                level: 'error',
                title: app.lang.get('ERR_LICENSE_CONNECTION', 'AddonBoilerplate'),
                messages: curl_not_enabled,
                autoClose: false
            });
{/literal}{/if}{literal}

            return;
        }
        var licKey = $('#outfitters_license_key').val();
        if(!licKey) {
            return false;
        }

        $('#outfitters_increasing_license').show();

        $.ajax('index.php?module={/literal}{$MODULE}{literal}&action=portalHandler&method=validateLicence&to_pdf=1',{
            type: 'POST',
            dataType: 'json',
            data: {
                sub_method: 'change',
                key: licKey,
                user_count: increase_to
            },
            success: function(response){
                //hide loading
                $('#outfitters_increasing_license').hide();

                $('#btn-outfitters-validate-license').show();

                if (response.licensed_user_count){
                    $('#outfitters_license_increase').hide();
                    $('#outfitters_licensed_users').html(response.licensed_user_count);
                    $('#outfitters_license_passed').show();
                    $('#licensed_users').val(response.licensed_user_count);
                    outfitters_recalculate_users();
                } else {
                    alert('Unexpected data returned from the server.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#outfitters_increasing_license').hide();

                alert('Error: '+$.parseJSON(jqXHR.responseText));
            }
        });

        return false;
    }

    function outfitters_recalculate_users()
    {
{/literal}{if $validate_users == true && $manage_licensed_users == true}{literal}
        var lic_users = parseInt($('#licensed_users').val(),10);

        var avail_users = $('select[name="licensed_users[]"] option').length;

        avail_users = lic_users - avail_users;
        $('#available_users').val(avail_users);
        $('#outfitters_available_licensed_users').html(avail_users);

        if(avail_users <= 0)
        {
            $('#outfitters_license_increase').show();
        }
{/literal}{/if}{literal}
    }

    $(document).ready(function(){
        $('#chooser_unlicensed_users_left_arrow').parent().css('vertical-align','middle');

        $('#outfitters_additional_license_form').submit(function() { return false; });
        checkurlconfigdisplay();
    });


{/literal}{if $validate_users == true && $manage_licensed_users == true}{literal}
    SUGAR.tabChooser.movementCallback = function(left_side, right_side) {
        outfitters_recalculate_users();
    };

    function outfitters_save_additional_users()
    {
        var lic_users = parseInt($('#licensed_users').val(),10);
        var addt_users = parseInt($('#outfitters_additional_licenses').val(),10);
        if(isNaN(addt_users))
        {
            $('#outfitters_additional_licenses').val(0);
            return false;
        }

        outfitters_increase_license(lic_users + addt_users);
    }

    function outfitters_save_licensed_users()
    {
        $('#outfitters_save_licensed_users_fail').hide();
        $('#btn-outfitters-licensed-users').hide();
        $('#outfitters_save_licensed_users_success').hide();
        $('#outfitters_save_licensed_users').show();

        var lic_users = parseInt($('#licensed_users').val(),10);
        var avail_users = $('select[name="licensed_users[]"] option').length;

        avail_users = lic_users - avail_users;
        $('#available_users').val(avail_users);
        $('#outfitters_available_licensed_users').html(avail_users);

        if(avail_users < 0)
        {
            $('#outfitters_save_licensed_users').hide();
            $('#btn-outfitters-licensed-users').show();
            $('#outfitters_save_licensed_users_fail').show();
            $('#outfitters_save_licensed_users_fail_message').html('{/literal}{$LICENSE.LBL_ERROR_TOO_MANY_USERS}{literal}');
            return false;
        }

        var licensed_users = [];
        $('select[name="licensed_users[]"] option').each(function() {
            licensed_users.push($(this).val());
        });

        $.ajax('index.php?module={/literal}{$MODULE}{literal}&action=portalHandler&method=validateLicence&to_pdf=1',{
            type: 'POST',
            dataType: 'json',
            data: {
                sub_method: 'add',
                licensed_users: licensed_users
            },
            success: function(response){
                //hide loading
                $('#outfitters_save_licensed_users').hide();

                $('#btn-outfitters-licensed-users').show();
                $('#outfitters_save_licensed_users_success').show();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#outfitters_save_licensed_users').hide();
                $('#btn-outfitters-licensed-users').show();

                alert('Error: '+$.parseJSON(jqXHR.responseText));
            }
        });

        return false;
    }

{/literal}{/if}{literal}


</script>
<style type="text/css">
    .outfitters_license_key {
        background-color: #ffffff;
        border: 1px solid #4E8CCF;
        text-align: center;
        padding: 10px 30px;
        font-size: 1.2em;
        margin-right: 10px;
    }
    #outfitters_license_increase {
        color: red;
        font-weight: bold;
        font-size: 1.1em;
    }
    #outfitters_license_increase, #outfitters_license_passed, #outfitters_validation_fail, #outfitters_validation_success {
        padding-left: 10px;
    }
    #chooser_unlicensed_users_up_arrow
    ,#chooser_licensed_users_down_arrow
    {
        display: none;
    }
    #chooser_unlicensed_users_left_arrow
    ,#chooser_unlicensed_users_left_to_right
    {
        display: block;
        cursor: pointer;
        padding: 12px;
        margin-bottom: 10px;
    }
    #chooser_unlicensed_users_left_arrow
    {
        margin-bottom: 10px;
    }
    .manage-users select {
        width: 220px !important;
    }
    #outfitters_license_increase {
        padding: 0;
    }
    #outfitters_additional_licenses {
        text-align: center;
    }
    .btn-outfitters-big {
        margin-top: 8px;
        padding: 6px 18px !important;
    }
    .outfitters-success {
        margin: 0 20px;
    }
</style>
{/literal}

<form name="outfitters_license_form" id="outfitters_license_form" method="POST">
    <input type="hidden" name="module" value="{$MODULE}">
    <input type="hidden" name="action">
    <input type="hidden" name="return_module" value="{$RETURN_MODULE}">
    <input type="hidden" name="return_action" value="{$RETURN_ACTION}">
    <input type="hidden" name="licensed_users" id="licensed_users" value="{$licensed_users}">
    <input type="hidden" name="available_users" id="available_users" value="{$available_licensed_users}">
<div id="main">
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="edit view">
    <tr><th align="left" scope="row" colspan="4"><h4>{$LICENSE.LBL_STEPS_TO_LOCATE_KEY_TITLE}</h4></th></tr>
    <tr>
        <td align="left" scope="row" colspan="4">
            {$LICENSE.LBL_STEPS_TO_LOCATE_KEY}
        </td>
   </tr>
    <tr>
        <td width="20%" scope="row" style="vertical-align: middle">{$LICENSE.LBL_LICENSE_KEY}</td>
        <td width="30%" colspan="3" scope="row">
            <input id='outfitters_license_key' name='outfitters_license_key' class='outfitters_license_key' tabindex='1' size='50' maxlength='100' type="text" value="{$license_key}">
            <input title="{$LICENSE.LBL_VALIDATE_LABEL}" class="button primary" onclick="return outfitters_validate_license();" type="submit" name="button" id="btn-outfitters-validate-license" value=" {$LICENSE.LBL_VALIDATE_LABEL} ">
            <span id="outfitters_validating_license" style="display: none"><img src="themes/default/images/img_loading.gif" alt="Loading"></img> Validating...</span>
            <span id="outfitters_validation_fail" style="display: none"><img src="themes/default/images/no.gif" alt="Failed"></img> Failed: <span id="outfitters_fail_message"></span></span>
            <span id="outfitters_validation_success" style="display: none">
                <img src="themes/default/images/yes.gif" alt="Success"></img> Success!
                <input type="hidden" name="LastValidationCheck" value="{$LastValidationCheck}" id="LastValidationCheck">
                    <br/><br/>
                   
            </span>
        </td>
        
        
    </tr>
    <tr>
        <td width="20%" scope="row"></td>
        <td width="30%" scope="row"></td>
        <td width="20%" scope="row"></td>
        <td width="30%" scope="row"></td>
    </tr>


</table></div> 
<div id="urlconfig" style="display:none;" ><table border="0" cellpadding="0" cellspacing="0" width="100%">
               <table id="ConfigureSurvey" class="themeSettings edit view" style="margin-bottom:0px;" border="0" cellpadding="0" cellspacing="0">
			    <tbody>
			 <h2>Customer Poral URL Configuration </h2>
                <div class="clear"></div></div>
                </h2></td></tr>
                <tr><td colspan="100">
	            <div class="add_table" style="margin-bottom:5px">
		            <tr><th align="left" colspan="4" scope="row"><h4>Enter Customer Portal URL Which is used by Sugar/SuiteCRM when contact is created for creating Portal user.</h4></th></tr>
			    <tr>
			    
            	<td nowrap="nowrap" style="width: 15%;"><input name="url" id="url" size="30" maxlength="255" value="{$url}" title="" accesskey="9" type="text"></td>
            	<td nowrap="nowrap" style="width: 20%;"><input title="Validate" id="Validate" class="button primary" onclick="setURL(this);" name="validate" value="Save" type="button">&nbsp;<input title="Clear" id="clearkey_url" class="button primary" onclick="clearKey();" name="clear" value="Clear" type="button"></td>
            	<td nowrap="nowrap" style="width: 55%;">&nbsp;</td>
            	</tr></tbody></table>           
            </div> 
            
</form>

{if $validate_users == true}

    {if $manage_licensed_users == false}
        <table width="100%" border="1" cellspacing="0" cellpadding="0" class="edit view">
            <tr>
                <td width="20%" scope="row"></td>
                <td width="30%" scope="row"></td>
                <td width="20%" scope="row"></td>
                <td width="30%" scope="row"></td>
            </tr>
            <tr>
                <td width="20%" scope="row">{$LICENSE.LBL_CURRENT_USERS}</td>
                <td width="30%" scope="row" id="outfitters_current_users">{$current_users}</td>
                <td width="20%" scope="row"></td>
                <td width="30%" scope="row"></td>
            </tr>
            <tr>
                <td width="20%" scope="row">{$LICENSE.LBL_LICENSED_USERS}</td>
                <td width="30%" scope="row" colspan="3">
                    <span id="outfitters_licensed_users"></span>
                    <span id="outfitters_increasing_license" style="display: none"><img src="themes/default/images/img_loading.gif" alt="Loading"></img> Boosting...</span>
                    <span id="outfitters_license_increase" style="display: none">
                        <img src="themes/default/images/no.gif" alt="Warning"></img> Warning: Boost license to continue using this add-on
                        <br/>
                        <button id="btn-outfitters-increase" onclick="javascript:outfitters_increase_license({$current_users}); return false;">Boost to # users</button>
                    </span>
                    <span id="outfitters_license_passed" style="display: none">
                        <img src="themes/default/images/yes.gif" alt="Passed"></img> Verified!

                    </span>
                </td>
            </tr>
        </table>
    {else}
        {if $validation_required == true}
            <table width="100%" border="1" cellspacing="0" cellpadding="0" class="edit view validation-required">
                <tr><th align="left" scope="row" colspan="4"><h4>{$LICENSE.LBL_MANAGE_USERS_TITLE}</h4></th></tr>
                <tr>
                    <td width="20%" scope="row"></td>
                    <td width="30%" scope="row"></td>
                    <td width="20%" scope="row"></td>
                    <td width="30%" scope="row"></td>
                </tr>
                <tr>
                    <td colspan="4" scope="row">{$LICENSE.LBL_VALIDATION_REQUIRED}</td>
                </tr>
            </table>
        {/if}
        <form name="outfitters_additional_license_form" id="outfitters_additional_license_form" method="POST" >
            <input type="hidden" name="method" value="add"/>
            <table width="100%" border="1" cellspacing="0" cellpadding="0" class="edit view manage-users" {if $validation_required == true}style="display: none"{/if}>
                <tr><th align="left" scope="row" colspan="4"><h4>{$LICENSE.LBL_MANAGE_USERS_TITLE}</h4></th></tr>
                <tr>
                    <td width="20%" scope="row"></td>
                    <td width="30%" scope="row"></td>
                    <td width="20%" scope="row"></td>
                    <td width="30%" scope="row"></td>
                </tr>
                <tr>
                    <td width="20%" scope="row">{$LICENSE.LBL_LICENSED_USERS}:</td>
                    <td width="30%" scope="row" id="outfitters_licensed_users">{$licensed_users}</td>
                    <td width="20%" scope="row"></td>
                    <td width="30%" scope="row"></td>
                </tr>
                <tr>
                    <td width="20%" scope="row">{$LICENSE.LBL_AVAILABLE_USERS}:</td>
                    <td width="30%" scope="row">
                        <span id="outfitters_available_licensed_users">{$available_licensed_users}</span>
                    </td>
                    <td width="20%" scope="row"></td>
                    <td width="30%" scope="row"></td>
                </tr>
                <tr id="outfitters_show_additional_licenses">
                    <td width="20%" scope="row">{$LICENSE.LBL_HOW_MANY_USERS}:</td>
                    <td scope="row" colspan="3">
                        <div id="outfitters_additional_license_increase">
                            <input type="text" name="outfitters_additional_licenses" id="outfitters_additional_licenses" size="6" value="5"/>
                            <input title="{$LICENSE.LBL_ADD_USERS_BUTTON_LABEL}" class="button primary" onclick="return outfitters_save_additional_users();" type="button" name="button" id="btn-outfitters-additional-users" value=" {$LICENSE.LBL_ADD_USERS_BUTTON_LABEL} ">
                        </div>
                        <span id="outfitters_increasing_license" style="display: none"><img src="themes/default/images/img_loading.gif" alt="Loading"></img> Increasing...</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" scope="row">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td scope="row" align="left" style="padding-bottom: 2em;">{$USER_CHOOSER}</td>
                                <td scope="row" width="90%" valign="top"><BR>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td scope="row">&nbsp;</td>
                    <td scope="row" colspan="3">
                        <input title="{$APP.LBL_SAVE_BUTTON_LABEL}" class="button primary btn-outfitters-big" onclick="return outfitters_save_licensed_users();" type="button" name="button" id="btn-outfitters-licensed-users" value=" {$APP.LBL_SAVE_BUTTON_LABEL} ">
                        <span id="outfitters_save_licensed_users" style="display: none"><img src="themes/default/images/img_loading.gif" alt="Loading"></img> Saving Users...</span>
                        <span id="outfitters_save_licensed_users_fail" style="display: none"><img src="themes/default/images/no.gif" alt="Failed"></img> Failed: <span id="outfitters_save_licensed_users_fail_message"></span></span>
                        <span id="outfitters_save_licensed_users_success" style="display: none">
                            <span class="outfitters-success"><img src="themes/default/images/yes.gif" alt="Success"></img> Success!</span>
                           
                        </span>
                    </td>
                </tr>
                <tr>
                    <td scope="row" colspan="4"><div style="margin-top: 18px"><em>{$LICENSE.LBL_HOWTO_REDUCE_LICENSE}</em></div></td>
                </tr>
            </table>
        </form>
    {/if}
{/if}
