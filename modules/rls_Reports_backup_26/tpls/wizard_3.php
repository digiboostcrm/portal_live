<div class="rls_wizard_main">
    <div class="rls_wizard_left">
        <div class="rls_wizard_left_box">
          <div class="rls_wizard_left_header">
              <h3 class="h3Row"><?php echo $mod_strings['LBL_MODULES'] ?></h3>
          </div>
          <div class="rls_wizard_left_container">
              <div id="modules-tree"></div>
              <ul id="rls_list" class="filetree">
        
              </ul>
          </div>
        </div>
        <div class="rls_wizard_left_box">
            <div class=rls_wizard_left_header>
                <h3 class="h3Row">
                    <?php echo $mod_strings['LBL_FIELDS'] ?>
                    <input id="fields_of_module-search_field" size="12" onkeyup="Wizard.Fields.setFilter(this.value, 'fields_of_module')">
                    <input type="button" value="X" onclick="Wizard.Fields.resetFilter('fields_of_module')">
                </h3>
            </div>
            <div class=rls_wizard_left_container>
                <table class="" cellpadding="0" cellspacing="0">
                    <tbody id="fields_of_module">
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="rls_wizard_right">
        <div class="rls_wizard_right_main">
            <div class="rls_wizard_right_header">
                <h3 class="h3Row"><?php echo $mod_strings['LBL_SELECTED_DISPLAY_FIELDS'] ?></h3>
            </div>
            <div class="rls_wizard_right_container">
                <div class="rls_sortable">
                    <table class="rls_table_sortable" cellpadding="0" cellspacing="0">
                        <tbody id="wizard-fields-sortable">
                          <?php if (!isset($_REQUEST['']) and !isset($_REQUEST['root'])): ?>
                          <?php echo $display_columns->display($wizard_displayfields->get()) ?>
                          <?php endif ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
