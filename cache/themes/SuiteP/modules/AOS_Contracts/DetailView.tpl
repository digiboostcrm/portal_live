

<script language="javascript">
    {literal}
    SUGAR.util.doWhen(function () {
        return $("#contentTable").length == 0;
    }, SUGAR.themes.actionMenu);
    {/literal}
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="">
<tr>
<td class="buttons" align="left" NOWRAP width="80%">
<div class="actionsContainer">
<form action="index.php" method="post" name="DetailView" id="formDetailView">
<input type="hidden" name="module" value="{$module}">
<input type="hidden" name="record" value="{$fields.id.value}">
<input type="hidden" name="return_action">
<input type="hidden" name="return_module">
<input type="hidden" name="return_id">
<input type="hidden" name="module_tab">
<input type="hidden" name="isDuplicate" value="false">
<input type="hidden" name="offset" value="{$offset}">
<input type="hidden" name="action" value="EditView">
<input type="hidden" name="sugar_body_only">
{if !$config.enable_action_menu}
<div class="buttons">
{if $bean->aclAccess("edit")}<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button primary" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='AOS_Contracts'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Edit" id="edit_button" value="{$APP.LBL_EDIT_BUTTON_LABEL}">{/if} 
{if $bean->aclAccess("edit")}<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='AOS_Contracts'; _form.return_action.value='DetailView'; _form.isDuplicate.value=true; _form.action.value='EditView'; _form.return_id.value='{$id}';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}" id="duplicate_button">{/if} 
{if $bean->aclAccess("delete")}<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='AOS_Contracts'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('{$APP.NTC_DELETE_CONFIRMATION}')) SUGAR.ajaxUI.submitForm(_form); return false;" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}" id="delete_button">{/if} 
{if $bean->aclAccess("edit") && $bean->aclAccess("delete")}<input title="{$APP.LBL_DUP_MERGE}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='AOS_Contracts'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='Step1'; _form.module.value='MergeRecords';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Merge" value="{$APP.LBL_DUP_MERGE}" id="merge_duplicate_button">{/if} 
<input type="button" class="button" onClick="showPopup('pdf');" value="{$MOD.LBL_PRINT_AS_PDF}"/>
<input type="button" class="button" onClick="showPopup('emailpdf');" value="{$MOD.LBL_EMAIL_PDF}"/>
{if $bean->aclAccess("detail")}{if !empty($fields.id.value) && $isAuditEnabled}<input id="btn_view_change_log" title="{$APP.LNK_VIEW_CHANGE_LOG}" class="button" onclick='open_popup("Audit", "600", "400", "&record={$fields.id.value}&module_name=AOS_Contracts", true, false,  {ldelim} "call_back_function":"set_return","form_name":"EditView","field_to_name_array":[] {rdelim} ); return false;' type="button" value="{$APP.LNK_VIEW_CHANGE_LOG}">{/if}{/if}
</div>                    {/if}
</form>
</div>
</td>
<td align="right" width="20%" class="buttons">{$ADMIN_EDIT}
</td>
</tr>
</table>
{sugar_include include=$includes}
<div class="detail-view">
<div class="mobile-pagination">{$PAGINATION}</div>

<ul class="nav nav-tabs">


<li role="presentation" class="active">
<a id="tab0" data-toggle="tab" class="hidden-xs">
{sugar_translate label='DEFAULT' module='AOS_Contracts'}
</a>


<a id="xstab0" href="#" class="visible-xs first-tab-xs dropdown-toggle" data-toggle="dropdown">
{sugar_translate label='DEFAULT' module='AOS_Contracts'}
</a>
<ul id="first-tab-menu-xs" class="dropdown-menu">
<li role="presentation">
<a id="tab1" data-toggle="tab" onclick="changeFirstTab(this, 'tab-content-1');">
{sugar_translate label='DEFAULT' module='AOS_Contracts'}
</a>
</li>
<li role="presentation">
<a id="tab2" data-toggle="tab" onclick="changeFirstTab(this, 'tab-content-2');">
{sugar_translate label='LBL_LINE_ITEMS' module='AOS_Contracts'}
</a>
</li>
<li role="presentation">
<a id="tab3" data-toggle="tab" onclick="changeFirstTab(this, 'tab-content-3');">
{sugar_translate label='LBL_PANEL_ASSIGNMENT' module='AOS_Contracts'}
</a>
</li>
</ul>
</li>


