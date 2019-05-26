<?php /* Smarty version 2.6.31, created on 2019-05-24 10:11:26
         compiled from custom/include/SugarFields/Fields/Multiupload/DetailView.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'sugarvar', 'custom/include/SugarFields/Fields/Multiupload/DetailView.tpl', 1, false),)), $this); ?>
{if strlen(<?php echo smarty_function_sugarvar(array('key' => 'value','string' => true), $this);?>
) <= 0}
    {assign var="value" value=<?php echo smarty_function_sugarvar(array('key' => 'default_value','string' => true), $this);?>
 }
{else}
    {assign var="value" value=<?php echo smarty_function_sugarvar(array('key' => 'value','string' => true), $this);?>
 }
{/if}

{assign var=files value=":"|explode:$value}
{assign var="record_id" value=$fields.id.value}

<?php if (( $this->_tpl_vars['MULTI_UPLOAD'] )): ?>
    <ul>
        {foreach from=$files item=file}
            {if !empty($file)}
                <li>
                   <a href="index.php?entryPoint=download2&file_path=multi_{$record_id}&field_name=<?php echo smarty_function_sugarvar(array('key' => 'name'), $this);?>
&file_name={$file|urlencode}" target="_blank">{$file}</a>
                </li>
            {/if}
        {/foreach}
    </ul>
<?php else: ?>
    <p>
        Your license for Multiupload Package is expired. <br />
<a href="index.php?module=Multiupload&action=license">Click here to enter License Key.</a>
    </p>
<?php endif; ?>
