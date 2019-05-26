<div id="utBody" style="background-color: rgb(215, 215, 215); padding:30px 0px;">
<form name="UrdhvaTechSocial" method="post">
    <input type="hidden" name="module" value="UT_RightSignature">
    <input type="hidden" name="action" value="RightSignatureAppKeys">
    <div class="container">
        {if $MESSAGE}
            <div role="alert" class="alert alert-success offset1 span9" style="text-align:center;">
                <strong style="font-size:13px;">{$MESSAGE}</strong>
            </div>
        {/if}
        <div id="login-overlay" class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><img src="modules/UT_RightSignature/images/rightsignature_32x32.png" /> {$mod.LBL_UT_AUTH_TITLE}</a>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-left:0px; margin-right:0px;">
                        <div class="col-xs-5">
                            <div class="well">
                                <div class="row">
                                    <div class="col-md-12 col-xs-offset-2">
                                        <div id="rightsignature_divreplace">
                                            {if $isSetRightSignatureKey && $isSetRightSignatureAuth}
                                                <a class="btn btn-primary actionsociallogout" social='rightsignature'>
                                                  <img src="modules/UT_RightSignature/images/rightsignature_32x32.png" /> {$mod.LBL_UT_AUTH_LOGOUT}
                                                </a>
                                            {elseif $isSetRightSignatureKey && not $isSetRightSignatureAuth}
                                                <a class="btn btn-primary actionsociallogin" social='rightsignature' data-toggle="modal" data-target="#myRSModal">
                                                  <img src="modules/UT_RightSignature/images/rightsignature_32x32.png" /> {$mod.LBL_UT_AUTH_LOGIN}
                                                </a>
                                            {elseif not $isSetRightSignatureKey}
                                                <div class="alert alert-danger" style="font-size:14px;">
                                                  <i class="fa fa-warning fa-lg"></i>&nbsp;&nbsp;{$mod.LBL_UT_AUTH_APP_KEY_MISSING}
                                                </div>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-xs-offset-1">
                              <h5><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {$mod.LBL_TITLE_INFORMATION}</h5>
                              <div>
                              {$mod.LBL_TITLE_INFORMATION_DESCRIPTION}
                              </div>
                              <h5 style="padding-top:15px;padding-bottom: 15px;"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {$mod.LBL_REDIRECT_URL}</h5>
                              <div style="padding:0px 10px;">
                                {$mod.LBL_REDIRECT_URL_DESCRIPTION}
                                <br/>
                                {$redirecturl_information}
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="divider large"></div>
    </div>
</form>
</div>

{include file='modules/UT_RightSignature/tpls/rightsignaturemodal.tpl' site_url=$site_url}
<script src="modules/UT_RightSignature/js/RightSignatureAuth.js"></script>
{literal}
<script>
$(document).ready(function () {

});
</script>
{/literal}