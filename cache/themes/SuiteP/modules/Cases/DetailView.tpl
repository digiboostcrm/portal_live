

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
{if $bean->aclAccess("edit")}<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button primary" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Cases'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Edit" id="edit_button" value="{$APP.LBL_EDIT_BUTTON_LABEL}">{/if} 
{if $bean->aclAccess("edit")}<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Cases'; _form.return_action.value='DetailView'; _form.isDuplicate.value=true; _form.action.value='EditView'; _form.return_id.value='{$id}';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}" id="duplicate_button">{/if} 
{if $bean->aclAccess("delete")}<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Cases'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('{$APP.NTC_DELETE_CONFIRMATION}')) SUGAR.ajaxUI.submitForm(_form); return false;" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}" id="delete_button">{/if} 
{if $bean->aclAccess("edit") && $bean->aclAccess("delete")}<input title="{$APP.LBL_DUP_MERGE}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Cases'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='Step1'; _form.module.value='MergeRecords';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Merge" value="{$APP.LBL_DUP_MERGE}" id="merge_duplicate_button">{/if} 
{if $bean->aclAccess("detail")}{if !empty($fields.id.value) && $isAuditEnabled}<input id="btn_view_change_log" title="{$APP.LNK_VIEW_CHANGE_LOG}" class="button" onclick='open_popup("Audit", "600", "400", "&record={$fields.id.value}&module_name=Cases", true, false,  {ldelim} "call_back_function":"set_return","form_name":"EditView","field_to_name_array":[] {rdelim} ); return false;' type="button" value="{$APP.LNK_VIEW_CHANGE_LOG}">{/if}{/if}
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
{sugar_translate label='LBL_CASE_INFORMATION' module='Cases'}
</a>
<a id="xstab0" href="#" class="visible-xs first-tab dropdown-toggle" data-toggle="dropdown">
{sugar_translate label='LBL_CASE_INFORMATION' module='Cases'}
</a>
</li>

{/if}
{if $config.enable_action_menu and $config.enable_action_menu != false}
<li id="tab-actions" class="dropdown">
<a class="dropdown-toggle" data-toggle="dropdown" href="#">ACTIONS<span class="suitepicon suitepicon-action-caret"></span></a>
<ul class="dropdown-menu">
<li>{if $bean->aclAccess("edit")}<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="button primary" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Cases'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='EditView';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Edit" id="edit_button" value="{$APP.LBL_EDIT_BUTTON_LABEL}">{/if} </li>
<li>{if $bean->aclAccess("edit")}<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Cases'; _form.return_action.value='DetailView'; _form.isDuplicate.value=true; _form.action.value='EditView'; _form.return_id.value='{$id}';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}" id="duplicate_button">{/if} </li>
<li>{if $bean->aclAccess("delete")}<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Cases'; _form.return_action.value='ListView'; _form.action.value='Delete'; if(confirm('{$APP.NTC_DELETE_CONFIRMATION}')) SUGAR.ajaxUI.submitForm(_form); return false;" type="submit" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}" id="delete_button">{/if} </li>
<li>{if $bean->aclAccess("edit") && $bean->aclAccess("delete")}<input title="{$APP.LBL_DUP_MERGE}" class="button" onclick="var _form = document.getElementById('formDetailView'); _form.return_module.value='Cases'; _form.return_action.value='DetailView'; _form.return_id.value='{$id}'; _form.action.value='Step1'; _form.module.value='MergeRecords';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Merge" value="{$APP.LBL_DUP_MERGE}" id="merge_duplicate_button">{/if} </li>
<li>{if $bean->aclAccess("detail")}{if !empty($fields.id.value) && $isAuditEnabled}<input id="btn_view_change_log" title="{$APP.LNK_VIEW_CHANGE_LOG}" class="button" onclick='open_popup("Audit", "600", "400", "&record={$fields.id.value}&module_name=Cases", true, false,  {ldelim} "call_back_function":"set_return","form_name":"EditView","field_to_name_array":[] {rdelim} ); return false;' type="button" value="{$APP.LNK_VIEW_CHANGE_LOG}">{/if}{/if}</li>
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


