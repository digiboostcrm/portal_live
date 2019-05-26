<script src="custom/modules/Administration/js/p_payment.js"></script>
<style type="text/css">
    {literal}
        label{
            color:red;
        }
        .companyLogo{
           height:90;
        }
    {/literal}
</style>



<form name="ConfigureSettings" enctype='multipart/form-data' method="POST" action="index.php" onSubmit="return validfile();">
    <input type='hidden' name='action' value='SaveConfig'/>
    <input type='hidden' name='module' value='Configurator'/>
    <span class='error'>{$error.main}</span><br>
    <h3><b>Payment Configuration</b></h3>
    <br>
    <br>
    <table width="100%" cellpadding="0" cellspacing="1" border="0" class="edit view">
        <!--  status enable/dispaly -->
        <tr>
            <td scope='row'>{$MOD.LNK_LABLE_STATUS_ENABLE_DISABLE}: </td>
            <td><select name="status" id="status">
                    {if $payment_status eq 1}
                        <option value="1" selected>Enable</option>
                        <option value="0">Disable</option>
                    {else}  
                        <option value="1">Enable</option>
                        <option value="0" selected>Disable</option>
                    {/if}

                </select></td>
        </tr>
    </table>
    <br>
    <div id="payment">
        <table width="100%" cellpadding="0" cellspacing="1" border="0" class="edit view">
            <tr>
                <!--  account module -->
                <td scopre='row'>{$MOD.LNK_MODULE_FEILDS}:</td>
                <td><input type="hidden" id="currency_fields" name="currency_fields_name" value="currency_fields" /><select name="amount_module" onchange="AddModuleFeilds();">
                       <option value="">Select Module</option>  
                        {foreach from=$amount_module key=name item=def}
                            {foreach from=$table key=name item=tab}
                                {foreach from=$tab key=name1 item=def1}
                                    {if $def1|strtoupper eq $def.module|strtoupper}
                                        {if $payment_amount_module eq $def.module}
                                            <option value="{$def.module}" selected>{$def.label}</option>
                                        {else}
                                            <option value="{$def.module}">{$def.label}</option> 
                                        {/if}
                                    {/if}
                            {/foreach}{/foreach}
                        {/foreach}</select><label id="amount_module1"></label></td>
                <!--  account feilds -->  
                <td>
                    <select name="amount_fields" id="amount_fields">
                        {foreach from=$collectionKey key=name item=def}
                          
                                {if $def.name eq $payment_amount_fields}
                                    <option value="{$def.name}" selected>{$def.value}</option>
                                {else}
                                    <option value="{$def.name}">{$def.value}</option>
                                {/if}
                               
                                    {/foreach}
                                    </select><label id="amount_fields1"></label></td>
                            </tr>
                            <tr>
                                <td scopre='row'>{$MOD.LNK_STATUS_MODULE_FEILDS}:</td>
                                <!--  payment module -->
                                <td> <input type="hidden" id="status_fields" name="status_fields_name" value="status_fields" /><select name="payment_module" onchange="AddStatusFeilds();">
                                        
                                      <option value="">Select Module</option> 
                                        {foreach from=$amount_module key=name item=def}
                                            {foreach from=$table key=name item=tab}
                                                {foreach from=$tab key=name1 item=def1}
                                                    {if $def1|strtoupper eq $def.module|strtoupper}
                                                        {if $payment_payment_module eq $def.module}
                                                            <option value="{$def.module}" selected>{$def.label}</option>
                                                        {else}
                                                            <option value="{$def.module}">{$def.label}</option>  
                                                        {/if}
                                                    {/if}
                                            {/foreach}{/foreach}
                                        {/foreach}</select><label id="payment_module1"></label></td>

                                <td><!--  payment feilds -->
                                    <select name="payment_feilds" id="payment_feilds">
                                        {foreach from=$collectionKey1 key=name item=def}
                                            {if $def.name eq $payment_payment_feilds}
                                                    
                                                   <option value="{$def.name}" selected>{$def.value}</option>
                                                {else}
                                                    
                                                    <option value="{$def.name}">{$def.value}</option>
                                                {/if}
                                                {$def.name|@print_r}
                                                    {/foreach}
                                                    </select><label id="payment_feilds1"></label></td>
                                            </tr>        
        </table></div><br>
     <table width="100%" cellpadding="0" cellspacing="1" border="0" class="actionsContainer">
            <tr>
                <td>
                    <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" id="ConfigureSettings_save_button" type="submit"  name="save" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " ></td>
            </tr>

    </table>
 </form>

