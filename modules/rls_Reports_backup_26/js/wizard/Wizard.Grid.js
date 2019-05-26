Wizard.Grid = {
     
    /**
     * Return parameters for the node as Object.
     * 
     * @praram Object node   The object for node, it's 'a' tag.
     * @return Object 
     */
    getNodeParameters: function (node){
    	var parameters = 
    		$(node)
    			.attr('id')
    			.split('-');
    	var object_parameters = {
    		moduleName: parameters[1]
    	};
    	
      if (typeof parameters[2] != undefined){
    		object_parameters.fieldName = parameters[2]; 
    	}
      
    	if (typeof parameters[2] != undefined){
    		object_parameters.relationName = parameters[3]; 
    	}
    	
    	
    	return object_parameters;
    },
    
    /**
     * Add row to fields greed
     *
     * @param string tbody_id_append tbody id for append row with html data
     * @param string typeRow type data for append (class name)
     * */
    addRow: function(moduleName,fieldName, reletionName, tbody_id_append, typeRow){
        Wizard.Request.set({
            data:{
                'type':typeRow,
                'action':'getFieldHtml',
                'moduleName': moduleName,
                'fieldName': fieldName,
                'reletionName' : reletionName,
                'drill_down': $('#drill_down').attr('checked'),
            },
            success: function(data){
              $( '#' + tbody_id_append ).append(data);
            },
            error: function(){
                alert('Error!');
            }
        });
    },

    /**
     * Remove field from fields grid
     * */
    removeRow: function(elem){
        $(elem).closest('tr').remove();
    },
    
    /**
     * Initial drag and drop function
     * */
    initDragAndDrop: function(tbody_sortable_id){
        $('#'+ tbody_sortable_id).sortable({
            axis: 'y',
            cursor: 'move',
            handle: 'td:first-child',
            placeholder: 'wizard-fields-placeholder',
            helper: 'wizard-fields-helper',
            opacity: 0.7
        });
    }
};
