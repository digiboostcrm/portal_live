
<script type='text/javascript' src='modules/rls_Reports/js/adminPanel.js'></script>
<h3>Reports parameters:</h3>
<form name="ConfigureSettings" enctype='multipart/form-data' method="POST" action="index.php" onSubmit="return (add_checks(document.ConfigureSettings) && check_form('ConfigureSettings'));">
    <input type='hidden' name='action' value='rls_Reports_configurator'/>
    <input type='hidden' name='module' value='Configurator'/>
    <span class='error'>{$error.main}</span>
    <BR/>
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding-bottom: 2px;">
                <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button"  type="submit"  name="save" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
                &nbsp;<input title="{$MOD.LBL_SAVE_BUTTON_TITLE}"  class="button"  type="submit" name="restore" value="  {$MOD.LBL_RESTORE_BUTTON_LABEL}  " >
                &nbsp;<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}"  onclick="document.location.href = 'index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " >
            </td>
        </tr>
        <tr><td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
                    <tr>
                        <td>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
  									<td nowrap width="10%" class="dataLabel">Use NodeJs image generator
									</td>
									<td width="25%" class="dataField">
										{if is_null($config.switch_mode)}
            								{assign var='switch_mode' value=$reports_config.switch_mode}
										{else}
            								{assign var='switch_mode' value=$config.switch_mode}
        								{/if}

										{if $switch_mode == 1}
            								{assign var='switch_mode' value=' checked="checked"'}
										{else}
												{assign var='switch_mode' value=''}		
										{/if}
										<input type='checkbox' id='switch_mode' name='switch_mode' size="45" value='1' {$switch_mode}>
									</td>

                                    <td nowrap width="10%" class="dataLabel">Node Js Hostname
                                    </td>
                                    <td width="25%" class="dataField"> 
                                        <input type='textbox' name='nodejshost' size="45" value='{$config.nodejshost}'>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            <td>
                <br>
    </table>
    <div style="padding-top: 2px;">
        <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button"  type="submit" name="save" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " />
        &nbsp;<input title="{$MOD.LBL_SAVE_BUTTON_TITLE}"  class="button"  type="submit" name="restore" value="  {$MOD.LBL_RESTORE_BUTTON_LABEL}  " />
        &nbsp;<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}"  onclick="document.location.href = 'index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " />
    </div>
</form>
{$JAVASCRIPT}
