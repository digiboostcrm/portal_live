<?php
namespace Reports\Chart;

/**
 * The class to represent Funnel type of charts
 * 
 * @access public
 * @author Richlode Solutions
 * @package Chart
 */
class Columns extends Basic 
{
    /**
     * Returns grouping parameters for chart
     * 
     * @return array
     */
    public function getGroupBy() 
    {        
        return array(
            'first_title',
            'second_title'
        );
    }
    
    /**
     * Builds a contains for setting Dataset into SugarCharts Engine
     * 
     * @return self
     */
    protected function buildDataset()
    {
        $store     = new \Reports\Data\Collection();
        $settings  = \Reports\Settings\Storage::getSettings();
        $grouping  = \Reports\Data\Grouping::load();
        $summaries = \Reports\Data\Summarizing::load();
        $drilldown = \Reports\Chart\Drilldown::getInstance();
        $criterion = \Reports\Data\Criterion::getInstance()
            ->setSettings($settings)
            ->buildCriteria();        
        
        if (!$categroies_group = $criterion->getCategoriesByGrouping($grouping)) {
            return $this;
        }

        $index         = $grouping->get(1) ? 1 : 0;
        $summary_field = 
            $summaries->getQueriedName(
                $settings['chart']['datastore']['summariesIndex']
            );
        
        $groupBy = $this->getGroupBy();
        foreach ($categroies_group as $category_info) {
            $criterion->setAdditionalGrouping(array(0, $index ? $index : false));
            $concat_section = $grouping->getQueriedName($index);
            $collection     = $store->getRows(
                $criterion
                    ->setAdditionalWhere($category_info['additionalWhere'])
                    ->applySummaries()
            );
            
            $new_part = array();
            foreach ($collection as $row) {
                if (!$row[$summary_field]) continue;

                $drilldown->addUrlGroupingData(
                    '&' . $groupBy[0] . '=' . urlencode($row[$grouping->getQueriedName(0)]) . '&' .
                          $groupBy[1] . '=' . urlencode($row[$concat_section]),
                    $row
                );
                  
                $new_part[] = array(
                    'total' => $row[$summary_field],
                    $groupBy[0] => $this->setSectionTitle($row),
                    'key' => stripcslashes($row[$grouping->getQueriedName(0)]),
                    $groupBy[1]  => stripcslashes($grouping->getTitleByValue($index, $row[$concat_section])),
                    $groupBy[1].'_dom_option'  => stripcslashes($row[$concat_section]),
                );
            }
            
            $this->dataset = array_merge($this->dataset, $new_part);
        }
        
        return $grouping->sortChartDataset($this);
    }
    
    /**
     * Generates full code for chart
     * 
     * @return string
     * */
    public function display($only_build_xml = false)
    {
        global $current_user, $mod_strings;
        require_once('include/SugarCharts/SugarChartFactory.php');
                 
        $focus = \Reports\Settings\Storage::getFocus();
        $settings = \Reports\Settings\Storage::getSettings();
        $drilldown = \Reports\Chart\Drilldown::getInstance();
        $drilldown->setNumberGroupLevels(count($this->getGroupBy()));
        $guid  = $focus->id;
        
        $sugarChart = \SugarChartFactory::getInstance();
        $sugarChart->base_url   = $this->getBaseUrl();
        $sugarChart->url_params = $this->getUrlParams();
        $sugarChart->group_by   = $this->getGroupBy();
        
        //FIXME: JS error appears when it uncommented
        //$sugarChart->setColors($settings['chart']['colors']);
        
        $sugarChart->setData(
            $this
              ->buildDataset()
              ->getDataset()
        );
        $this->getDataFormatCurrency();
        (!empty($_SESSION['name_summaried_field'])) ? $format_mytotal = currency_format_number($sugarChart->getTotal()) : $format_mytotal = $sugarChart->getTotal();
        $sugarChart->setProperties($mod_strings['LBL_TOTAL'] . ': ' . $format_mytotal, $focus->name, 'stacked group by chart');
        $xmlFile = $sugarChart->getXMLFileName($guid);
        
        $xmlContent = $sugarChart->generateXML();
        $xmlContent = $drilldown->replaceSearchUrl($xmlContent);
        $xmlContent = $drilldown->removeDrilldownLinks($xmlContent);
        
        $sugarChart->saveXMLFile($xmlFile, $xmlContent);
        if($only_build_xml){
            return 'XML generated';
        }
        return $sugarChart->display($guid, $xmlFile, '100%', '800'); 
    }



}




