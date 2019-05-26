

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
{if $bean->aclAccess("edit")}<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button primary" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Opportunities'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Edit" id="edit_button" value="{$APP.LBL_EDIT_BUTTON_LABEL}">{/if} 
{if $bean->aclAccess("edit")}<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Opportunities'; _form.return_action.value='DetailView'; _form.isDuplicate.value=true; _form.action.value='EditView'; _form.return_id.value='{$id}';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}" id="duplicate_button">{/if} 
{if $bean->aclAccess("delete")}<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Opportunities'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('{$APP.NTC_DELETE_CONFIRMATION}')) SUGAR.ajaxUI.submitForm(_form); return false;" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}" id="delete_button">{/if} 
{if $bean->aclAccess("edit") && $bean->aclAccess("delete")}<input title="{$APP.LBL_DUP_MERGE}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Opportunities'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='Step1'; _form.module.value='MergeRecords';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Merge" value="{$APP.LBL_DUP_MERGE}" id="merge_duplicate_button">{/if} 
{if $bean->aclAccess("detail")}{if !empty($fields.id.value) && $isAuditEnabled}<input id="btn_view_change_log" title="{$APP.LNK_VIEW_CHANGE_LOG}" class="button" onclick='open_popup("Audit", "600", "400", "&record={$fields.id.value}&module_name=Opportunities", true, false,  {ldelim} "call_back_function":"set_return","form_name":"EditView","field_to_name_array":[] {rdelim} ); return false;' type="button" value="{$APP.LNK_VIEW_CHANGE_LOG}">{/if}{/if}
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

{if $config.enable_action_menu and $config.enable_action_menu != false}
<li role="presentation" class="active">
<a id="tab0" data-toggle="tab" class="hidden-xs">
{sugar_translate label='DEFAULT' module='Opportunities'}
</a>
<a id="xstab0" href="#" class="visible-xs first-tab dropdown-toggle" data-toggle="dropdown">
{sugar_translate label='DEFAULT' module='Opportunities'}
</a>
</li>




{/if}
{if $config.enable_action_menu and $config.enable_action_menu != false}
<li id="tab-actions" class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#">ACTIONS<span class="suitepicon suitepicon-action-caret"></span></a>
<ul class="dropdown-menu">
<li>{if $bean->aclAccess("edit")}<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button primary" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Opportunities'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Edit" id="edit_button" value="{$APP.LBL_EDIT_BUTTON_LABEL}">{/if} </li>
<li>{if $bean->aclAccess("edit")}<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Opportunities'; _form.return_action.value='DetailView'; _form.isDuplicate.value=true; _form.action.value='EditView'; _form.return_id.value='{$id}';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}" id="duplicate_button">{/if} </li>
<li>{if $bean->aclAccess("delete")}<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Opportunities'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('{$APP.NTC_DELETE_CONFIRMATION}')) SUGAR.ajaxUI.submitForm(_form); return false;" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}" id="delete_button">{/if} </li>
<li>{if $bean->aclAccess("edit") && $bean->aclAccess("delete")}<input title="{$APP.LBL_DUP_MERGE}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Opportunities'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='Step1'; _form.module.value='MergeRecords';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Merge" value="{$APP.LBL_DUP_MERGE}" id="merge_duplicate_button">{/if} </li>
<li>{if $bean->aclAccess("detail")}{if !empty($fields.id.value) && $isAuditEnabled}<input id="btn_view_change_log" title="{$APP.LNK_VIEW_CHANGE_LOG}" class="button" onclick='open_popup("Audit", "600", "400", "&record={$fields.id.value}&module_name=Opportunities", true, false,  {ldelim} "call_back_function":"set_return","form_name":"EditView","field_to_name_array":[] {rdelim} ); return false;' type="button" value="{$APP.LNK_VIEW_CHANGE_LOG}">{/if}{/if}</li>
</ul>        </li>
<li class="tab-inline-pagination">
{$PAGINATION}
</li>
{/if}
</ul>
<div class="clearfix"></div>

{if $config.enable_action_menu and $config.enable_action_menu != false}

<div class="tab-content">
{else}

<div class="tab-content" style="padding: 0; border: 0;">
{/if}


{if $config.enable_action_menu and $config.enable_action_menu != false}
<div class="tab-pane-NOBOOTSTRAPTOGGLER active fade in" id='tab-content-0'>





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_OPPORTUNITY_NAME' module='Opportunities'}{/capture}
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


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTACT_LEAD' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="contact_lead_c"  >

