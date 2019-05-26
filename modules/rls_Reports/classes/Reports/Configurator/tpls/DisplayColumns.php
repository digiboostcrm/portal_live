
<tr class="rls_selected_field">
   <td width="5%">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <input type="hidden"
             name="wizard[DisplayFields][<?php echo $field_name;?>][reletion_name]"
             value="<?php echo $this->getReletion();?>"/>
      <input type="hidden"
             name="wizard[DisplayFields][<?php echo $field_name;?>][field_name]"
             value="<?php echo $field_settings['vardefs']['name'];?>"/>
      <input type="hidden"
             name="wizard[DisplayFields][<?php echo $field_name;?>][module_of_report]"
             value="<?php echo $this->getModule();?>"/>
   </td>
   <td width="35%"><b><?php echo $this->getDisplayLink($this->getModule(),
                                                       translate($field_settings['vardefs']['vname'],
                                                                 $this->getSelectedModuleName($this->getModule(),
                                                                                              $this->getReletion())
                                                                ),
                                                       $this->getReletion() ) ?></b>
   </td>

   <td width="40%">
      <input type="text" name="wizard[DisplayFields][<?php echo $field_name;?>][label]"
             value="<?php echo htmlentities( html_entity_decode($label, ENT_QUOTES, 'UTF-8')
                                              , ENT_QUOTES
                                              , 'UTF-8');?>"
      />
   </td>
   <td width="1%">
       <input type="radio" name="wizard[DisplayFields][<?php echo $field_name;?>][radio_btn]" <?php echo $checked;?> onclick="onclickRadioDisplayColumns(this.name);">
    </td>
   <td width="40%">
       <select name="wizard[DisplayFields][<?php echo $field_name;?>][orderBy]" style="<?php echo $select_style;?>">
      <option value="a" <?php echo $selectedA;?>>Ascending</option>
      <option value="d" <?php echo $selectedD;?>>Descending</option>      
    </select>
   </td>
   
   <td width="10%">
      <input type="button" value="<?php echo $mod_strings['LBL_DELETE']?>" class="remove_row" style="float:right"/>
   </td>
</tr>
<script type="text/javascript">
    /**
     * onclick radio contrlol for DisplayColumns
     *
     * @param string radio name
     * 
     * */
    function onclickRadioDisplayColumns(radio_name)
    {
        $('input[type=radio]').prop('checked', false);
        $('select').css('display','none');
        var radio_type = document.getElementsByName(radio_name);
        $(radio_type).prop('checked', true);
        var orderBy_name = radio_name.replace("radio_btn", "orderBy");
        var orderBy = document.getElementsByName(orderBy_name);
        $(orderBy).css('display','inline'); 
        $('#chart_type').css('display','inline');
       
    }
</script>