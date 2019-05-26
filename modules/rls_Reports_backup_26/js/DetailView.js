/**
 * The object is intended for interface realization of Spreadsheet
 * 
 * */
var SpreadSheet = {
	/**
	 * 
	 */
	setPaddingsForChilds: function () {
		var $header         = $('.reports-grid-group > div h1');
		var current_padding = parseInt( 
			$header
				.parent()
				.parent()
				.prev()
				.css('padding-left')
		);
			
		$header.css({
			paddingLeft: parseInt(current_padding+10) + 'px'
		});
	},
		
	/**
	 * Binding actions for SPreadsheet
	 * 
	 */
	bindActions: function () {
	    $('.reports-grid-group h1 a').click(function (e) {
	    	e.preventDefault();
	    	
	    	if ($(this).parent().next().is(":visible")) {
	    		SpreadSheet.collapseGroup(this);
			} else {
				SpreadSheet.expandGroup(this);
			}
	    });
	},
		
    /**
     * Expand group data
     * 
     * @param Object header		The DOM element of the header of Group.
     */
	expandGroup: function (header){
		$(header).parent().next().show();
		$(header).children('span')
			.removeClass('collapsed')
			.addClass('expanded');
	},
	
	/**
	 * Collapse group data
	 * 
	 * @param Object header		The DOM element of the header of Group.
	 */
	collapseGroup: function (header){
		$(header).parent().next().hide();
		$(header).children('span')
			.removeClass('expanded')
			.addClass('collapsed');
	}
	
};

/**
 * This object is intended for interface realization of Filter
 * 
 * 
 * */
var Filter = {
	/**
	 * Binds actions for the filter
	 * 
	 */
	init: function (){
		$('#report-filter-clear')
			.click(function (event) {
				Filter.clearValues(event);
			});
	},
		
	/**
	 * Clearing of the filters
	 * 
	 * @param Object event  The parameters of the event when button was clicked. 
	 */	
	clearValues: function (event) {
		event.preventDefault();
		var $button = $('#report-filter-clear');
		
		alert('To be implemented.'+"\n"+'This action will clear all values in filter.');
		$button.attr('disabled', 'disabled');
		$button.text('Cleared');
	}
};

/**
 * The object is intended for interface realization of Reports
 * 
 * */
var Reports = {
    /**
     * The object is intended for realization of PDF
     * 
     * */
    Pdf: {
        /**
         *  Download PDF for current page
         *
         * @param string id guid current record
         * */
        downloadPDF: function(id) {
            historyData = $('#legend' + id). html();
            document.location.href='index.php?module=rls_Reports&action=downloadPDF'+
                '&sugar_body_only=pdf'+
                '&record='+id+
                '&legendData='+encodeURIComponent(historyData);
        }
    },        

    /**
     * The object is intended for interface realization of Spreadsheet
     * 
     * */
    SpreadSheet: SpreadSheet,
    
    /**
     * The object is intended for interface realization of Spreadsheet
     * 
     * */
    Filter: Filter
};

/**
 * After load action.
 * */
$(document).ready(function() {
    // hide checkbox for Columns Chart
    if ($('#chart_type').val() == 'Columns') {
        $('#hide_empty').hide();
        $('#hide_empty').parent().prev().html('');
    }
    
    SpreadSheet.bindActions();
    SpreadSheet.setPaddingsForChilds();
    
    Filter.init();
});