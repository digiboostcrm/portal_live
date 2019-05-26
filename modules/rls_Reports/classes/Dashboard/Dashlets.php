<?php
namespace Dashboard;

/**
 * This class is intended for handling of Dashlets configuration on the current Page/Tab.
 * 
 * @access public
 * @author Richlode Solutions
 */
class Dashlets 
{    
    /**
     * The GUID of Page
     * 
     * @var string
     */
    private $pageGuid = null;

    /**
     * Constructor
     * 
     * @param string $page_guid  The GUID of the Page
     */
    public function __construct($page_guid)
    {
        $this->setPageGuid($page_guid);
        $this->getConfiguration();
    }
    
    /**
     * Returns the HTML for all Dashlets on the Page
     * 
     * @return string
     */
    public function getHtmlForDashlets()
    {
        $dashlets_html = null;
        $tab_conf      = Tabs::getConfiguration($page_guid = $this->getPageGuid());
       
        if (!isset($tab_conf['columns']) || !is_array($tab_conf['columns'])) {
            throw new Exception('<b>Fatal Error</b>: No columns for the page');
        }
        
        $columns_code_list = array();
        foreach ($tab_conf['columns'] as $key=>$column) {
            $dashlets_divs_list = array();
            
            foreach ($column['dashlets'] as $dashlet_guid) {
                $dashlets_divs_list[] = $this->getCodeForOne($dashlet_guid);
            }
            
            $columns_code_list[] = '<td class="dashboard-sortable" width="'. $column['width'] .'">'. implode("\n", $dashlets_divs_list) .'</td>';
        }
        
        $dashlets_html  = '<table class="dashboard-dashlets-grid" border="0" width="100%" cellspacing="7" alt="'. $page_guid .'">';
        $dashlets_html .= ' <tr>';
        $dashlets_html .= implode("\n", $columns_code_list);
        $dashlets_html .= ' </tr>';
        $dashlets_html .= '</table>';
         
        return $dashlets_html;
    }
    