{capture name="label" assign="label"}{sugar_translate label='LBL_NUMBER' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="case_number"  >

{if !$fields.case_number.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.case_number.value) <= 0}
{assign var="value" value=$fields.case_number.default_value }
{else}
{assign var="value" value=$fields.case_number.value }
{/if} 
<span class="sugar_field" id="{$fields.case_number.name}">{$fields.case_number.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_NAME' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="account_name"  >

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


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SUBJECT' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="subject"  >

{if !$fields.subject.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.subject.options)}
<input type="hidden" class="sugar_field" id="{$fields.subject.name}" value="{ $fields.subject.options }">
{ $fields.subject.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.subject.name}" value="{ $fields.subject.value }">
{ $fields.subject.options[$fields.subject.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_PRIORITY' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="priority"  >

{if !$fields.priority.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.priority.options)}
<input type="hidden" class="sugar_field" id="{$fields.priority.name}" value="{ $fields.priority.options }">
{ $fields.priority.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.priority.name}" value="{ $fields.priority.value }">
{ $fields.priority.options[$fields.priority.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CATEGORY' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="category"  >

{if !$fields.category.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.category.options)}
<input type="hidden" class="sugar_field" id="{$fields.category.name}" value="{ $fields.category.options }">
{ $fields.category.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.category.name}" value="{ $fields.category.value }">
{ $fields.category.options[$fields.category.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_STATUS' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="dynamicenum" field="status"  >

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


{capture name="label" assign="label"}{sugar_translate label='LBL_START_DATE' module='Cases'}{/capture}
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


{capture name="label" assign="label"}{sugar_translate label='LBL_DUE_DATE' module='Cases'}{/capture}
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

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DESCRIPTION' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="description" colspan='3' >

{if !$fields.description.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.description.name|url2html|nl2br}">{$fields.description.value|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CUSTOM_ATTACHMENT' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="Multiupload" field="custom_attachment_c" colspan='3' >

{if !$fields.custom_attachment_c.hidden}
{counter name="panelFieldCount" print=false}
{if strlen($fields.custom_attachment_c.value) <= 0}
{assign var="value" value=$fields.custom_attachment_c.default_value }
{else}
{assign var="value" value=$fields.custom_attachment_c.value }
{/if}
{assign var=files value=":"|explode:$value}
{assign var="record_id" value=$fields.id.value}
<ul>
{foreach from=$files item=file}
{if !empty($file)}
<li>
<a href="index.php?entryPoint=download2&file_path=multi_{$record_id}&field_name={$fields.custom_attachment_c.name}&file_name={$file|urlencode}" target="_blank">{$file}</a>
</li>
{/if}
{/foreach}
</ul>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ASSIGNED_TO_NAME' module='Cases'}{/capture}
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


{capture name="label" assign="label"}{sugar_translate label='LBL_CREATED_BY_NAME' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="created_by_name"  >

{if !$fields.created_by_name.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.created_by_name.value) <= 0}
{assign var="value" value=$fields.created_by_name.default_value }
{else}
{assign var="value" value=$fields.created_by_name.value }
{/if} 
<span class="sugar_field" id="{$fields.created_by_name.name}">{$fields.created_by_name.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SPENT_MINUTS' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="int" field="total_spent_minuts" colspan='3' >

{if !$fields.total_spent_minuts.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.total_spent_minuts.name}">
{sugar_number_format precision=0 var=$fields.total_spent_minuts.value}
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_TIME_CATEGORY' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="enum" field="time_category" colspan='3' >

{if !$fields.time_category.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.time_category.options)}
<input type="hidden" class="sugar_field" id="{$fields.time_category.name}" value="{ $fields.time_category.options }">
{ $fields.time_category.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.time_category.name}" value="{ $fields.time_category.value }">
{ $fields.time_category.options[$fields.time_category.value]}
{/if}
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
{sugar_translate label='LBL_CASE_INFORMATION' module='Cases'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel--1" data-id="LBL_CASE_INFORMATION">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_NUMBER' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="int" field="case_number"  >

{if !$fields.case_number.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.case_number.value) <= 0}
{assign var="value" value=$fields.case_number.default_value }
{else}
{assign var="value" value=$fields.case_number.value }
{/if} 
<span class="sugar_field" id="{$fields.case_number.name}">{$fields.case_number.value}</span>
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ACCOUNT_NAME' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="relate" field="account_name"  >

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


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SUBJECT' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="subject"  >

{if !$fields.subject.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.subject.options)}
<input type="hidden" class="sugar_field" id="{$fields.subject.name}" value="{ $fields.subject.options }">
{ $fields.subject.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.subject.name}" value="{ $fields.subject.value }">
{ $fields.subject.options[$fields.subject.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_PRIORITY' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="priority"  >

{if !$fields.priority.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.priority.options)}
<input type="hidden" class="sugar_field" id="{$fields.priority.name}" value="{ $fields.priority.options }">
{ $fields.priority.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.priority.name}" value="{ $fields.priority.value }">
{ $fields.priority.options[$fields.priority.value]}
{/if}
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CATEGORY' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="enum" field="category"  >

{if !$fields.category.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.category.options)}
<input type="hidden" class="sugar_field" id="{$fields.category.name}" value="{ $fields.category.options }">
{ $fields.category.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.category.name}" value="{ $fields.category.value }">
{ $fields.category.options[$fields.category.value]}
{/if}
{/if}

</div>


</div>




<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-2-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_STATUS' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="dynamicenum" field="status"  >

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


{capture name="label" assign="label"}{sugar_translate label='LBL_START_DATE' module='Cases'}{/capture}
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


{capture name="label" assign="label"}{sugar_translate label='LBL_DUE_DATE' module='Cases'}{/capture}
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

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_DESCRIPTION' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="description" colspan='3' >

{if !$fields.description.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.description.name|url2html|nl2br}">{$fields.description.value|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_CUSTOM_ATTACHMENT' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="Multiupload" field="custom_attachment_c" colspan='3' >

{if !$fields.custom_attachment_c.hidden}
{counter name="panelFieldCount" print=false}
{if strlen($fields.custom_attachment_c.value) <= 0}
{assign var="value" value=$fields.custom_attachment_c.default_value }
{else}
{assign var="value" value=$fields.custom_attachment_c.value }
{/if}
{assign var=files value=":"|explode:$value}
{assign var="record_id" value=$fields.id.value}
<ul>
{foreach from=$files item=file}
{if !empty($file)}
<li>
<a href="index.php?entryPoint=download2&file_path=multi_{$record_id}&field_name={$fields.custom_attachment_c.name}&file_name={$file|urlencode}" target="_blank">{$file}</a>
</li>
{/if}
{/foreach}
</ul>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-6 detail-view-row-item">


<div class="col-xs-12 col-sm-4 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_ASSIGNED_TO_NAME' module='Cases'}{/capture}
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


{capture name="label" assign="label"}{sugar_translate label='LBL_CREATED_BY_NAME' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-8 detail-view-field " type="varchar" field="created_by_name"  >

{if !$fields.created_by_name.hidden}
{counter name="panelFieldCount" print=false}

{if strlen($fields.created_by_name.value) <= 0}
{assign var="value" value=$fields.created_by_name.default_value }
{else}
{assign var="value" value=$fields.created_by_name.value }
{/if} 
<span class="sugar_field" id="{$fields.created_by_name.name}">{$fields.created_by_name.value}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_SPENT_MINUTS' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="int" field="total_spent_minuts" colspan='3' >

{if !$fields.total_spent_minuts.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.total_spent_minuts.name}">
{sugar_number_format precision=0 var=$fields.total_spent_minuts.value}
</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_TIME_CATEGORY' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="enum" field="time_category" colspan='3' >

{if !$fields.time_category.hidden}
{counter name="panelFieldCount" print=false}


{if is_string($fields.time_category.options)}
<input type="hidden" class="sugar_field" id="{$fields.time_category.name}" value="{ $fields.time_category.options }">
{ $fields.time_category.options }
{else}
<input type="hidden" class="sugar_field" id="{$fields.time_category.name}" value="{ $fields.time_category.value }">
{ $fields.time_category.options[$fields.time_category.value]}
{/if}
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
{sugar_translate label='LBL_EDITVIEW_PANEL2' module='Cases'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-0"  data-id="LBL_EDITVIEW_PANEL2">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_INTERNAL' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="bool" field="internal" colspan='3' >

{if !$fields.internal.hidden}
{counter name="panelFieldCount" print=false}

{if strval($fields.internal.value) == "1" || strval($fields.internal.value) == "yes" || strval($fields.internal.value) == "on"} 
{assign var="checked" value='checked="checked"'}
{else}
{assign var="checked" value=""}
{/if}
<input type="checkbox" class="checkbox" name="{$fields.internal.name}" id="{$fields.internal.name}" value="$fields.internal.value" disabled="true" {$checked}>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_UPDATE_TEXT' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="update_text" colspan='3' >

{if !$fields.update_text.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.update_text.name|url2html|nl2br}">{$fields.update_text.value|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_UPDATE_ATTACHMENT' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="Multiupload" field="update_attachment_c" colspan='3' >

{if !$fields.update_attachment_c.hidden}
{counter name="panelFieldCount" print=false}
{if strlen($fields.update_attachment_c.value) <= 0}
{assign var="value" value=$fields.update_attachment_c.default_value }
{else}
{assign var="value" value=$fields.update_attachment_c.value }
{/if}
{assign var=files value=":"|explode:$value}
{assign var="record_id" value=$fields.id.value}
<ul>
{foreach from=$files item=file}
{if !empty($file)}
<li>
<a href="index.php?entryPoint=download2&file_path=multi_{$record_id}&field_name={$fields.update_attachment_c.name}&file_name={$file|urlencode}" target="_blank">{$file}</a>
</li>
{/if}
{/foreach}
</ul>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_AOP_CASE_UPDATES_THREADED' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="function" field="aop_case_updates_threaded" colspan='3' >

{if !$fields.aop_case_updates_threaded.hidden}
{counter name="panelFieldCount" print=false}
<span id='aop_case_updates_threaded_span'>
{$fields.aop_case_updates_threaded.value}
</span>
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
{sugar_translate label='LBL_EDITVIEW_PANEL2' module='Cases'}
</div>
</a>
</div>
<div class="panel-body panel-collapse collapse in panelContainer" id="top-panel-0" data-id="LBL_EDITVIEW_PANEL2">
<div class="tab-content">
<!-- TAB CONTENT -->





<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_INTERNAL' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="bool" field="internal" colspan='3' >

{if !$fields.internal.hidden}
{counter name="panelFieldCount" print=false}

{if strval($fields.internal.value) == "1" || strval($fields.internal.value) == "yes" || strval($fields.internal.value) == "on"} 
{assign var="checked" value='checked="checked"'}
{else}
{assign var="checked" value=""}
{/if}
<input type="checkbox" class="checkbox" name="{$fields.internal.name}" id="{$fields.internal.name}" value="$fields.internal.value" disabled="true" {$checked}>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_UPDATE_TEXT' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="text" field="update_text" colspan='3' >

{if !$fields.update_text.hidden}
{counter name="panelFieldCount" print=false}

<span class="sugar_field" id="{$fields.update_text.name|url2html|nl2br}">{$fields.update_text.value|escape:'html_entity_decode'|url2html|nl2br}</span>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_UPDATE_ATTACHMENT' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="Multiupload" field="update_attachment_c" colspan='3' >

{if !$fields.update_attachment_c.hidden}
{counter name="panelFieldCount" print=false}
{if strlen($fields.update_attachment_c.value) <= 0}
{assign var="value" value=$fields.update_attachment_c.default_value }
{else}
{assign var="value" value=$fields.update_attachment_c.value }
{/if}
{assign var=files value=":"|explode:$value}
{assign var="record_id" value=$fields.id.value}
<ul>
{foreach from=$files item=file}
{if !empty($file)}
<li>
<a href="index.php?entryPoint=download2&file_path=multi_{$record_id}&field_name={$fields.update_attachment_c.name}&file_name={$file|urlencode}" target="_blank">{$file}</a>
</li>
{/if}
{/foreach}
</ul>
{/if}

</div>


</div>

</div>


<div class="row detail-view-row">



<div class="col-xs-12 col-sm-12 detail-view-row-item">


<div class="col-xs-12 col-sm-2 label col-1-label">


{capture name="label" assign="label"}{sugar_translate label='LBL_AOP_CASE_UPDATES_THREADED' module='Cases'}{/capture}
{$label|strip_semicolon}:
</div>


<div class="col-xs-12 col-sm-10 detail-view-field " type="function" field="aop_case_updates_threaded" colspan='3' >

{if !$fields.aop_case_updates_threaded.hidden}
{counter name="panelFieldCount" print=false}
<span id='aop_case_updates_threaded_span'>
{$fields.aop_case_updates_threaded.value}
</span>
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