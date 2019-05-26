<?php
namespace Dashboard;

/**
 * This class is intended for handling Tabs configuration.
 * 
 * Adding Tabs.
 * Removing Tabs.
 * Ordering Tabs.
 * @access public
 * @author Richlode Solutions
 * 
 * TODO: implement methods retrieve, and save.
 */
class Tabs 
{
    /**
     * Returns configuration for Tabs of Dashboard.
     * 
     * Retrieving data from User Preferences.
     * 
     * @access public
     * @param string $guid  The GUID for Tab. To get configuration directly for the one.
     * @return array
     */
    public static function getConfiguration($guid = null) 
    {
        global $current_user;
        $configuration = array();
        
        if (!$configuration = $current_user->getPreference('pages', $_REQUEST['module'])) {
            self::addEmptyTab();
            $configuration = self::getConfiguration();
        }
        
        if (!is_null($guid) && isset($configuration[$guid])) {
            return $configuration[$guid];
        }
        
        return $configuration;
    }

    /**
     * Adding an empty Tab to Dashboard.
     * 
     * Appends an item to array of the pages User Preferences for Reports and save it.
     * 
     * @access public
     * @return string
     * @static
     */
    public static function addEmptyTab() 
    {
        global $current_user;
        
        if (!is_array($pages = $current_user->getPreference('pages', $_REQUEST['module']))) {
            $pages = array();
        }
        
        $tab_guid  = create_guid(); 
        $empty_tab = array(
           $tab_guid => array(
                'guid' => $tab_guid,
                'order' => count($pages),
                'columns'    => array(
                    array(
                        'width' => '50%',
                        'dashlets' => array(),
                    ),
                    array(
                        'width' => '50%',
                        'dashlets' => array(),
                    ),
                ),
                'numColumns' => '2',
                'pageTitle'  => translate('LBL_NO_CAPTION', 'rls_Reports'),
            )
        );
        
        $current_user->setPreference(
            'pages', 
            array_merge($pages, $empty_tab), 
            0, 
            $_REQUEST['module']
        );

        return $tab_guid;
    }
    
    /**
     * Removes all Tabs/Pages
     * 
     * @return boolean
     */
    public static function removeAll() 
    {
        global $current_user;
        
        $current_user->setPreference(
            'pages', 
            array(), 
            0, 
            $_REQUEST['module']
        );
        
        return true;
    }
    
    /**
     * Saves the content for Tab.
     * 
     * Saving dashlets columns with GUIDs.
     * @access public
     * @param int tab_number The number fur tab
     * @param array content Content for Page
     * @return boolean
     * @static
     */
    public static function saveContent($tab_number, array $content) 
    {
        // Not yet implemented
    }
    
    /**
     * Sets the caption for Tab
     * 
     * @param string $guid  The GUID of the Tab
     * @param string $caption  The catpion value of the Tab
     * @return boolean
     * @static
     */
    public static function setCaption($guid, $caption)
    {
        global $current_user;
        
        if (!$guid) {
            return false;
        }
        
        if (
            !is_array($pages = $current_user->getPreference('pages', $_REQUEST['module']))
            && !isset($pages[$guid])
        ) {
            $pages = array();
        }
        
        $pages[$guid]['pageTitle'] = $caption;
        
        $current_user->setPreference(
            'pages', 
            $pages, 
            0, 
            $_REQUEST['module']
        );
        
        return true;
    }
    
    /**
     * Removes page from User Preferences.
     *
     * @access public
     * @param string $guid  The GUID of the Tab
     * @return boolean
     * @static
     */
    public static function removeTab($guid) 
    {
        global $current_user;
        
        if (!$guid) {
            return false;
        }
        
        if (!is_array($pages = $current_user->getPreference('pages', $_REQUEST['module']))) {
            $pages = array();
        }
        
        unset($pages[$guid]);
        
        $current_user->setPreference(
            'pages', 
            $pages, 
            0, 
            $_REQUEST['module']
        );
        
        return true;
    }

    /**
     * Sets the order for tabs.
     * 
     * Usage examples:
     * <code>
     *     Tabs::setOrder(array(
     *         //'tab_guid' => 'order',
     *         '73fffa41-9253-e356-dd77-4f782e9f1f76' => '3',
     *         '82611aef-b055-48d2-97ec-694853cb7e01' => '0',
     *     ));
     * </code>
     * 
     * @access public
     * @param array ordering_settings The array of settings for ordering
     * @return boolean
     * @static
     */
    public static function setOrder(array $ordering_settings) 
    {
        global $current_user;
        $tab_list = self::getConfiguration();
        
        foreach ($ordering_settings as $guid=>$index) {
            $tab_list[$guid]['order'] = $index;
        }
        
        $current_user->setPreference(
            'pages', 
            $tab_list, 
            0, 
            $_REQUEST['module']
        );
        
        return true;
    }
}
