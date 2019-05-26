/**
 * This object is inteded for Dashlets handling
 * 
 */
Dashboard.Dashlets = {
	/**
	 * Sequence to refresh
	 * 
	 * @var Array
	 */
	sequenceRefresh: [],
		
	/**
	 * Positions of dashlets on start of moving
	 * 
	 * @var object
	 */
	startMovePosition: {
		column: 0,
		index: 0
	},
	
    /**
     * Adds empty dashlet to the top of the Page
     * 
     * @param string page_guid		The GUID of the Page.
     * */
	addEmptyDashlet: function (page_guid) {
	    $.get(
	        Dashboard.getUrl('DashboardAddEmptyDashlet', 'tab_guid='+page_guid)
	      , function (response){
	        	Dashboard.Dashlets.columnToRefresh(0);
	        	
	        	$('table[alt='+ page_guid +'] tr td')
	        		.first()
	        		.html(function(index, oldhtml) {
	        			return response.html + oldhtml;
					});
	        	$('#dashlet_'+response.guid)
	        	    .hide()
	        		.slideDown('slow');
	        	
	        	Dashboard.Dashlets.refreshSequence();
	        }
	      , 'json'
	    );
	},
	
	/**
	 * Binding Sortable stanc for all Dashlets list
	 * 
	 */
	applySortable: function () {
                
        // RLS Vic 29.03.2016
        var ui_tabs_active_class = '.ui-tabs-active';
        var jqueryUI_version = $.ui.version.split('.');
        if (Number(jqueryUI_version[0]) == 1 && Number(jqueryUI_version[1]) < 10) {
            ui_tabs_active_class = '.ui-tabs-selected';
        }
        
	    $('.dashboard-sortable').sortable({
			connectWith: '.dashboard-sortable',
			handle: '.hd',
			cursor: 'move',
			delay: 300,
			placeholder: 'dashboard-placeholder',
			forcePlaceholderSize: true,
			opacity: 0.7,
			start: function(event, ui){			
	    		Dashboard.Dashlets.setMovingIndicator(ui.item);
	    		Dashboard.Dashlets.startMovePosition.column = $(ui.item).parent().index();
	    		Dashboard.Dashlets.startMovePosition.index = $(ui.item).index();
			},
			stop: function(event, ui){
//				ui.item.css({'top':'0','left':'0'}); //Opera fix
				Dashboard.Dashlets.removeMovingIndicator(ui.item);
				
				var new_column = $(ui.item).parent().index();
				var new_index  = $(ui.item).index();
				
				if (
					Dashboard.Dashlets.startMovePosition.column != new_column
				    || Dashboard.Dashlets.startMovePosition.index != new_index
				) {
					Dashboard.Dashlets.savePosition({
						tab_guid: $(ui_tabs_active_class).attr('alt')
					  , dashlet_guid: $(ui.item).attr('alt')
					  , column: new_column
					  , index: new_index
					});
				}
			}
		})
		.disableSelection();
	},
	
	/**
	 * Sets indicator for moving status
	 * 
	 * @param object dashlet	Entire DOM element of Dashlet
	 */
	setMovingIndicator: function(dashlet) {
		$(dashlet).css({
			backgroundColor: '#F6F5E5'
		});
	},
	
	/**
	 * Sets indicator for moving status
	 * 
	 * @param object dashlet	Entire DOM element of Dashlet
	 */
	removeMovingIndicator: function(dashlet) {
		$(dashlet).css({
			backgroundColor: ''
		});
	},
	
	/**
	 * Saving position for Dashlet
	 * 
	 * @param object parameters  Parameters for save
	 */
	savePosition: function (parameters){
		$.get(
		   Dashboard.getUrl('DashboardSaveDashletPosition')
		 , parameters
		 , function (response){
			   //Dashboard.Tabs.refreshContent(parameters.tab_guid);
		   }
		);
	},
	
	/**
	 * Adds to refresh the number of column
	 * 
	 * @param integer column_number		The number of column
	 */
	columnToRefresh: function (column_number) {
		var $current_panel = $('.ui-tabs-panel').not('.ui-tabs-hide');
		var $column_object = $( $('.dashboard-dashlets-grid td', $current_panel).get(column_number) );
		
		$column_object
			.children('div')
			.each(function (index){
				Dashboard.Dashlets.sequenceRefresh.push( 
				    $(this).attr('alt')
				);
			});
	},
	
	/**
	 * Refresh Dashlets Content
	 * 
	 * @param Array sequence  The list of Dashlets
	 */
	refreshSequence: function () {
		var sequence = this.sequenceRefresh;
		
		if (sequence.length == 0){
			return false;
		}

		$.ajax({
			url: Dashboard.getUrl('DynamicAction', 'DynamicAction=displayDashlet&session_commit=1&to_pdf=1&id='+sequence[0])
		  , async: false
		})
		.success(function (response) {		
			$('#dashlet_entire_'+sequence[0]).html(response);
			Dashboard.Dashlets.sequenceRefresh.splice(0, 1);
			
			setTimeout('Dashboard.Dashlets.refreshSequence()', 500);
		});
	}
};
