<?php
namespace Dashboard;

/**
 * This class is intended for setup layout of the Page
 * @access public
 * @author Richlode Solutions
 */
class Layout {
    /**
     * @var string
     * The GUID of Page
     */
    private $pageGuid = null;
    
    /**
     * @var string
     * Type of layout
     */
    private $type = null;
    
    /**
     * @var integer
     * The count of columns
     */
    private $columnsCount = 0;
    
    /**
     * Self Instance storage
     * 
     * */
    private static $selfInstance = null;

    /**
     * Factory initialization.
     * 
     * @access public
     * @param string page_guid The GUID of page
     * @return Layout
     * @static
     */
    public static function getInstance($page_guid) 
    {
        if (!is_null(self::$selfInstance)){
            self::$selfInstance->setPageGuid($page_guid);
            return self::$selfInstance;
        }
        
        self::$selfInstance = new self();
        return self::getInstance($page_guid);
    }
    
    /**
     * Saves the layout configuration.
     * 
     * @access public
     * @return boolean
     * @ReturnType boolean
     */
    public function saveLayout() 
    {
        global $current_user;
        
        $pages        = Tabs::getConfiguration();
        $tab_guid     = $this->getPageGuid();
        $difference   = ($new_count = (int)$this->getColumnsCount()) - ($current_count = (int)count($pages[$tab_guid]['columns']));
        $column_width = (int)floor(100 / $new_count);
        
        if ($difference > 0) {
            for ($i = 0; $i < $new_count; $i++) {
                if (isset($pages[$tab_guid]['columns'][$i])) {
                    $pages[$tab_guid]['columns'][$i]['width'] = $column_width.'%';
                } else {                
                    $pages[$tab_guid]['columns'][] = array(
                        'width' => $column_width.'%',
                        'dashlets' => array(),
                    );
                }
            }
        } else if ($difference < 0) { 
            $last_column   = ($current_count + $difference);
            $move_dashlets = array();
            
            for ($i = 0; $i < $current_count; $i++) {
                if ($last_column <= $i) {
                    $move_dashlets = array_merge($move_dashlets, $pages[$tab_guid]['columns'][$i]['dashlets']);
                    unset($pages[$tab_guid]['columns'][$i]);
                }
            }
            
            foreach ($pages[$tab_guid]['columns'] as $key=>$column) {
                $pages[$tab_guid]['columns'][$key]['width'] = $column_width.'%';
                
                if (count($pages[$tab_guid]['columns']) == ($key + 1)) {
                    $pages[$tab_guid]['columns'][$key]['dashlets'] = array_merge(
                        $pages[$tab_guid]['columns'][$key]['dashlets'],
                        $move_dashlets
                    );
                }
            }
        }
        
        $current_user->setPreference('pages', $pages, 0, $_REQUEST['module']);
        
        return true;
    }

    /**
     * @access public
     * @param string page_guid
     */
    public function setPageGuid($page_guid) 
    {
        $this->page_guid = $page_guid;
        return $this;
    }

    /**
     * @access public
     * @return string
     */
    public function getPageGuid() 
    {
        return $this->page_guid;
    }

    /**
     * @access public
     * @param string type
     * @ParamType type string
     */
    public function setType($type) 
    {
        $this->type = $type;
    }

    /**
     * @access public
     * @return string
     * @ReturnType string
     */
    public function getType() 
    {
        return $this->type;
    }

    /**
     * @access public
     * @param int columnsCount
     */
    public function setColumnsCount($columnsCount) 
    {
        $this->columnsCount = $columnsCount;
        
        return $this;
    }

    /**
     * @access public
     * @return int
     * @ReturnType int
     */
    public function getColumnsCount() 
    {
        return $this->columnsCount;
    }
}
