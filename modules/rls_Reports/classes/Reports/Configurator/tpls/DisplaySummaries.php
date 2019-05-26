<tr class="rls_selected_field">
   <td width="3%">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <input type="hidden"
             name="wizard[DisplaySummaries][<?php echo $row_name;?>][reletion_name]"
             value="<?php echo $this->getReletion();?>"/>
      <input type="hidden"
             name="wizard[DisplaySummaries][<?php echo $row_name;?>][field_name]"
             value="<?php echo $field_defs['name'];?>"/>
      <input type="hidden"
             name="wizard[DisplaySummaries][<?php echo $row_name;?>][module_of_report]"
             value="<?php echo $this->getModule();?>"/>
   </td>
   <td width="15%">
      <b><?php echo $this->getDisplayLink($this->getModule(),
                                         translate($field_defs['vname'],
                                                   $this->getSelectedModuleName($this->getModule(),
                                                                                $this->getReletion())
                                                  ),
                                         $this->getReletion() );?>
      </b>
   </td>
   <td width="15%">
      <?php echo $summaries_function;?> 
   </td>
   <td width="10%" >
      <input type="button" value="<?php echo $mod_strings['LBL_DELETE']?>" class="remove_row" style="float:right"/>
   </td>
</tr>
