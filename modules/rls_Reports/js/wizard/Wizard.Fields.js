Wizard.Fields = {

    /**
     * Set filter for fields by name and set hide or show property.
     *
     * @param string search_name name for search fields
     * @param string table_id table id for find row with "No records" label
     * */
    setFilter: function(search_name, table_id)
    {
        finded = false;
        $.each($('#'+table_id+' tr td'), function(i, y) {
            str = $(this).html().toUpperCase();
            
            if ((str.indexOf(search_name.toUpperCase()) + 1) || !search_name) {
                $(this).parent().show();
                finded = true;
            } else {
                $(this).parent().hide();
            }
            
        });

        if (finded) {
            $('#'+table_id+'_no-records').parent().hide();
        } else {
            $('#'+table_id+'_no-records').parent().show();
        }
    },

    /**
     * Reset filter for displayed fields.
     *
     * @param string table_id table id for clear filter
     */
    resetFilter: function(table_id)
    {
        $('#'+table_id+'-search_field').val('');
        Wizard.Fields.setFilter('', table_id);
    },

    /**
     * Bind click on list of fields.
     *
     * @param string td_class_name td class name for bind click
     * @param string tbody_id_append tbody id for append row with html data
     * @param string typeRow type data for append (php class name)
     * */
    bindAddRow: function(td_class_name, tbody_id_append, typeRow)
    {
        $(document)
            .on('click','.' + td_class_name, function() {
                var param = Wizard.Grid.getNodeParameters($(this));
                Wizard.Grid.addRow(
                    param.moduleName,
                    param.fieldName,
                    param.relationName,
                    tbody_id_append,
                    typeRow
                );
        });
    },
  
    /**
     * Insert a list of fields in the controller
     * 
     * FIXME: Actually this method should set, i.e. load content to display fields. 
     * 
     * */
    setFields: function(moduleName, reletionName, list_fields_id, td_class_name)
    {
        var fields_html = this.getFieldsHtml(moduleName, reletionName, list_fields_id, td_class_name);
    },

    /**
     * TODO: Make this more structural.
     * 	     Success operation should be parsed with separate method
     * 		 Error operation should be parsed with separate method
     * 
     * Get fields of module
     *
     * @param string moduleName module name
     * @param string reletionName reletion name
     * @param string list_fields_id table id for fields of module
     * @param string td_class_name td class name for bind click
     * 
     * */
    getFieldsHtml: function(moduleName, reletionName, list_fields_id, td_class_name)
    {
      // set drilldown settings
      drilldown = '';
      if (list_fields_id == 'filter_fields_of_module'
          || list_fields_id == 'groupby_fields_of_module'
      ) {
          drilldown = $('#drill_down').attr('checked');
      }

      // set summaries settings
      stepName = '';
      if (list_fields_id == 'summaries_fields_of_module') {
          stepName = 'WizardSummaries';
      }
      
    	Wizard.Request.set({
            data:{
                'action':'getAccessibleFieldsList',
                'moduleName': moduleName,
                'reletionName' : reletionName,
                'td_class_name' : td_class_name,
                'type': $('#type').val(),
                'drill_down': drilldown,
                'stepName': stepName,
            },
            success: function(data){
                $('#'+list_fields_id+'-search_field').val('');
                $('#' + list_fields_id).html(data + Wizard.Fields.getHtmlTrNoRecords(list_fields_id));
            },
            error: function() {
                alert('Error!');
            }
                            
        });
    },

    /**
     * Get html for TR and label "No records".
     *
     * @param string list_fields_id table id for fields of module
     * @return string
     * */
    getHtmlTrNoRecords: function(list_fields_id)
    {
        return '<tr style="display:none;">'+
                  '<td id="'+list_fields_id+'_no-records" class="rls_addFilter">'+
                      SUGAR.language.get('rls_Reports', 'LBL_NO_RECORDS')+
                  '</td>'+
               '</tr>';
    },

};