<li role="presentation" class="hidden-xs">
<a id="tab1" data-toggle="tab">
{sugar_translate label='LBL_LINE_ITEMS' module='AOS_Contracts'}
</a>
</li>


<li role="presentation" class="hidden-xs">
<a id="tab2" data-toggle="tab">
{sugar_translate label='LBL_PANEL_ASSIGNMENT' module='AOS_Contracts'}
</a>
</li>
{if $config.enable_action_menu and $config.enable_action_menu != false}
<li id="tab-actions" class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#">ACTIONS<span class="suitepicon suitepicon-action-caret"></span></a>
<ul class="dropdown-menu">
<li>{if $bean->aclAccess("edit")}<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button primary" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='AOS_Contracts'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Edit" id="edit_button" value="{$APP.LBL_EDIT_BUTTON_LABEL}">{/if} </li>
<li>{if $bean->aclAccess("edit")}<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='AOS_Contracts'; _form.return_action.value='DetailView'; _form.isDuplicate.value=true; _form.action.value='EditView'; _form.return_id.value='{$id}';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}" id="duplicate_button">{/if} </li>
<li>{if $bean->aclAccess("delete")}<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='AOS_Contracts'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('{$APP.NTC_DELETE_CONFIRMATION}')) SUGAR.ajaxUI.submitForm(_form); return false;" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}" id="delete_button">{/if} </li>
<li>{if $bean->aclAccess("edit") && $bean->aclAccess("delete")}<input title="{$APP.LBL_DUP_MERGE}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='AOS_Contracts'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='Step1'; _form.module.value='MergeRecords';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Merge" value="{$APP.LBL_DUP_MERGE}" id="merge_duplicate_button">{/if} </li>
<li><input type="button" class="button" onClick="showPopup('pdf');" value="{$MOD.LBL_PRINT_AS_PDF}"/></li>
<li><input type="button" class="button" onClick="showPopup('emailpdf');" value="{$MOD.LBL_EMAIL_PDF}"/></li>
<li>{if $bean->aclAccess("detail")}{if !empty($fields.id.value) && $isAuditEnabled}<input id="btn_view_change_log" title="{$APP.LNK_VIEW_CHANGE_LOG}" class="button" onclick='open_popup("Audit", "600", "400", "&record={$fields.id.value}&module_name=AOS_Contracts", true, false,  {ldelim} "call_back_function":"set_return","form_name":"EditView","field_to_name_array":[] {rdelim} ); return false;' type="button" value="{$APP.LNK_VIEW_CHANGE_LOG}">{/if}{/if}</li>
</ul>        </li>
<li class="tab-inline-pagination">
{$PAGINATION}
</li>
{/if}
</ul>
<div class="clearfix"></div>

<div class="tab-content">

<div class="tab-pane-NOBOOTSTRAPTOGGLER active fade in" id='tab-content-0'>





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_NAME' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="name" field="name"  >

