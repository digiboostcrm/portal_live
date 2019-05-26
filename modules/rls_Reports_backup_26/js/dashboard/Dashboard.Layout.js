/**
 * This object is inteded for Layout handling
 * 
 * */
Dashboard.Layout = {
		
	/**
	 * Set Layout on the Page
	 * 
	 * @param object parameters		The parameters for setting of the Layout
	 * */	
	set: function (parameters) {
		$.get(
			Dashboard.getUrl('DashboardSaveLayout')
		  , parameters
		  , function (response){
				Dashboard.Tabs.refreshContent(parameters.tab_guid);
			}
		);
	}
};