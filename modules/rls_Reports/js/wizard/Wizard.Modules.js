Wizard.Modules = {
    /**
     * Current module
     * 
     * */
    currentModule: undefined,
    
    
    /**
     * Set current module
     * */
    setCurrentModule: function(module_name){
        this.currentModule = module_name;
        return this;
    },
    
    /**
     * Get current module
     * */
    getCurrentModule: function(){
        return this.currentModule;
    },
    
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
    		object_parameters.relationName = parameters[2]; 
    	}
    	
    	
    	return object_parameters;
    },
    
    /**
     * Get list of modules
     * */
    getModulesList: function(module_name){
        // Not yet implemented
        this.setCurrentModule(module_name);
        Wizard.Request.set({
            data:{
                'action':'getAccessibleModulesList',
                'moduleName': this.getCurrentModule()
            },
            success: function(data){
                //Wizard.Moduls.setModuleList(data);
                //Wizard.Moduls.initTree();
            },
            error: function(){
                alert('Error!');
            }
                            
        });
    },
    
    /**
     * Insert a list of modules to interface
     * */
    setModuleList: function(html){
        // Not yet implemented
    },
    
    /**
     * Get a list of fields of the module
     * */
    getFieldsList: function(){
        // Not yet implemented
    },
    
    /**
     * This method running when modules Tree loaded.
     * 
     * Actions:
     *   1. Loading first childs
     *   2. Loading Fields from root Module
     * 
     * @param Object event   The OBject contains Event parameters
     * @param Object data	 The Object contains data parameters compose by jsTree
     * */
    afterTreeLoaded: function (event, data, tree_id) {
        $('#'+tree_id+' ul li ins').click();
        setTimeout(
          function () {
            $('#'+tree_id+' li.jstree-last a').first().click();
          },
          2000
        );
		
		/* TODO: Regarding jsTree documentation it should work, but it didn't.
		 *       Try to get last version of jsTree and try to run again in new version. 
		 *
		$('#modules-tree')
			.jstree(
				'open_node', 
				'li.jstree-last'
			);
		
		
		$('#modules-tree')
			.jstree(
				'toggle_node', 
				'#modules-tree ul li'
			);/**/
    },
    
    /**
     * This method running when a Node of modules Tree was selected.
     * 
     * Actions:
     *   1. Loading first childs
     *   2. Loading Fields from root Module
     * 
     * @param Object event   The OBject contains Event parameters
     * @param Object data	 The Object contains data parameters compose by jsTree
     * */
    treeNodeSelected: function (event, data, list_fields_id, td_class_name) {
        event.preventDefault();
        var parameters = Wizard.Modules.getNodeParameters(data.args[0]);
        Wizard.Fields.setFields(
            parameters.moduleName, 
            parameters.relationName,
            list_fields_id,
            td_class_name

        );    	
    },

    /**
     * Init tree lib
     * */
    initTree: function(tree_id, list_fields_id, td_class_name) {
        $('#' + tree_id).jstree({ 
            'json_data' : {
                'ajax' : {
                    url: Wizard.Request.getUrl({
                        action: 'getAccessibleModulesList'
                    }),
                    'data' : function (data) {
                        var request = {
                            record: $('input[name=record]').val(),
                            type:   $('input[name=type]').val()
                        };
                        if (data != -1) {
                            //console.log(data);
                            var parameters = Wizard.Modules.getNodeParameters($('a', data));
                            request.moduleName = parameters.moduleName;
                            if (typeof(parameters.relationName) != 'undefined') {
                                request.relationName = parameters.relationName;
                            } else {
                                request.relationName = '';
                            }
                        }
                        return request;
                    }
                }
            },
            'plugins' : [ 'themes', 'json_data', 'ui' ]
        })
        .bind(
            'select_node.jstree', 
            function (event, data) {
                Wizard.Modules.treeNodeSelected(event, data, list_fields_id, td_class_name);
            }
        )
        .bind(
          'loaded.jstree', 
          function (event, data) {
              Wizard.Modules.afterTreeLoaded(event, data, tree_id);
          }
        );
    }
   
};
