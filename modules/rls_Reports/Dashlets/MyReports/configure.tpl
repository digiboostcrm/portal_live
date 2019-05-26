{*

/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

*}


<div style='width:100%'>
<form name='configure_{$id}' action="index.php" method="post" onSubmit='return SUGAR.dashlets.postForm("configure_{$id}", SUGAR.mySugar.uncoverPage);'>
<input type='hidden' name='id' value='{$id}'>
<input type='hidden' name='module' value='{$module}'>
<input type='hidden' name='action' value='ConfigureDashlet'>
<input type='hidden' name='to_pdf' value='true'>
<input type='hidden' name='configure' value='true'>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="edit view" align="center">
<tr>
    <td scope='row'>{$titleLBL}</td>
    <td>
      <input class="text" name="title" size='20' maxlength='80' value='{$title}'>
    </td>
</tr>
{if $isRefreshable}
<tr>
    <td scope='row'>
        {$autoRefresh}
    </td>
    <td>
        <select name='autoRefresh'>
            {html_options options=$autoRefreshOptions selected=$autoRefreshSelect}
        </select>
    </td>
</tr>
{/if}
{*
<tr>
    <td scope='row'>{$urlLBL}</td>
    <td>
      <input class="text" name="url" size='20' maxlength='255' value='{$url}'>
    </td>
</tr>

<tr>
    <td scope='row'>{$heightLBL}</td>
    <td>
      <input class="text" name="height" size='20' maxlength='80' value='{$height}'>
    </td>
</tr>
*}
<tr>
    <td scope='row'>{$reportLBL}</td>
    <td>
        <input type="text" autocomplete="off" title="" value="{$report_c}" size="" id="report_c" tabindex="0" class="sqsEnabled yui-ac-input" name="report_c">
        <input type="hidden" value="{$rls_reports_id_c}" id="rls_reports_id_c" name="rls_reports_id_c">
        {literal}    
            <span class="id-ff multiple"> 
            <button
              onclick="open_popup(&quot;rls_Reports&quot;, 600, 400, &quot;&quot;, true, false,
              {&quot;call_back_function&quot;:&quot;set_return_rls&quot;,
              &quot;form_name&quot;:&quot;{/literal}configure_{$id}{literal}&quot;,
              &quot;field_to_name_array&quot;:{&quot;id&quot;:&quot;rls_reports_id_c&quot;,&quot;name&quot;:&quot;report_c&quot;}}, &quot;single&quot;, true );"
              value="Выбрать" class="button firstChild" accesskey="T" title="Select [Alt+T]" id="btn_report_c" name="btn_report_c" type="button">
              <img src="themes/default/images/id-ff-select.png?v=mgtokehP0hlxfqolZv7qOw">
            </button>
            <button value="Clear Selection" onclick="SUGAR.clearRelateField(this.form, 'report_c', 'rls_reports_id_c');" class="button lastChild" 
                title="Clear Selection" id="btn_clr_report_c" name="btn_clr_report_c" type="button">
                <img src="themes/default/images/id-ff-clear.png?v=mgtokehP0hlxfqolZv7qOw">
            </button>
            </span>
        {/literal}    
    </td>
</tr>

<tr>
    <td align="right" colspan="2">
        <input type='submit' class='button' value='{$saveLBL}'>
    </td>
</tr>
</table>
</form>
</div>
{literal}
<script type="text/javascript">
function set_return_rls(popup_reply_data) {
    from_popup_return = true;
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    name_to_value_array['title'] = name_to_value_array['report_c'];
    if (typeof name_to_value_array != 'undefined' && name_to_value_array['account_id']) {
        var label_str = '';
        var label_data_str = '';
        var current_label_data_str = '';
        var popupConfirm = popup_reply_data.popupConfirm;
        for (var the_key in name_to_value_array) {
            if (the_key == 'toJSON') {
            }
            else {
                var displayValue = replaceHTMLChars(name_to_value_array[the_key]);
                if (window.document.forms[form_name] && document.getElementById(the_key + '_label') && !the_key.match(/account/)) {
                    var data_label = document.getElementById(the_key + '_label').innerHTML.replace(/\n/gi, '').replace(/<\/?[^>]+(>|$)/g, "");
                    label_str += data_label + ' \n';
                    label_data_str += data_label + ' ' + displayValue + '\n';
                    if (window.document.forms[form_name].elements[the_key]) {
                        current_label_data_str += data_label + ' ' + window.document.forms[form_name].elements[the_key].value + '\n';
                    }
                }
            }
        }
        if (label_data_str != label_str && current_label_data_str != label_str) {
            if (typeof popupConfirm != 'undefined') {
                if (popupConfirm > -1) {
                    set_return_basic(popup_reply_data, /\S/);
                } else {
                    set_return_basic(popup_reply_data, /account/);
                }
            }
            else if (confirm(SUGAR.language.get('app_strings', 'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM') + '\n\n' + label_data_str)) {
                set_return_basic(popup_reply_data, /\S/);
            }
            else {
                set_return_basic(popup_reply_data, /account/);
            }
        } else if (label_data_str != label_str && current_label_data_str == label_str) {
            set_return_basic(popup_reply_data, /\S/);
        } else if (label_data_str == label_str) {
            set_return_basic(popup_reply_data, /account/);
        }
    } else {
        set_return_basic(popup_reply_data, /\S/);
    }
}
</script>
{/literal}
