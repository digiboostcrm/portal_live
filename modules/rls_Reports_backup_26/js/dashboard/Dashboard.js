/**
 * This Object is intended for Dashboard interface handling
 * 
 */
var Dashboard = {
    /**
     * Return valid URL for AJAX
     * 
     * @param string params		The URL parameters
     * */
    getUrl: function(action, params)
    {
      var url = '';
      url = 'index.php?module=rls_Reports&action='+ action +'&'+ params +'&sugar_body_only=true';
    
      return url;
    },

    /**
     * Open Users popup for copy dashboards settings.
     */
    openUsersPopup: function()
    {
        var popupRequestData = {
            "call_back_function" : "Dashboard.setUsersConfig",
            "form_name" : "EditView",
            "field_to_name_array" : {
              "id" : "user_id"
            }
        };
        open_popup('Users', 800, 850, '', true, true, popupRequestData, 'MultiSelect', true);
    },

    /**
     *  Set current_user config of dashboard for selected users.
     *
     * */
    setUsersConfig: function(popupReplyData)
    {
        $.post('index.php?module=rls_Reports&action=DashboardCopyCofiguration&sugar_body_only=true',
            {'selection_list': popupReplyData.selection_list},
            function(response) {
                alert(response);
            }
        );
    }
    
    
};