    /**
     * Returns the HTML Code for Dashlet
     * 
     * @return string
     * @param string $guid  The GUID of dashlet
     */
    public function getCodeForOne($guid)
    {
        if (!$dashlet_info = $this->getConfiguration($guid)){
            return false;
        }
        
        $dashletHtml = '
        <div id="dashlet_'.$guid.'" alt="'. $guid .'" class="dashletPanel">
            <div id="dashlet_entire_'.$guid.'">';
                $module = $dashlet_info['module'];
                require_once($dashlet_info['fileLocation']);
                
                $dashlet = new $dashlet_info['className']($guid, (isset($dashlet_info['options']) ? $dashlet_info['options'] : array()));
            
                $lvsParams = array();
                if(!empty($dashlet_info['sort_options'])){
                    $lvsParams = $dashlet_info['sort_options'];
                }
                $dashlet->process($lvsParams);
            
                if($dashlet->hasScript) {
                    $dashletHtml .= $dashlet->displayScript();
                }
                $dashletHtml .= $dashlet->getHeader();
                //$dashletHtml .= $dashlet->display();
                $dashletHtml .= '
                    <script type="text/javascript">
                        Dashboard.Dashlets.sequenceRefresh.push(\''. $dashlet->id .'\');
                    </script>
                ';
                $dashletHtml .= $dashlet->getFooter();
                $dashletHtml .= '
            </div>
        </div>';
        
        return $dashletHtml;
    }
    
    /**
     * Return the settings for dashlets.
     * 
     * Retrieves from the User Preferences.
     * 
     * @param string $guid  The GUID of Dashlet. To get configuration directly for the one
     * @return array
     */
    public function getConfiguration($guid = null)
    {
        global $current_user;
        $configuration = array();
        
        if (!$configuration = $current_user->getPreference('dashlets', $_REQUEST['module'])) {
            $this->addEmptyDashlet();
            $configuration = $this->getConfiguration();
        }
        
        if (!is_null($guid)) {
            if (!isset($configuration[$guid])){
                $this->removeLinkFromPage($guid);
                return false;
            }
            
            return $configuration[$guid]; 
        }
        
        return $configuration;
    }
    
    /**
     * This method adds an empty dashlet to the top of the Page
     * 
     * @access public  
     * @return boolean
     */
    public function addEmptyDashlet() 
    {
        global $current_user;
        
        if (!is_array($dashlets = $current_user->getPreference('dashlets', $_REQUEST['module']))) {
            $dashlets = array();
        }
        
        if (!$page_guid = $this->getPageGuid()) {
            return false;
        }
        
        $dashlet_guid  = create_guid();
        $empty_dashlet = array(
            $dashlet_guid => array(
                'className' => 'rls_MyReports',
                'module' => 'rls_Reports',
                'forceColumn' => 0,
                'fileLocation' => 'modules/rls_Reports/Dashlets/MyReports/rls_MyReports.php',
                'options' => array(
                    'title' => 'No Title',
                    'report_c' => null,
                    'rls_reports_id_c' => null,
                    'autoRefresh' => -1,
                )
            )
        );
        
        $pages    = $current_user->getPreference('pages', $_REQUEST['module']);
        $current  = $pages[$page_guid]['columns'][0]['dashlets'];        
        
        $dashlets_guid_list = array_merge(array($dashlet_guid), $current); 
        $pages[$page_guid]['columns'][0]['dashlets'] = $dashlets_guid_list;
        
        $current_user->setPreference('pages', $pages, 0, $_REQUEST['module']);        
        $current_user->setPreference('dashlets', array_merge($dashlets, $empty_dashlet), 0, $_REQUEST['module']);
        
        return $dashlet_guid;
    }
    
    /**
     * Removes the Link from Tab
     * 
     * @param string $guid    The GUID value for the Dashlet
     * @return boolean
     */
    public function removeLinkFromPage($guid)
    {
        global $current_user;
        
        $pages            = Tabs::getConfiguration();
        $dashlets_on_page = array();
        $tab_guid         = $this->getPageGuid();
                
        foreach ($pages[$tab_guid]['columns'] as $key=>$column) {
            if (in_array($guid, $column['dashlets'])){
                foreach ($column['dashlets'] as $link_to_guid) {
                    if ($link_to_guid != $guid) {
                        $dashlets_on_page[] = $link_to_guid;
                    }
                }
                
                $pages[$tab_guid]['columns'][$key]['dashlets'] = $dashlets_on_page;
                
                $current_user->setPreference(
                    'pages', 
                    $pages, 
                    0, 
                    $_REQUEST['module']
                );
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Saves the position for Dashlet.
     * 
     * @access public
     * @param string positions  This is an array for parameters for save Positions
     */
    public function savePosition($parameters) 
    {
        if (
            !isset($parameters['guid'])
            || !isset($parameters['column'])
            || !isset($parameters['index'])
            || !$this->removeLinkFromPage($parameters['guid'])
        ) {
            return false;
        }
        
        global $current_user;
        $dashlets_on_page = array();
        $dashlet_pinned   = false;
        
        $pages    = Tabs::getConfiguration();
        $tab_guid = $this->getPageGuid();
        
        if (!isset($pages[$tab_guid]['columns'][$parameters['column']])) {
            return false;
        }
        
        if ($current_dashlets = $pages[$tab_guid]['columns'][$parameters['column']]['dashlets']) {
            foreach ($current_dashlets as $key=>$link_to_guid) {
                if ($key == $parameters['index']) {
                    $dashlets_on_page[] = $parameters['guid'];
                    $dashlet_pinned     = true;
                }
                
                $dashlets_on_page[] = $link_to_guid;            
            }
        } 
        
        if (
            !$current_dashlets
            || !$dashlet_pinned
        ) {
            $dashlets_on_page[] = $parameters['guid'];
        }
        
        $pages[$tab_guid]['columns'][$parameters['column']]['dashlets'] = $dashlets_on_page;
        $current_user->setPreference('pages', $pages, 0, $_REQUEST['module']);
        
        return true;
    }

    /**
     * Sets the GUID of the page
     * 
     * @access public
     * @param string page_guid
     */
    public function setPageGuid($page_guid)
    {
        $this->pageGuid = $page_guid;
    }

    /**
     * Returns the GUID of the page
     * 
     * @access public
     * @return string
     */
    public function getPageGuid() 
    {
        return $this->pageGuid;
    }
    
    /**
     * Remove all dashlets
     * 
     * If page GUID specified only dashlets from this page will be removed.
     * 
     * @return boolean
     */
    public function removeAll() 
    {
        global $current_user;
                
        if ($page_guid = $this->getPageGuid()) { 
            $pages           = Tabs::getConfiguration();
            
            $dashlets        = $this->getConfiguration();
            $guids_to_remove = array();
            
            foreach ($pages[$page_guid]['columns'] as $key=>$column) {
                $guids_to_remove = array_merge($guids_to_remove, $column['dashlets']);
            }
            
            foreach ($guids_to_remove as $guid) {
                unset($dashlets[$guid]);
            }
        } else {
            $dashlets = array();
        }
        
        $current_user->setPreference('dashlets', $dashlets, 0, $_REQUEST['module']);
        
        return false;
    }
}