{if !$fields.name.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.name.value) <= 0}
{assign var="value" value=$fields.name.default_value }
{else}
{assign var="value" value=$fields.name.value }
{/if} 
<span class="sugar_field" id="{$fields.name.name}">{$fields.name.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_STATUS' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="status"  >

{if !$fields.status.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.status.options)}
<input type="hidden" class="sugar_field" id="{$fields.status.name}" value="{ $fields.status.options }">
{ $fields.status.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.status.name}" value="{ $fields.status.value }">
{ $fields.status.options[$fields.status.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_TOTAL_CONTRACT_VALUE' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="currency" field="total_contract_value"  >

{if !$fields.total_contract_value.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.total_contract_value.name}'>
{sugar_number_format var=$fields.total_contract_value.value }
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ASSIGNED_TO_NAME' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="assigned_user_name"  >

{if !$fields.assigned_user_name.hidden}
{counter name="panelFieldCount" print=false}

<span id="assigned_user_id" class="sugar_field" data-id-value="{$fields.assigned_user_id.value}">{$fields.assigned_user_name.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_START_DATE' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="start_date"  >

{if !$fields.start_date.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.start_date.value) <= 0}
{assign var="value" value=$fields.start_date.default_value }
{else}
{assign var="value" value=$fields.start_date.value }
{/if}
<span class="sugar_field" id="{$fields.start_date.name}">{$value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTRACT_ACCOUNT' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="contract_account"  >

{if !$fields.contract_account.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.contract_account_id.value)}
{capture assign="detail_url"}index.php?module=Accounts&action=DetailView&record={$fields.contract_account_id.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="contract_account_id" class="sugar_field" data-id-value="{$fields.contract_account_id.value}">{$fields.contract_account.value}</span>
{if !empty($fields.contract_account_id.value)}</a>{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_END_DATE' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="end_date"  >

{if !$fields.end_date.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.end_date.value) <= 0}
{assign var="value" value=$fields.end_date.default_value }
{else}
{assign var="value" value=$fields.end_date.value }
{/if}
<span class="sugar_field" id="{$fields.end_date.name}">{$value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTACT' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="contact"  >

{if !$fields.contact.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.contact_id.value)}
{capture assign="detail_url"}index.php?module=Contacts&action=DetailView&record={$fields.contact_id.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="contact_id" class="sugar_field" data-id-value="{$fields.contact_id.value}">{$fields.contact.value}</span>
{if !empty($fields.contact_id.value)}</a>{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_RENEWAL_REMINDER_DATE' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="datetimecombo" field="renewal_reminder_date"  >

{if !$fields.renewal_reminder_date.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.renewal_reminder_date.value) <= 0}
{assign var="value" value=$fields.renewal_reminder_date.default_value }
{else}
{assign var="value" value=$fields.renewal_reminder_date.value }
{/if} 
<span class="sugar_field" id="{$fields.renewal_reminder_date.name}">{$fields.renewal_reminder_date.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_OPPORTUNITY' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="opportunity"  >

{if !$fields.opportunity.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.opportunity_id.value)}
{capture assign="detail_url"}index.php?module=Opportunities&action=DetailView&record={$fields.opportunity_id.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="opportunity_id" class="sugar_field" data-id-value="{$fields.opportunity_id.value}">{$fields.opportunity.value}</span>
{if !empty($fields.opportunity_id.value)}</a>{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CUSTOMER_SIGNED_DATE' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="customer_signed_date"  >

{if !$fields.customer_signed_date.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.customer_signed_date.value) <= 0}
{assign var="value" value=$fields.customer_signed_date.default_value }
{else}
{assign var="value" value=$fields.customer_signed_date.value }
{/if}
<span class="sugar_field" id="{$fields.customer_signed_date.name}">{$value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTRACT_TYPE' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="contract_type"  >

{if !$fields.contract_type.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.contract_type.options)}
<input type="hidden" class="sugar_field" id="{$fields.contract_type.name}" value="{ $fields.contract_type.options }">
{ $fields.contract_type.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.contract_type.name}" value="{ $fields.contract_type.value }">
{ $fields.contract_type.options[$fields.contract_type.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_COMPANY_SIGNED_DATE' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="company_signed_date"  >

{if !$fields.company_signed_date.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.company_signed_date.value) <= 0}
{assign var="value" value=$fields.company_signed_date.default_value }
{else}
{assign var="value" value=$fields.company_signed_date.value }
{/if}
<span class="sugar_field" id="{$fields.company_signed_date.name}">{$value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CCUSTOM_FILE' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="file" field="custom_file"  >

{if !$fields.custom_file.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.custom_file.name}">
<a href="index.php?entryPoint=download&id={$fields.id.value}&type={$module}" class="tabDetailViewDFLink" target='_blank'>{$fields.custom_file.value}</a>
&nbsp;
<a href="index.php?preview=yes&entryPoint=download&id={$fields.id.value}&type={$module}" class="tabDetailViewDFLink" target='_blank' style="border-bottom: 0px;">
<i class="glyphicon glyphicon-eye-open"></i>
</a>
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DESCRIPTION' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="description" colspan='3' >

{if !$fields.description.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.description.name|escape:'html'|url2html|nl2br}">{$fields.description.value|escape:'html'|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>
                    </div>
<div class="tab-pane-NOBOOTSTRAPTOGGLER fade" id='tab-content-1'>





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CURRENCY' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="id" field="currency_id" colspan='3' >

{if !$fields.currency_id.hidden}
{counter name="panelFieldCount" print=false}
<span id='currency_id_span'>
{$fields.currency_id.value}
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_LINE_ITEMS' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="function" field="line_items" colspan='3' >

{if !$fields.line_items.hidden}
{counter name="panelFieldCount" print=false}
<span id='line_items_span'>
{$fields.line_items.value}
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">
</div>

<div class="clear"></div>



<div class="col-xs-12 col-sm-6 detail-view-row-item">
</div>

<div class="clear"></div>
</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_TOTAL_AMT' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="currency" field="total_amt" colspan='3' >

{if !$fields.total_amt.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.total_amt.name}'>
{sugar_number_format var=$fields.total_amt.value }
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DISCOUNT_AMOUNT' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="currency" field="discount_amount" colspan='3' >

{if !$fields.discount_amount.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.discount_amount.name}'>
{sugar_number_format var=$fields.discount_amount.value }
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SUBTOTAL_AMOUNT' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="currency" field="subtotal_amount" colspan='3' >

{if !$fields.subtotal_amount.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.subtotal_amount.name}'>
{sugar_number_format var=$fields.subtotal_amount.value }
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SHIPPING_AMOUNT' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="currency" field="shipping_amount" colspan='3' >

{if !$fields.shipping_amount.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.shipping_amount.name}'>
{sugar_number_format var=$fields.shipping_amount.value }
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SHIPPING_TAX_AMT' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="currency" field="shipping_tax_amt" colspan='3' >

{if !$fields.shipping_tax_amt.hidden}
{counter name="panelFieldCount" print=false}
<span id='shipping_tax_amt_span'>
{$fields.shipping_tax_amt.value}
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_TAX_AMOUNT' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="currency" field="tax_amount" colspan='3' >

{if !$fields.tax_amount.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.tax_amount.name}'>
{sugar_number_format var=$fields.tax_amount.value }
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_GRAND_TOTAL' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="currency" field="total_amount" colspan='3' >

{if !$fields.total_amount.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.total_amount.name}'>
{sugar_number_format var=$fields.total_amount.value }
</span>
{/if}

</div>


</div>

</div>
                    </div>
<div class="tab-pane-NOBOOTSTRAPTOGGLER fade" id='tab-content-2'>





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DATE_ENTERED' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="datetime" field="date_entered"  >

{if !$fields.date_entered.hidden}
{counter name="panelFieldCount" print=false}
<span id="date_entered" class="sugar_field">{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DATE_MODIFIED' module='AOS_Contracts'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="datetime" field="date_modified"  >

{if !$fields.date_modified.hidden}
{counter name="panelFieldCount" print=false}
<span id="date_modified" class="sugar_field">{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}</span>
{/if}

</div>


</div>

</div>
                    </div>
</div>

<div class="panel-content">
<div>&nbsp;</div>






</div>
</div>

</form>
<script>SUGAR.util.doWhen("document.getElementById('form') != null",
        function(){ldelim}SUGAR.util.buildAccessKeyLabels();{rdelim});
</script>            <script type="text/javascript" src="include/InlineEditing/inlineEditing.js"></script>
<script type="text/javascript" src="modules/Favorites/favorites.js"></script>
{literal}
<script type="text/javascript">

                    var selectTabDetailView = function(tab) {
                        $('#content div.tab-content div.tab-pane-NOBOOTSTRAPTOGGLER').hide();
                        $('#content div.tab-content div.tab-pane-NOBOOTSTRAPTOGGLER').eq(tab).show().addClass('active').addClass('in');
                    };

                    var selectTabOnError = function(tab) {
                        selectTabDetailView(tab);
                        $('#content ul.nav.nav-tabs > li').removeClass('active');
                        $('#content ul.nav.nav-tabs > li a').css('color', '');

                        $('#content ul.nav.nav-tabs > li').eq(tab).find('a').first().css('color', 'red');
                        $('#content ul.nav.nav-tabs > li').eq(tab).addClass('active');

                    };

                    var selectTabOnErrorInputHandle = function(inputHandle) {
                        var tab = $(inputHandle).closest('.tab-pane-NOBOOTSTRAPTOGGLER').attr('id').match(/^detailpanel_(.*)$/)[1];
                        selectTabOnError(tab);
                    };


                    $(function(){
                        $('#content ul.nav.nav-tabs > li > a[data-toggle="tab"]').click(function(e){
                            if(typeof $(this).parent().find('a').first().attr('id') != 'undefined') {
                                var tab = parseInt($(this).parent().find('a').first().attr('id').match(/^tab(.)*$/)[1]);
                                selectTabDetailView(tab);
                            }
                        });
                    });

                </script>
{/literal}