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
<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
<div id="utBody" style="background-color: rgb(215, 215, 215); padding:30px 0px;">
<form name="UrdhvaTechRightSignature" method="post" role="form">
    <input type="hidden" name="module" value="UT_RightSignature">
    <input type="hidden" name="action" value="RightSignatureAppKeys">
    <div class="">
        {if $MESSAGE}
            <div role="alert" class="alert alert-success offset1 span9" style="text-align:center;">
                <strong style="font-size:13px;">{$MESSAGE}</strong>
            </div>
        {/if}
        <div class="divider medium"></div>
        <div id="login-overlay" class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel"><img src="modules/UT_RightSignature/images/rightsignature_32x32.png" /> {$mod.LBL_UT_RIGHTSIGNATURE_TITLE}</a>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-left:0px; margin-right:0px;">
                        <div class="col-xs-6">
                            <div class="well">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="consumer_key" class="control-label">{$mod.LBL_CONSUMER_KEY}</label>
                                                <input type="text" class="form-control" name="consumer_key" value="{$rs_consumer_key}" required="" title="{$mod.LBL_CONSUMER_KEY}" placeholder="{$mod.LBL_CONSUMER_KEY}">
                                                <span class="help-block"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="consumer_secret" class="control-label">{$mod.LBL_CONSUMER_SECRET}</label>
                                                <input type="text" class="form-control" name="consumer_secret" value="{$rs_consumer_secret}" required="" title="{$mod.LBL_CONSUMER_SECRET}" placeholder="{$mod.LBL_CONSUMER_SECRET}" >
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>{$mod.LBL_PROCEED_TO_SAVE_KEY}</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" name="submit" class="btn btn-success btn-block"> {$mod.LBL_SAVE_CONTINUE_BUTTON}</button>
                                        </div>
                                        <div class="col-md-6"  style="margin-top:5px;padding-left:10px;">
                                            <a href="">{$mod.LBL_CANCEL_BUTTON}</a>
                                        </div>
                                    </div>

                            </div>
                        </div>
                          <div class="col-xs-6">
                              <h5 style="padding-bottom: 15px;"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {$mod.LBL_TITLE_INFORMATION}</h5>
                              <div style="padding:0px 10px;">
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
$(document).ready(function () {

});
</script>
{/literal}