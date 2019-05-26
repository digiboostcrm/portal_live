/**
 * This class navigating on the flow of reports Creation
 * 
 * */
Wizard.Control = {
    /**
     * Number of current step
     * 
     * */
    step: 1,
    
    /**
     * The type of the report
     * 
     * */
    type: '',
    
    /**
     * Bind the buttons
     * 
     * */
    bind: function (){
        $('img.report-type').click(function (){
        	var record = $('input[name=record]').val();
        	var record_param = '';
        	if (record != '' && typeof(record)!='undefined' ) {
        		record_param = '&record='+record;
			}
        	
        	document.location.href = 'index.php?module=rls_Reports&action=EditView&root='+$(this).attr('name') + record_param;
        });
        
        $('#reports-wizard-navigate a').click(function (e) {
        	e.preventDefault();
        	if ($(this).hasClass('active')) {
				return false;
			}
        	
        	var element_position = parseInt($(this).parent().index());
        	
        	Wizard.Control.step = element_position - parseInt( element_position/2 );
        	Wizard.Control.refreshWorkarea();
        });
    },
    
    /**
     * Move to the next step of wizard
     * 
     * */
    next: function (){
        this.step++;
        this.refreshWorkarea();
    },

    /**
     * Move to the previous step of wizard
     * 
     * */
    previous: function (){
        this.step--;
        this.refreshWorkarea();
    },
    
    /**
     * Refreshing workarea
     * 
     * */
    refreshWorkarea: function (){
    	$('.reports-wizard-step-container')
    		.addClass('hidden');
    	$('#reports-wizard-navigate a.active')
    		.removeClass('active');
    	
    	$($('.reports-wizard-step-container')
    	      .get(Wizard.Control.step)
    	  ).removeClass('hidden');
    	
    	$($('#reports-wizard-navigate h4')
      	      .get(Wizard.Control.step)
      	  ).children('a').addClass('active');
    },
    
    /**
     * Show Proper modules
     * */
    showProperModules: function (){
        var search_module = $('#search_module').val();
        $('.wizard_step1_module_name').each(function( index ) {
            var reg = new RegExp('^'+search_module, "i");
            var row_class = $(this).closest('tr').attr('class');
            if(reg.test($( this ).text())){
                $('.'+row_class).show();
            } else {
                $('.'+row_class).hide();
            }
        });
    }
};
