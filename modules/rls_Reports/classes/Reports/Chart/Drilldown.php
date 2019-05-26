<?php

namespace Reports\Chart;

/**
 * This class is intended for operating Drildown function.
 * 
 * Includes:
 *  - Parser for URL
 *  - Composing URL for drilldown
 *  - Storage for replace by Patterns
 *  - Date range composer, ex: Quarter range, Month range.
 *  - Other fieldmappings
 *
 * @author Richlode Solutions
 */
class Drilldown
{
    /**
     * @AttributeType classes.Reports.Chart.Drilldown
     * The holder for self instance.
     */
    private static $selfInstance = null;

    /**
     * Number of grouping for current drilldown.
     * */
    private $numberGroupLevels = 1;

    /**
     * Property about drilldown ON.
     * */
    private $isDrilldown = false;

    /**
     * Prefix for search fields on listview.
     * */
    private $searchPrefix = '_advanced';

    /**
     * @AttributeType array
     * Holder for Search and Replace patterns in XML of Chart
     */
    private $searchReplaceXmlPattern = array();

    /**
     * @AttributeType array
     * Grouping setting for change url parameter name, check extension etc.
     */
    private $fieldNameMap = array(
        // Module, old field name, new field name
        'Users' => array(
            'user_name' => array(
                'name' => 'assigned_user_id',
                'is_extended' => true,
            ),
        ),
    );

    /**
     * Init basic params.
     * */
    public function __construct()
    {
        // init isDrilldown
        $settings = \Reports\Settings\Storage::getSettings();
        if (isset($settings['chart']['drilldown'])
            and $settings['chart']['drilldown']
        ) {
            $this->isDrilldown = true;
        }
    }

    /**
     *  Set drilldown option.
     *
     *  @param $value value to set 
     *
     *  @return self
     * */
    public function setDrilldown($value = null)
    {
        if ($value) {
            $this->isDrilldown = true;
        } else {
            $this->isDrilldown = false;
        }

        return $this;
    }

    /**
     * Get the list of search fields from advanced_search list view.
     *
     * @param string $module name of module for get fields
     *
     * @return array
     * */
    public function getAvailableSearchFields($module = '')
    {
        if (file_exists('custom/modules/'.$module.'/metadata/searchdefs.php')) {
            include 'custom/modules/'.$module.'/metadata/searchdefs.php';
        } elseif (file_exists('modules/'.$module.'/metadata/searchdefs.php')) {
            include 'modules/'.$module.'/metadata/searchdefs.php';
        } else {
            return array();
        }

        return $searchdefs[$module]['layout']['advanced_search'];
    }

    /**
     * Check field in advanced_search data on listview.
     *
     * @param string $module     name of module for get fields
     * @param string $field_name name of field for check
     *
     * @return bool
     * */
    public function isFieldAvailableForDrilldown($module = '', $field_name = '')
    {
        $list_fields = $this->getAvailableSearchFields($module);
        if (array_key_exists($field_name, $list_fields)) {
            return true;
        }

        return false;
    }

    /**
     * Return self instance.
     * 
     * Singleton realization.
     *
     * @return classes.Reports.Chart.Drilldown
     * @ReturnType classes.Reports.Chart.Drilldown
     */
    public static function getInstance()
    {
        if (self::$selfInstance instanceof self) {
            return self::$selfInstance;
        }

        self::$selfInstance = new self();

        return self::getInstance();
    }

    /**
     * Reset class settings to default.
     * */
    public static function resetReport()
    {
        self::$selfInstance = null;
    }

    /**
     * Get Prefix for search fields on listview.
     *
     * @param int number_group_levels number of groupings for current chart
     *
     * @return string
     * */
    public function setNumberGroupLevels($number_group_levels)
    {
        $this->numberGroupLevels = $number_group_levels;
    }

    /**
     * Get Prefix for search fields on listview.
     *
     * @return string
     * */
    public function getSearchPrefix()
    {
        return $this->searchPrefix;
    }

    /**
     * Get names of search fields on listview for all groupings.
     * 
     * @return array list of fields name
     */
    public function getGroupingsName()
    {
        $list_names = array();
        $grouping = \Reports\Data\Grouping::load();
        $number_group_levels = 0;
        foreach ($grouping->get() as $grouping) {
            if ($number_group_levels >= $this->numberGroupLevels) {
                break;
            }
            $list_names[] = $this->getAssociatedName(
                $grouping['field_name'],
                $grouping['module_of_field']
            );
            $number_group_levels++;
        }

        return array();
    }

    /**
     *  Check drilldown option.
     *
     *  @return bool
     * */
    public function isDrilldown()
    {
        return $this->isDrilldown;
    }

