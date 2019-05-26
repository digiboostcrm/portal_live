<div id="myRSModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="myModalLabel1"><img src="modules/UT_RightSignature/images/rightsignature_32x32.png" /> {$mod.LBL_UT_AUTH_PERMISSION_TITLE}</h4>
      </div>
      <div class="modal-body">
        <form method="POST" action="{$site_url}/UT_RSCallback.php" name="oauth2_rightsignature" id="oauth2_rightsignature">
        </form>
        <div class="content">
            <h3 class="modal-title">{$mod.LBL_UT_AUTH_REQUEST_ACCESS}</h3>
            <p>{$mod.LBL_UT_AUTH_REQUEST_ACCESS_MSG}</p>
        </div>
      </div>
      <div class="modal-footer">
        <a id="btnYesRightSignature" href="javascript:void(0)" class="btn btn-primary" style="color:#fffff; text-decoration:none;">
            <img src="modules/UT_RightSignature/images/rightsignature_32x32.png" />    {$mod.LBL_UT_AUTH_PROCEED}
        </a>
        <a href="javascript:void(0)" class="btn secondary" data-dismiss="modal" aria-hidden="true" style="text-decoration:none;">{$mod.LBL_CANCEL_BUTTON}</a>
      </div>
    </div>
  </div>
</div>