<?php
namespace Reports\Chart;

/**
 * The class to represent Funnel type of charts
 * 
 * @access public
 * @author Richlode Solutions
 * @package Chart
 */
class None extends Basic 
{
    /**
     * Generates full code for chart
     * 
     * @return string
     * */
    public function display()
    {
        return '';
        /*global  $mod_strings;
        require_once('include/SugarCharts/SugarChartFactory.php');                 
        $focus = \Reports\Settings\Storage::getFocus();
        $sugarChart = \SugarChartFactory::getInstance();
        $sugarChart->base_url   = $this->getBaseUrl();
        $sugarChart->url_params = $this->getUrlParams();
        $sugarChart->setData(null);
        $sugarChart->setProperties('', $focus->name, 'stacked group by chart');
        $xmlFile = $sugarChart->getXMLFileName($focus->id);
        $xmlContent = $sugarChart->generateXML();

        $sugarChart->saveXMLFile($xmlFile, $xmlContent);
        return $sugarChart->display($focus->id, $xmlFile, '0', '0'); */
    }
}
