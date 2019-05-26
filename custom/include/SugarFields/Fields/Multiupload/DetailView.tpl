{if strlen({{sugarvar key='value' string=true}}) <= 0}
    {assign var="value" value={{sugarvar key='default_value' string=true}} }
{else}
    {assign var="value" value={{sugarvar key='value' string=true}} }
{/if}

{assign var=files value=":"|explode:$value}
{assign var="record_id" value=$fields.id.value}

{{if ($MULTI_UPLOAD)}}
    <ul>
        {foreach from=$files item=file}
            {if !empty($file)}
                <li>
                   <a href="index.php?entryPoint=download2&file_path=multi_{$record_id}&field_name={{sugarvar key='name'}}&file_name={$file|urlencode}" target="_blank">{$file}</a>
                </li>
            {/if}
        {/foreach}
    </ul>
{{else}}
    <p>
        Your license for Multiupload Package is expired. <br />
<a href="index.php?module=Multiupload&action=license">Click here to enter License Key.</a>
    </p>
{{/if}}