{if !$fields.contact_lead_c.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.contact_id_c.value)}
{capture assign="detail_url"}index.php?module=Contacts&action=DetailView&record={$fields.contact_id_c.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="contact_id_c" class="sugar_field" data-id-value="{$fields.contact_id_c.value}">{$fields.contact_lead_c.value}</span>
{if !empty($fields.contact_id_c.value)}</a>{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ASSIGNED_TO_NAME' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="assigned_user_name"  >

{if !$fields.assigned_user_name.hidden}
{counter name="panelFieldCount" print=false}

<span id="assigned_user_id" class="sugar_field" data-id-value="{$fields.assigned_user_id.value}">{$fields.assigned_user_name.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_USER_OPP_OWNER' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="user_opp_owner_c"  >

{if !$fields.user_opp_owner_c.hidden}
{counter name="panelFieldCount" print=false}

<span id="user_id_c" class="sugar_field" data-id-value="{$fields.user_id_c.value}">{$fields.user_opp_owner_c.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTRACT_DATE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="contract_date_c"  >

{if !$fields.contract_date_c.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.contract_date_c.value) <= 0}
{assign var="value" value=$fields.contract_date_c.default_value }
{else}
{assign var="value" value=$fields.contract_date_c.value }
{/if}
<span class="sugar_field" id="{$fields.contract_date_c.name}">{$value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_LEAD_TYPE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="lead_type"  >

{if !$fields.lead_type.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.lead_type.options)}
<input type="hidden" class="sugar_field" id="{$fields.lead_type.name}" value="{ $fields.lead_type.options }">
{ $fields.lead_type.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.lead_type.name}" value="{ $fields.lead_type.value }">
{ $fields.lead_type.options[$fields.lead_type.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DATE_CLOSED' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="date_closed"  >

{if !$fields.date_closed.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.date_closed.value) <= 0}
{assign var="value" value=$fields.date_closed.default_value }
{else}
{assign var="value" value=$fields.date_closed.value }
{/if}
<span class="sugar_field" id="{$fields.date_closed.name}">{$value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ONLINE_DATE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="online_date_c"  >

{if !$fields.online_date_c.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.online_date_c.value) <= 0}
{assign var="value" value=$fields.online_date_c.default_value }
{else}
{assign var="value" value=$fields.online_date_c.value }
{/if}
<span class="sugar_field" id="{$fields.online_date_c.name}">{$value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_FREE_TIME' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="free_time_c"  >

{if !$fields.free_time_c.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.free_time_c.name}">
{sugar_number_format precision=0 var=$fields.free_time_c.value}
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTRACT_TERM' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="contract_term_c"  >

{if !$fields.contract_term_c.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.contract_term_c.options)}
<input type="hidden" class="sugar_field" id="{$fields.contract_term_c.name}" value="{ $fields.contract_term_c.options }">
{ $fields.contract_term_c.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.contract_term_c.name}" value="{ $fields.contract_term_c.value }">
{ $fields.contract_term_c.options[$fields.contract_term_c.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SALES_STAGE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="sales_stage"  >

{if !$fields.sales_stage.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.sales_stage.options)}
<input type="hidden" class="sugar_field" id="{$fields.sales_stage.name}" value="{ $fields.sales_stage.options }">
{ $fields.sales_stage.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.sales_stage.name}" value="{ $fields.sales_stage.value }">
{ $fields.sales_stage.options[$fields.sales_stage.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_LEAD_SOURCE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="lead_source"  >

{if !$fields.lead_source.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.lead_source.options)}
<input type="hidden" class="sugar_field" id="{$fields.lead_source.name}" value="{ $fields.lead_source.options }">
{ $fields.lead_source.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.lead_source.name}" value="{ $fields.lead_source.value }">
{ $fields.lead_source.options[$fields.lead_source.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_INDUSTRY' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="industry_c"  >

{if !$fields.industry_c.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.industry_c.options)}
<input type="hidden" class="sugar_field" id="{$fields.industry_c.name}" value="{ $fields.industry_c.options }">
{ $fields.industry_c.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.industry_c.name}" value="{ $fields.industry_c.value }">
{ $fields.industry_c.options[$fields.industry_c.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CAMPAIGN' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="campaign_name"  >

{if !$fields.campaign_name.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.campaign_id.value)}
{capture assign="detail_url"}index.php?module=Campaigns&action=DetailView&record={$fields.campaign_id.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="campaign_id" class="sugar_field" data-id-value="{$fields.campaign_id.value}">{$fields.campaign_name.value}</span>
{if !empty($fields.campaign_id.value)}</a>{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_PROJECT_STATUS' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="project_status"  >

{if !$fields.project_status.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.project_status.options)}
<input type="hidden" class="sugar_field" id="{$fields.project_status.name}" value="{ $fields.project_status.options }">
{ $fields.project_status.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.project_status.name}" value="{ $fields.project_status.value }">
{ $fields.project_status.options[$fields.project_status.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">
</div>

<div class="clear"></div>
</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_NEXT_STEP' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="next_step"  >

{if !$fields.next_step.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.next_step.value) <= 0}
{assign var="value" value=$fields.next_step.default_value }
{else}
{assign var="value" value=$fields.next_step.value }
{/if} 
<span class="sugar_field" id="{$fields.next_step.name}">{$fields.next_step.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_PROBABILITY' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="probability"  >

{if !$fields.probability.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.probability.name}">
{sugar_number_format precision=0 var=$fields.probability.value}
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_NAME' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="relate" field="account_name" colspan='3' >

{if !$fields.account_name.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.account_id.value)}
{capture assign="detail_url"}index.php?module=Accounts&action=DetailView&record={$fields.account_id.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="account_id" class="sugar_field" data-id-value="{$fields.account_id.value}">{$fields.account_name.value}</span>
{if !empty($fields.account_id.value)}</a>{/if}
{/if}

</div>


</div>

</div>
                        </div>
{else}

<div class="tab-pane-NOBOOTSTRAPTOGGLER panel-collapse"></div>
{/if}
</div>

<div class="panel-content">
<div>&nbsp;</div>





{if $config.enable_action_menu and $config.enable_action_menu != false}

{else}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel--1" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='DEFAULT' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel--1" data-id="DEFAULT">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_OPPORTUNITY_NAME' module='Opportunities'}{/capture}
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


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTACT_LEAD' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="contact_lead_c"  >

{if !$fields.contact_lead_c.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.contact_id_c.value)}
{capture assign="detail_url"}index.php?module=Contacts&action=DetailView&record={$fields.contact_id_c.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="contact_id_c" class="sugar_field" data-id-value="{$fields.contact_id_c.value}">{$fields.contact_lead_c.value}</span>
{if !empty($fields.contact_id_c.value)}</a>{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ASSIGNED_TO_NAME' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="assigned_user_name"  >

{if !$fields.assigned_user_name.hidden}
{counter name="panelFieldCount" print=false}

<span id="assigned_user_id" class="sugar_field" data-id-value="{$fields.assigned_user_id.value}">{$fields.assigned_user_name.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_USER_OPP_OWNER' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="user_opp_owner_c"  >

{if !$fields.user_opp_owner_c.hidden}
{counter name="panelFieldCount" print=false}

<span id="user_id_c" class="sugar_field" data-id-value="{$fields.user_id_c.value}">{$fields.user_opp_owner_c.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTRACT_DATE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="contract_date_c"  >

{if !$fields.contract_date_c.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.contract_date_c.value) <= 0}
{assign var="value" value=$fields.contract_date_c.default_value }
{else}
{assign var="value" value=$fields.contract_date_c.value }
{/if}
<span class="sugar_field" id="{$fields.contract_date_c.name}">{$value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_LEAD_TYPE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="lead_type"  >

{if !$fields.lead_type.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.lead_type.options)}
<input type="hidden" class="sugar_field" id="{$fields.lead_type.name}" value="{ $fields.lead_type.options }">
{ $fields.lead_type.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.lead_type.name}" value="{ $fields.lead_type.value }">
{ $fields.lead_type.options[$fields.lead_type.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DATE_CLOSED' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="date_closed"  >

{if !$fields.date_closed.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.date_closed.value) <= 0}
{assign var="value" value=$fields.date_closed.default_value }
{else}
{assign var="value" value=$fields.date_closed.value }
{/if}
<span class="sugar_field" id="{$fields.date_closed.name}">{$value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ONLINE_DATE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="date" field="online_date_c"  >

{if !$fields.online_date_c.hidden}
{counter name="panelFieldCount" print=false}


{if strlen($fields.online_date_c.value) <= 0}
{assign var="value" value=$fields.online_date_c.default_value }
{else}
{assign var="value" value=$fields.online_date_c.value }
{/if}
<span class="sugar_field" id="{$fields.online_date_c.name}">{$value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_FREE_TIME' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="free_time_c"  >

{if !$fields.free_time_c.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.free_time_c.name}">
{sugar_number_format precision=0 var=$fields.free_time_c.value}
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CONTRACT_TERM' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="contract_term_c"  >

{if !$fields.contract_term_c.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.contract_term_c.options)}
<input type="hidden" class="sugar_field" id="{$fields.contract_term_c.name}" value="{ $fields.contract_term_c.options }">
{ $fields.contract_term_c.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.contract_term_c.name}" value="{ $fields.contract_term_c.value }">
{ $fields.contract_term_c.options[$fields.contract_term_c.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SALES_STAGE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="sales_stage"  >

{if !$fields.sales_stage.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.sales_stage.options)}
<input type="hidden" class="sugar_field" id="{$fields.sales_stage.name}" value="{ $fields.sales_stage.options }">
{ $fields.sales_stage.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.sales_stage.name}" value="{ $fields.sales_stage.value }">
{ $fields.sales_stage.options[$fields.sales_stage.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_LEAD_SOURCE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="lead_source"  >

{if !$fields.lead_source.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.lead_source.options)}
<input type="hidden" class="sugar_field" id="{$fields.lead_source.name}" value="{ $fields.lead_source.options }">
{ $fields.lead_source.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.lead_source.name}" value="{ $fields.lead_source.value }">
{ $fields.lead_source.options[$fields.lead_source.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_INDUSTRY' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="industry_c"  >

{if !$fields.industry_c.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.industry_c.options)}
<input type="hidden" class="sugar_field" id="{$fields.industry_c.name}" value="{ $fields.industry_c.options }">
{ $fields.industry_c.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.industry_c.name}" value="{ $fields.industry_c.value }">
{ $fields.industry_c.options[$fields.industry_c.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CAMPAIGN' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="campaign_name"  >

{if !$fields.campaign_name.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.campaign_id.value)}
{capture assign="detail_url"}index.php?module=Campaigns&action=DetailView&record={$fields.campaign_id.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="campaign_id" class="sugar_field" data-id-value="{$fields.campaign_id.value}">{$fields.campaign_name.value}</span>
{if !empty($fields.campaign_id.value)}</a>{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_PROJECT_STATUS' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="project_status"  >

{if !$fields.project_status.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.project_status.options)}
<input type="hidden" class="sugar_field" id="{$fields.project_status.name}" value="{ $fields.project_status.options }">
{ $fields.project_status.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.project_status.name}" value="{ $fields.project_status.value }">
{ $fields.project_status.options[$fields.project_status.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">
</div>

<div class="clear"></div>
</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_NEXT_STEP' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="next_step"  >

{if !$fields.next_step.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.next_step.value) <= 0}
{assign var="value" value=$fields.next_step.default_value }
{else}
{assign var="value" value=$fields.next_step.value }
{/if} 
<span class="sugar_field" id="{$fields.next_step.name}">{$fields.next_step.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_PROBABILITY' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="probability"  >

{if !$fields.probability.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.probability.name}">
{sugar_number_format precision=0 var=$fields.probability.value}
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_NAME' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="relate" field="account_name" colspan='3' >

{if !$fields.account_name.hidden}
{counter name="panelFieldCount" print=false}

{if !empty($fields.account_id.value)}
{capture assign="detail_url"}index.php?module=Accounts&action=DetailView&record={$fields.account_id.value}{/capture}
<a href="{sugar_ajax_url url=$detail_url}">{/if}
<span id="account_id" class="sugar_field" data-id-value="{$fields.account_id.value}">{$fields.account_name.value}</span>
{if !empty($fields.account_id.value)}</a>{/if}
{/if}

</div>


</div>

</div>
                            </div>
</div>
</div>
{/if}





{if $config.enable_action_menu and $config.enable_action_menu != false}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel-0" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='LBL_EDITVIEW_PANEL3' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-0"  data-id="LBL_EDITVIEW_PANEL3">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_NAME' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="lead_account_name"  >

{if !$fields.lead_account_name.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.lead_account_name.value) <= 0}
{assign var="value" value=$fields.lead_account_name.default_value }
{else}
{assign var="value" value=$fields.lead_account_name.value }
{/if} 
<span class="sugar_field" id="{$fields.lead_account_name.name}">{$fields.lead_account_name.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_TYPE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="account_type"  >

{if !$fields.account_type.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.account_type.options)}
<input type="hidden" class="sugar_field" id="{$fields.account_type.name}" value="{ $fields.account_type.options }">
{ $fields.account_type.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.account_type.name}" value="{ $fields.account_type.value }">
{ $fields.account_type.options[$fields.account_type.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_PHONE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="account_phone"  >

{if !$fields.account_phone.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.account_phone.name}">
{sugar_number_format precision=0 var=$fields.account_phone.value}
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_EMAIL' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="account_email"  >

{if !$fields.account_email.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.account_email.value) <= 0}
{assign var="value" value=$fields.account_email.default_value }
{else}
{assign var="value" value=$fields.account_email.value }
{/if} 
<span class="sugar_field" id="{$fields.account_email.name}">{$fields.account_email.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_DESCRIPTION' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="text" field="account_description"  >

{if !$fields.account_description.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.account_description.name|escape:'html'|url2html|nl2br}">{$fields.account_description.value|escape:'html'|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">
</div>

<div class="clear"></div>
</div>
                                </div>
</div>
</div>
{else}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel-0" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='LBL_EDITVIEW_PANEL3' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-0" data-id="LBL_EDITVIEW_PANEL3">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_NAME' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="lead_account_name"  >

{if !$fields.lead_account_name.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.lead_account_name.value) <= 0}
{assign var="value" value=$fields.lead_account_name.default_value }
{else}
{assign var="value" value=$fields.lead_account_name.value }
{/if} 
<span class="sugar_field" id="{$fields.lead_account_name.name}">{$fields.lead_account_name.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_TYPE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="account_type"  >

{if !$fields.account_type.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.account_type.options)}
<input type="hidden" class="sugar_field" id="{$fields.account_type.name}" value="{ $fields.account_type.options }">
{ $fields.account_type.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.account_type.name}" value="{ $fields.account_type.value }">
{ $fields.account_type.options[$fields.account_type.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_PHONE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="account_phone"  >

{if !$fields.account_phone.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.account_phone.name}">
{sugar_number_format precision=0 var=$fields.account_phone.value}
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_EMAIL' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="account_email"  >

{if !$fields.account_email.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.account_email.value) <= 0}
{assign var="value" value=$fields.account_email.default_value }
{else}
{assign var="value" value=$fields.account_email.value }
{/if} 
<span class="sugar_field" id="{$fields.account_email.name}">{$fields.account_email.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_DESCRIPTION' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="text" field="account_description"  >

{if !$fields.account_description.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.account_description.name|escape:'html'|url2html|nl2br}">{$fields.account_description.value|escape:'html'|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">
</div>

<div class="clear"></div>
</div>
                            </div>
</div>
</div>
{/if}





{if $config.enable_action_menu and $config.enable_action_menu != false}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel-1" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='LBL_EDITVIEW_PANEL2' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-1"  data-id="LBL_EDITVIEW_PANEL2">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='Logo Design' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="logo_design"  >

{if !$fields.logo_design.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.logo_design.name}">
{sugar_number_format precision=0 var=$fields.logo_design.value}
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_WEBSITE_DESIGN_AMOUNT' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="currency" field="website_design_amount_c"  >

{if !$fields.website_design_amount_c.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.website_design_amount_c.name}'>
{sugar_number_format var=$fields.website_design_amount_c.value }
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SOCIAL_ENGAGEMENT' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="varchar" field="social_engagement_c" colspan='3' >

{if !$fields.social_engagement_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.social_engagement_c.value) <= 0}
{assign var="value" value=$fields.social_engagement_c.default_value }
{else}
{assign var="value" value=$fields.social_engagement_c.value }
{/if} 
<span class="sugar_field" id="{$fields.social_engagement_c.name}">{$fields.social_engagement_c.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_EMAIL_MRR' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="email_mrr_c"  >

{if !$fields.email_mrr_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.email_mrr_c.value) <= 0}
{assign var="value" value=$fields.email_mrr_c.default_value }
{else}
{assign var="value" value=$fields.email_mrr_c.value }
{/if} 
<span class="sugar_field" id="{$fields.email_mrr_c.name}">{$fields.email_mrr_c.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_HOSTING_MRR' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="hosting_mrr_c"  >

{if !$fields.hosting_mrr_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.hosting_mrr_c.value) <= 0}
{assign var="value" value=$fields.hosting_mrr_c.default_value }
{else}
{assign var="value" value=$fields.hosting_mrr_c.value }
{/if} 
<span class="sugar_field" id="{$fields.hosting_mrr_c.name}">{$fields.hosting_mrr_c.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_PAYMENT_NOTES' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="payment_notes_c" colspan='3' >

{if !$fields.payment_notes_c.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.payment_notes_c.name|escape:'html'|url2html|nl2br}">{$fields.payment_notes_c.value|escape:'html'|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SETUP_FEE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="varchar" field="setup_fee_c" colspan='3' >

{if !$fields.setup_fee_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.setup_fee_c.value) <= 0}
{assign var="value" value=$fields.setup_fee_c.default_value }
{else}
{assign var="value" value=$fields.setup_fee_c.value }
{/if} 
<span class="sugar_field" id="{$fields.setup_fee_c.name}">{$fields.setup_fee_c.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DESCRIPTION' module='Opportunities'}{/capture}
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


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_WEBSITE_DESIGN_DESCRIPTION_C' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="website_design_description_c" colspan='3' >

{if !$fields.website_design_description_c.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.website_design_description_c.name|escape:'html'|url2html|nl2br}">{$fields.website_design_description_c.value|escape:'html'|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


&nbsp;
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="" field="" colspan='3' >

{if !$fields.totalCountRow12.hidden}
{counter name="panelFieldCount" print=false}
<span id="totalCountRow12" class="sugar_field"><input type="hidden" name="editRowsCount" id="editRowsCount" value="{$rowCountEdit}">
<input type="hidden" name="editViewData" id="editViewData" value="{$dataRowEdit}">
<input type="hidden" name="totalCountRow" id="totalCountRow" value="{$rowCountEdit}">
<input type="hidden" name="outSourceDom" id="outSourceDom" value="{$outSourceDom}">
<input type="hidden" name="detailView" id="detailView" value="{$detailView}">
<input type="hidden" name="rowData" id="rowData" value="{$dataRowEdit}"></span>
{/if}

</div>


</div>

</div>
                                </div>
</div>
</div>
{else}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel-1" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='LBL_EDITVIEW_PANEL2' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-1" data-id="LBL_EDITVIEW_PANEL2">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='Logo Design' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="logo_design"  >

{if !$fields.logo_design.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.logo_design.name}">
{sugar_number_format precision=0 var=$fields.logo_design.value}
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_WEBSITE_DESIGN_AMOUNT' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="currency" field="website_design_amount_c"  >

{if !$fields.website_design_amount_c.hidden}
{counter name="panelFieldCount" print=false}

<span id='{$fields.website_design_amount_c.name}'>
{sugar_number_format var=$fields.website_design_amount_c.value }
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SOCIAL_ENGAGEMENT' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="varchar" field="social_engagement_c" colspan='3' >

{if !$fields.social_engagement_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.social_engagement_c.value) <= 0}
{assign var="value" value=$fields.social_engagement_c.default_value }
{else}
{assign var="value" value=$fields.social_engagement_c.value }
{/if} 
<span class="sugar_field" id="{$fields.social_engagement_c.name}">{$fields.social_engagement_c.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_EMAIL_MRR' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="email_mrr_c"  >

{if !$fields.email_mrr_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.email_mrr_c.value) <= 0}
{assign var="value" value=$fields.email_mrr_c.default_value }
{else}
{assign var="value" value=$fields.email_mrr_c.value }
{/if} 
<span class="sugar_field" id="{$fields.email_mrr_c.name}">{$fields.email_mrr_c.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_HOSTING_MRR' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="hosting_mrr_c"  >

{if !$fields.hosting_mrr_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.hosting_mrr_c.value) <= 0}
{assign var="value" value=$fields.hosting_mrr_c.default_value }
{else}
{assign var="value" value=$fields.hosting_mrr_c.value }
{/if} 
<span class="sugar_field" id="{$fields.hosting_mrr_c.name}">{$fields.hosting_mrr_c.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_PAYMENT_NOTES' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="payment_notes_c" colspan='3' >

{if !$fields.payment_notes_c.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.payment_notes_c.name|escape:'html'|url2html|nl2br}">{$fields.payment_notes_c.value|escape:'html'|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SETUP_FEE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="varchar" field="setup_fee_c" colspan='3' >

{if !$fields.setup_fee_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.setup_fee_c.value) <= 0}
{assign var="value" value=$fields.setup_fee_c.default_value }
{else}
{assign var="value" value=$fields.setup_fee_c.value }
{/if} 
<span class="sugar_field" id="{$fields.setup_fee_c.name}">{$fields.setup_fee_c.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DESCRIPTION' module='Opportunities'}{/capture}
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


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_WEBSITE_DESIGN_DESCRIPTION_C' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="website_design_description_c" colspan='3' >

{if !$fields.website_design_description_c.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.website_design_description_c.name|escape:'html'|url2html|nl2br}">{$fields.website_design_description_c.value|escape:'html'|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


&nbsp;
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="" field="" colspan='3' >

{if !$fields.totalCountRow12.hidden}
{counter name="panelFieldCount" print=false}
<span id="totalCountRow12" class="sugar_field"><input type="hidden" name="editRowsCount" id="editRowsCount" value="{$rowCountEdit}">
<input type="hidden" name="editViewData" id="editViewData" value="{$dataRowEdit}">
<input type="hidden" name="totalCountRow" id="totalCountRow" value="{$rowCountEdit}">
<input type="hidden" name="outSourceDom" id="outSourceDom" value="{$outSourceDom}">
<input type="hidden" name="detailView" id="detailView" value="{$detailView}">
<input type="hidden" name="rowData" id="rowData" value="{$dataRowEdit}"></span>
{/if}

</div>


</div>

</div>
                            </div>
</div>
</div>
{/if}





{if $config.enable_action_menu and $config.enable_action_menu != false}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel-2" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='LBL_EDITVIEW_PANEL4' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-2"  data-id="LBL_EDITVIEW_PANEL4">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ROW_COUNT' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="totalCountRow"  >

{if !$fields.totalCountRow.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.totalCountRow.name}">
{sugar_number_format precision=0 var=$fields.totalCountRow.value}
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_TERM' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="term_c"  >

{if !$fields.term_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.term_c.value) <= 0}
{assign var="value" value=$fields.term_c.default_value }
{else}
{assign var="value" value=$fields.term_c.value }
{/if} 
<span class="sugar_field" id="{$fields.term_c.name}">{$fields.term_c.value}</span>
{/if}

</div>


</div>

</div>
                                </div>
</div>
</div>
{else}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel-2" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='LBL_EDITVIEW_PANEL4' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-2" data-id="LBL_EDITVIEW_PANEL4">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ROW_COUNT' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="totalCountRow"  >

{if !$fields.totalCountRow.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.totalCountRow.name}">
{sugar_number_format precision=0 var=$fields.totalCountRow.value}
</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_TERM' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="term_c"  >

{if !$fields.term_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.term_c.value) <= 0}
{assign var="value" value=$fields.term_c.default_value }
{else}
{assign var="value" value=$fields.term_c.value }
{/if} 
<span class="sugar_field" id="{$fields.term_c.name}">{$fields.term_c.value}</span>
{/if}

</div>


</div>

</div>
                            </div>
</div>
</div>
{/if}





{if $config.enable_action_menu and $config.enable_action_menu != false}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel-3" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='LBL_EDITVIEW_PANEL1' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-3"  data-id="LBL_EDITVIEW_PANEL1">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_MANAGER' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="enum" field="account_manager_c" colspan='3' >

{if !$fields.account_manager_c.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.account_manager_c.options)}
<input type="hidden" class="sugar_field" id="{$fields.account_manager_c.name}" value="{ $fields.account_manager_c.options }">
{ $fields.account_manager_c.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.account_manager_c.name}" value="{ $fields.account_manager_c.value }">
{ $fields.account_manager_c.options[$fields.account_manager_c.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CHURNED' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="bool" field="churned_c"  >

{if !$fields.churned_c.hidden}
{counter name="panelFieldCount" print=false}

{if strval($fields.churned_c.value) == "1" || strval($fields.churned_c.value) == "yes" || strval($fields.churned_c.value) == "on"} 
{assign var="checked" value='checked="checked"'}
{else}
{assign var="checked" value=""}
{/if}
<input type="checkbox" class="checkbox" name="{$fields.churned_c.name}" id="{$fields.churned_c.name}" value="$fields.churned_c.value" disabled="true" {$checked}>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ON_BOARDING_INCOMPLETE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="bool" field="on_boarding_incomplete_c"  >

{if !$fields.on_boarding_incomplete_c.hidden}
{counter name="panelFieldCount" print=false}

{if strval($fields.on_boarding_incomplete_c.value) == "1" || strval($fields.on_boarding_incomplete_c.value) == "yes" || strval($fields.on_boarding_incomplete_c.value) == "on"} 
{assign var="checked" value='checked="checked"'}
{else}
{assign var="checked" value=""}
{/if}
<input type="checkbox" class="checkbox" name="{$fields.on_boarding_incomplete_c.name}" id="{$fields.on_boarding_incomplete_c.name}" value="$fields.on_boarding_incomplete_c.value" disabled="true" {$checked}>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ON_BOARDING_INCOMPLETE_NOTES' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="on_boarding_incomplete_notes_c"  >

{if !$fields.on_boarding_incomplete_notes_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.on_boarding_incomplete_notes_c.value) <= 0}
{assign var="value" value=$fields.on_boarding_incomplete_notes_c.default_value }
{else}
{assign var="value" value=$fields.on_boarding_incomplete_notes_c.value }
{/if} 
<span class="sugar_field" id="{$fields.on_boarding_incomplete_notes_c.name}">{$fields.on_boarding_incomplete_notes_c.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_LEAD_ID' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="custom_lead_id"  >

{if !$fields.custom_lead_id.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.custom_lead_id.value) <= 0}
{assign var="value" value=$fields.custom_lead_id.default_value }
{else}
{assign var="value" value=$fields.custom_lead_id.value }
{/if} 
<span class="sugar_field" id="{$fields.custom_lead_id.name}">{$fields.custom_lead_id.value}</span>
{/if}

</div>


</div>

</div>
                                </div>
</div>
</div>
{else}

<div class="panel panel-default">
<div class="panel-heading ">
<a class="" role="button" data-toggle="collapse" href="#top-panel-3" aria-expanded="false">
<div class="col-xs-10 col-sm-11 col-md-11">
{sugar_translate label='LBL_EDITVIEW_PANEL1' module='Opportunities'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-3" data-id="LBL_EDITVIEW_PANEL1">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_MANAGER' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="enum" field="account_manager_c" colspan='3' >

{if !$fields.account_manager_c.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.account_manager_c.options)}
<input type="hidden" class="sugar_field" id="{$fields.account_manager_c.name}" value="{ $fields.account_manager_c.options }">
{ $fields.account_manager_c.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.account_manager_c.name}" value="{ $fields.account_manager_c.value }">
{ $fields.account_manager_c.options[$fields.account_manager_c.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CHURNED' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="bool" field="churned_c"  >

{if !$fields.churned_c.hidden}
{counter name="panelFieldCount" print=false}

{if strval($fields.churned_c.value) == "1" || strval($fields.churned_c.value) == "yes" || strval($fields.churned_c.value) == "on"} 
{assign var="checked" value='checked="checked"'}
{else}
{assign var="checked" value=""}
{/if}
<input type="checkbox" class="checkbox" name="{$fields.churned_c.name}" id="{$fields.churned_c.name}" value="$fields.churned_c.value" disabled="true" {$checked}>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ON_BOARDING_INCOMPLETE' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="bool" field="on_boarding_incomplete_c"  >

{if !$fields.on_boarding_incomplete_c.hidden}
{counter name="panelFieldCount" print=false}

{if strval($fields.on_boarding_incomplete_c.value) == "1" || strval($fields.on_boarding_incomplete_c.value) == "yes" || strval($fields.on_boarding_incomplete_c.value) == "on"} 
{assign var="checked" value='checked="checked"'}
{else}
{assign var="checked" value=""}
{/if}
<input type="checkbox" class="checkbox" name="{$fields.on_boarding_incomplete_c.name}" id="{$fields.on_boarding_incomplete_c.name}" value="$fields.on_boarding_incomplete_c.value" disabled="true" {$checked}>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ON_BOARDING_INCOMPLETE_NOTES' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="on_boarding_incomplete_notes_c"  >

{if !$fields.on_boarding_incomplete_notes_c.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.on_boarding_incomplete_notes_c.value) <= 0}
{assign var="value" value=$fields.on_boarding_incomplete_notes_c.default_value }
{else}
{assign var="value" value=$fields.on_boarding_incomplete_notes_c.value }
{/if} 
<span class="sugar_field" id="{$fields.on_boarding_incomplete_notes_c.name}">{$fields.on_boarding_incomplete_notes_c.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_LEAD_ID' module='Opportunities'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="custom_lead_id"  >

{if !$fields.custom_lead_id.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.custom_lead_id.value) <= 0}
{assign var="value" value=$fields.custom_lead_id.default_value }
{else}
{assign var="value" value=$fields.custom_lead_id.value }
{/if} 
<span class="sugar_field" id="{$fields.custom_lead_id.name}">{$fields.custom_lead_id.value}</span>
{/if}

</div>


</div>

</div>
                            </div>
</div>
</div>
{/if}
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