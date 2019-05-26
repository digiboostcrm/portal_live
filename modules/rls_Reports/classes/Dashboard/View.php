<?php
namespace Dashboard;

/**
 * This class is intended for displaying all of the elements of dashboard.
 * Tabs, Controls, Panels, Popups, Pages Contents and so.
 * @access public
 * @author Richlode Solutions
 */
class View {
    /**
     * @AttributeType array
     * This array will store the HTML for all pages
     */
    private $pageStack = array();
    
    /**
     * @AttributeType array
     * The parameters for every tab of the dashboard
     */
    private static $tabsStack = array();

    /**
     * Return the HTML code for Tabs
     * 
     * @access public
     * @return string
     */
    public function displayTabs() 
    {
        $tabs = array();
        
        foreach ($this->getTabsStack() as $tab_info) {
            $remove_button = null;
            if (($tab_order = $tab_info['order']) > 0) {
                $remove_button = '<span class="ui-icon ui-icon-close" alt="'. $tab_info['guid'] .'">Remove Tab</span>';
            }
            
            $tabs[!isset($tabs[$tab_order]) ? $tab_order : rand(555, 999)] = '
                <li alt="'. $tab_info['guid'] .'">
                    <a href="index.php?module=rls_Reports&action=DashboardGetTabContent&tab_guid='. $tab_info['guid'] .'&sugar_body_only=true">'. $tab_info['title'] .'</a> 
                    '.$remove_button.'
                </li>
            ';
        }
        
        ksort($tabs);
        $tabs[] = '
            <li class="ui-state-default ui-corner-top" id="dashboard_add_tab">
                <a href="index.php?module=rls_Reports&action=DashboardNewTabIndicator">+</a> 
            </li>
        ';
        
        return implode("\n", $tabs);
    }

    /**
     * The method to indicate the empty dashboard
     * 
     * @access public
     * @return string
     */
    public function displayNoData() 
    {
        // Not yet implemented
    }

    /**
     * Return HTML code for controls of the Page
     * 
     * @access public
     * @param string $tab_guid      The GUID of the tab
     * @return string
     */
    public function displayControls($tab_guid) 
    {
        global $current_user;
        $tab_info = Tabs::getConfiguration($tab_guid);
        $count_of_columns = count($tab_info['columns']);
        
        return '
            <select class="dashboard_page_layout" name="dashboard_page_layout['.$tab_guid.']">              
              <option value="1" '. ($count_of_columns == 1 ? 'selected' : null) .' class="dashboard-layout-one">'.translate('LBL_1_COLUMN', 'rls_Reports').'</option>    
              <option value="2" '. ($count_of_columns == 2 ? 'selected' : null) .' class="dashboard-layout-two">'.translate('LBL_2_COLUMN', 'rls_Reports').'</option>    
              <option value="3" '. ($count_of_columns == 3 ? 'selected' : null) .' class="dashboard-layout-three">'.translate('LBL_3_COLUMN', 'rls_Reports').'</option>    
            </select>
            <button class="dashboard_add_dashlet" type="button">'.translate('LBL_ADD_DASHLET', 'rls_Reports').'</button>
            
        ' . (($current_user->is_admin) ? '<button
                                              class="dashboard_copy_to_users"
                                              type="button"
                                              onclick="Dashboard.openUsersPopup()"
                                          >
                                              '.translate('LBL_COPY_TO_USERS', 'rls_Reports')
                                          .'</button>' : '');
    }

    /**
     * Reserved for displaying appended scripts
     * 
     * @access public
     * @return string
     */
    public function displayAppendedScripts() 
    {
        global $current_user;
        
        return '
            <script type="text/javascript">
                var activePage = 0;
                current_user_id = "'. $current_user->id .'";
                var moduleName = "'. $_REQUEST['module'] .'";
            
                initMySugar();
                initmySugarCharts();
            </script>        
        ';
    }

    /**
     * Getting template and return the content
     * 
     * @access public
     * @return string
     */
    public function getTemplate() 
    {
        $view =& $this;
        
        require dirname(__FILE__). '/../../tpls/dashboard.php';
    }
    
    /**
     * Sets the stack of the tabs list
     * 
     * @access public
     * @param array pageStack
     */
    public function setPageStack(array $pageStack) 
    {
        $this->pageStack = $pageStack;
    }

    /**
     * Returns the stack of all Tabs
     * 
     * @access public
     * @return array
     */
    public function getPageStack($index = null) 
    {
        return $this->pageStack;
    }

    /**
     * @access public
     * @param array tabsStack
     */
    public function setTabsStack(array $tabsStack) 
    {
        self::$tabsStack = $tabsStack;
    }

    /**
     * @access public
     * @return array
     */
    public function getTabsStack() 
    {
        return self::$tabsStack;
    }
}

