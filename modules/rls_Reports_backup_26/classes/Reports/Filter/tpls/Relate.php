<input type="text"
       readonly="readonly"
       title=""
       value="<?php echo htmlentities(
                            html_entity_decode($current_value['relate_name'], ENT_QUOTES, 'UTF-8')
                            , ENT_QUOTES
                            , 'UTF-8'); ?>"
       size=""
       id="relate_name_<?php echo $rand_key; ?>"
       class="sqsEnabled yui-ac-input"
       name="wizard[DisplayFilters][<?php echo $settings['control_name'].'_'.$settings['field_guide']; ?>][value][relate_name]">
       
<input type="hidden"
       value="<?php echo $current_value['guid']; ?>"
       id="relate_id_<?php echo $rand_key; ?>"
       name="wizard[DisplayFilters][<?php echo $settings['control_name'].'_'.$settings['field_guide']; ?>][value][guid]">
       
<span class="id-ff multiple">
    <button value="Select"
            onclick="open_popup(
                        '<?php echo $settings['module']; ?>', 
                        600, 
                        400, 
                        '', 
                        true, 
                        false, 
                        {'call_back_function':'set_return',
                        'form_name':'<?php echo ($_REQUEST['action'] == 'DetailView'?'FilterValues':'EditView'); ?>',
                        'field_to_name_array':{'<?php echo $settings['relate_id_field']; ?>':'relate_id_<?php echo $rand_key; ?>',
                                               '<?php echo $settings['relate_name_field']; ?>':'relate_name_<?php echo $rand_key; ?>'}}, 
                        'single', 
                        true
                        );"
            class="button firstChild"
            accesskey="T"
            title="Select [Alt+T]"
            id="btn_<?php echo $settings['control_name'].'_'.$settings['field_guide']; ?>_relate_name"
            name="btn_<?php echo $settings['control_name'].'_'.$settings['field_guide']; ?>_relate_name"
            type="button">
        <img src="<?php echo SugarThemeRegistry::current()->getImageURL('id-ff-select.png')?>">
    </button>
    <button value="Clear"
            onclick="$('#relate_name_<?php echo $rand_key; ?>').val(''); $('#relate_id_<?php echo $rand_key; ?>').val('');"
            class="button lastChild"
            accesskey="C"
            title="Clear [Alt+C]"
            tabindex="119"
            id="btn_clr_<?php echo $settings['control_name'].'_'.$settings['field_guide']; ?>_relate_name"
            name="btn_clr_<?php echo $settings['control_name'].'_'.$settings['field_guide']; ?>_relate_name"
            type="button">
        <img src="<?php echo SugarThemeRegistry::current()->getImageURL('id-ff-clear.png')?>">
    </button>
</span>
