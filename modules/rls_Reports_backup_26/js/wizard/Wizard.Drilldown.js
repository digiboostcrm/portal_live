Wizard.Drilldown = {

    /**
     * Sugar GUID for current record.
     * 
     * */
    reportId: '',
    
    /**
     * Reload list of fields and configured filters after on/off drilldown.
     *
     * @param object obj current object
     * @param string report_id sugar guid(id) of report
     */
    reloadData: function (obj, report_id) {
        Wizard.Drilldown.reportId = report_id;

        // reload filters
        $('#filters-modules-tree li.jstree-last a').first().click();
        Wizard.Drilldown.reloadSelectedData('filter-fields-sortable', 'DisplayFilters');

        // reload grouping
        $('#groupby-modules-tree li.jstree-last a').first().click();
        Wizard.Drilldown.reloadSelectedData('groupby-fields-sortable', 'DisplayGroupBy');
        
    },

    /**
     * Update html with configured rows according with step settings.
     *
     * @param string insert_into_id tag id where update html
     * @param string class_step php class name for get updated html
     * */
    reloadSelectedData: function(insert_into_id, class_step) {
        Wizard.Request.set({
              data:{
                  'action': 'getSelectedDataHtml',
                  'record': Wizard.Drilldown.reportId,
                  'type': $('#type').val(),
                  'class_step': class_step,
                  'drill_down': $('#drill_down').attr('checked'),
              },
              success: function(data){
                  $('#' + insert_into_id).html(data);
              },
              error: function() {
                  alert('Error!');
              }
        });
    },
    
};
