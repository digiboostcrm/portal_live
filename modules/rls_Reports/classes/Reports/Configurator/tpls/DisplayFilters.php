<tr class="rls_selected_field">
   <td width="3%">
      <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
      <input type="hidden"
             name="wizard[DisplayFilters][<?php echo $filter_name;?>][reletion_name]"
             value="<?php echo $this->getReletion();?>"/>
      <input type="hidden"
             name="wizard[DisplayFilters][<?php echo $filter_name;?>][field_name]"
             value="<?php echo $field_settings['vardefs']['name'];?>"/>
      <input type="hidden"
             name="wizard[DisplayFilters][<?php echo $filter_name;?>][module_of_report]"
             value="<?php echo $this->getModule();?>"/>
       <input type="hidden"
              name="wizard[DisplayFilters][<?php echo $filter_name;?>][field_guide]"
              value="<?php echo $field_guide;?>"/>
   </td>
   <td width="25%"><b><?php echo $this->getDisplayLink($this->getModule(),
                                                       translate($field_settings['vardefs']['vname'],
                                                                 $this->getSelectedModuleName($this->getModule(),
                                                                                              $this->getReletion())
                                                                ),
                                                       $this->getReletion() ) ?></b></td>
   <td width="15%">
      <?php echo $condition_html;?> 
   </td>
   <td width="25%">
      <?php echo $filter_html;?> 
   </td>
   <td width="10%" <?php echo ($this->onlyRuntime ? 'style="display:none"' : '');?> >
       <input type="hidden"
              name="wizard[DisplayFilters][<?php echo $filter_name;?>][run_time]"
              value="0" >
       <input type="checkbox"
              value="1"
              name="wizard[DisplayFilters][<?php echo $filter_name;?>][run_time]"
              id="runtime"
              <?php echo ((isset($field_settings['run_time']) and !$field_settings['run_time'])
                    ? '  ' : ' checked="checked" ');?> 
              >
       <b><?php echo $mod_strings['LBL_RUNTIME']?></b>
   </td>
   <td width="10%" <?php echo ($this->onlyRuntime ? 'style="display:none"' : '');?> >
      <input type="button" value="<?php echo $mod_strings['LBL_DELETE']?>" class="remove_row" style="float:right"/>
   </td>
</tr>
