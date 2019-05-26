/**
 * This object is intended for HTTP layer
 * Provide HTTP requesting data from rls_Reports module
 * 
 * */
Wizard.Request = {
        
    /**
     * The valid URL value
     * 
     * @var string
     * */
    url: undefined,
    
    /**
     * This method is intended for Request object preparation.
     * 
     * @return string
     * @param Object parameters    The parameters for this.Request Object preparation
     * */
    prepare: function (parameters){
        this.url = parameters.url;            
        
        $.ajaxSetup({
            url: this.url,
            global: false,
            type: "POST"
        });/**/
        
        return this.url;
    },
    
    /**
     * This method is intended for requesting by AJAX.
     * 
     * @param Object parameters  Put parameters here in std. Object format for adding parameters to request.
     * */
    set: function (parameters){
        $.ajax(parameters);
    },
    
    /**
     * Return valid URL for Request
     * 
     * @param object Elements	The Object conatins an elements for request
     * */
    getUrl: function (parameters) {
    	var url = 'index.php?module=rls_Reports&sugar_body_only=true'; 
    	
    	$.each(
    		parameters,
    		function (key, value){
    			url = url + '&' + key + '=' + value;
    		}
    	);
    	
    	return url; 
    }
};