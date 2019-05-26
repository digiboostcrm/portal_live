$(document).ready(function (){

    $('.wizard-content')
    	.parent()
    	.parent()
    	.attr('colspan', 4);

    Wizard.Request.prepare({
        url:    "index.php?module=rls_Reports&sugar_body_only=true",
        global: false,
        type:   "POST"
    });
    
    /**
     * Remove row from the selected fields
     * */
    $(document)
        .on('click','.remove_row', function() {
            Wizard.Grid.removeRow(this);
    });
    
    /**
     * Add Row to selected fields
     * */
    if (Wizard.Modules != undefined) {
        Wizard.Fields.bindAddRow('rls_addFilter', 'filter-fields-sortable', 'DisplayFilters');
        Wizard.Fields.bindAddRow('rls_addRow', 'wizard-fields-sortable', 'DisplayColumns');
        Wizard.Fields.bindAddRow('rls_addGroupBy', 'groupby-fields-sortable', 'DisplayGroupBy');
        Wizard.Fields.bindAddRow('rls_addSummaries', 'summaries-fields-sortable', 'DisplaySummaries');
    }
    
    Wizard.Control.bind();    
    
    if (Wizard.Modules != undefined) {
        Wizard.Modules.initTree('filters-modules-tree', 'filter_fields_of_module', 'rls_addFilter');
        Wizard.Modules.initTree('modules-tree', 'fields_of_module', 'rls_addRow');
        Wizard.Modules.initTree('groupby-modules-tree', 'groupby_fields_of_module', 'rls_addGroupBy');
        Wizard.Modules.initTree('summaries-modules-tree', 'summaries_fields_of_module', 'rls_addSummaries');
    }
    
    if (Wizard.Grid != undefined) {
        Wizard.Grid.initDragAndDrop('filter-fields-sortable');
        Wizard.Grid.initDragAndDrop('wizard-fields-sortable');
        Wizard.Grid.initDragAndDrop('groupby-fields-sortable');
        Wizard.Grid.initDragAndDrop('summaries-fields-sortable');
    }
    
    addToValidate('EditView', 'type', 'varchar', true, 'Report Type');
    
    
    /**
     * Show Proper modules
     * */
    $(document)
        .on('keyup','#search_module', function() {
            Wizard.Control.showProperModules();
    });
    
});