    /**
     * Check grouping on extention for search url.
     * 
     * @param int $group_index indes of current grouping
     *
     * @return mixed name of object for grouping (Chart/Drilldown) or false
     */
    public function isGroupingExtended($group_index)
    {
        $grouping = \Reports\Data\Grouping::load();
        if (($function_name = $grouping->getFunction($group_index))
            and ($object = $grouping->getFunctionObject($function_name))
        ) {
            $class_name = get_parent_class($object);

            return substr($class_name, strrpos($class_name, '\\') + 1);
        }

        $module_name = $grouping->getModule($group_index);
        $field_name = $grouping->getFieldname($group_index, $for_querry = false);
        if (isset($this->fieldNameMap[$module_name][$field_name]['is_extended'])
            and $this->fieldNameMap[$module_name][$field_name]['is_extended']
        ) {
            return $module_name;
        }

        return false;
    }

    /**
     *  Check search field of filter in fields of groupings.
     *
     *  @param string $field_name field name of filter
     *
     *  @return bool
     * */
    public function isFilterInGrouping($field_name = '')
    {
        $groupings_name = $this->getGroupingsName();
        if (in_array($field_name.$this->getSearchPrefix(), $groupings_name)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get module name for search url.
     * 
     * @return string module name
     * */
    public function getSearchModuleName()
    {
        $module = 'Home';
        $settings = \Reports\Settings\Storage::getSettings();
        if (isset($settings['data']['module_of_report']) and $settings['data']['module_of_report']) {
            $module = $settings['data']['module_of_report'];
        }

        return $module;
    }

    /**
     * Get associated search field name for listview from map.
     *
     * @param string $field_name field name 
     * @param string $module     module name
     *
     * @return string new field name
     * */
    public function getAssociatedName($field_name = '', $module = '')
    {
        if (isset($this->fieldNameMap[$module]) and isset($this->fieldNameMap[$module][$field_name]['name'])) {
            $field_name = $this->fieldNameMap[$module][$field_name]['name'];
        }

        return $field_name.$this->getSearchPrefix();
    }

    /**
     * Get search and replace data for url grouping.
     * 
     * @param string $search_key part of search url for search
     * @param array  $row        group data from querry
     */
    public function addUrlGroupingData($search_key, array $row)
    {
        if ($this->isDrilldown()) {
            $this->searchReplaceXmlPattern[$search_key] = $this->getUrlReplaceString($row);
        }
    }

    /**
     * Get url replace string for grouping.
     
     * @param array $row group data from querry
     *
     * @return string
     */
    public function getUrlReplaceString(array $row)
    {
        $replace_url = '';
        $grouping = \Reports\Data\Grouping::load();
        $number_group_levels = 0;
        foreach ($grouping->get() as $group_index => $group) {
            if ($number_group_levels >= $this->numberGroupLevels) {
                break;
            }
            if ($extention = $this->isGroupingExtended($group_index)) {
                $group_url = $this->generateReplaceUrlExtended($row, $number_group_levels, $extention);
            } else {
                $group_url = $this->generateReplaceUrl($row, $number_group_levels);
            }
            $replace_url .= '&' . $group_url;
            $number_group_levels++;
        }

        return $replace_url;
    }

    /**
     * Generate search url for current grouping.
     * 
     * @param array row data from querry
     * @param int group_index index of current grouping
     *
     * @return string
     */
    public function generateReplaceUrl(array $row, $group_index)
    {
        $parameter_name = '';
        $parameter_value = '';
        $grouping = \Reports\Data\Grouping::load();

        $parameter_name = $this->getAssociatedName(
            $grouping->getFieldname($group_index, $for_querry = false),
            $grouping->getModule($group_index)
        );
        $parameter_value = $row[$grouping->getQueriedName($group_index)];

        return $parameter_name.'='.$parameter_value;
    }

    /**
     * Generate search url for current grouping by date/user etc.
     * 
     * @param array row data from querry
     * @param int group_index index of current grouping
     * @param string extention name of object for extention
     *
     * @return string
     */
    public function generateReplaceUrlExtended(array $row, $group_index, $extention)
    {
        $url = '';

        $url = \Reports\Chart\Factory::loadDrilldown($extention)
                  ->getReplaceUrl($row, $group_index);

        return $url;
    }

    /**
     * Remove search links from xml chart content.
     *
     * @param string $content xml content of chart
     *
     * @return string parsed content
     * */
    public function removeDrilldownLinks($content = '')
    {
        if (!$this->isDrilldown()) {
            $content = preg_replace(
                array('/<link>(.*?)<\/link>/'),
                array('<link></link>'),
                $content
            );
        }

        return $content;
    }

    /**
     * Replace search url for groupings in xml content.
     * 
     * @param string $content xml content of chart
     *
     * @return string parsed content
     */
    public function replaceSearchUrl($content)
    {
        $list_search = array();
        $list_replace = array();
        foreach ($this->searchReplaceXmlPattern as $search => $replace) {
            $list_search[] = $search;
            $list_replace[] = $replace;
        }
        if ($this->isDrilldown()) {
            $content = str_replace(
                $list_search,
                $list_replace,
                $content
            );
        }

        return $content;
    }
}
