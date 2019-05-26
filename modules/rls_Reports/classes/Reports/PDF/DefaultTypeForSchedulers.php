<?php

namespace Reports\PDF;

/**
 * This is standard type of PDF document for reports.
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.PDF
 */
class DefaultTypeForSchedulers extends Basic {
    /**
     * This method will be used for building of contents for PDF.
     * 
     * Adding the Image for Diagram.
     * Adding the Spreadsheet for Table information.
     * @access public
     */
    public function generateContent() {
        $this->loadTcpdf();
        $this->initPdf();
        $focus = \Reports\Settings\Storage::getFocus();

        if ($focus->chart_type != 'None')
            $this->addChartIntoPdf();
        $this->tcpdfInstance->writeHTML($this->getSpreadsheetHtml());
    }

    function getImageParams() {
        $report = \Reports\Settings\Storage::getFocus();
        \Reports\Settings\Storage::load();
        $chart = \Reports\Chart\Factory::loadChart($report->chart_type);
        $chart->display();

        $json = file_get_contents($this->getFilename($report->id, 'js'), FILE_USE_INCLUDE_PATH);
        $chartConfig = $this->getReportChartConfigParams($report->chart_type);
        $chartConfig['image_export_type'] = (extension_loaded('gd') && function_exists('gd_info')) ? "png" : "jpg";

        $params['json'] = json_decode($json, true);
        $params['css'] = $this->getReportChartCss();
        $params['chartConfig'] = $chartConfig;

        return $params;
    }

    function getNodeJSHostname() {
        $configuratorObj = new \Configurator();
        $configuratorObj->loadConfig();
        return $configuratorObj->config['nodejshost'];
    }

    function nodeJSmode() {
        $configuratorObj = new \Configurator();
        $configuratorObj->loadConfig();
        return $configuratorObj->config['switch_mode'];
    }

    function getChartdata() {

        $url = $this->getNodeJSHostname();
        $params = $this->getImageParams();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $header = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $result = curl_exec($ch);
        $chartData = json_decode($result, true);
        curl_close($ch);
        return $chartData['image'];
    }

    function createChartImage($img) {

        if (!$imageStr = $this->getChartdata()) {
            return false;
        }
        $filename = pathinfo($img, PATHINFO_BASENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), array('jpg', 'png', 'jpeg'))) {
            return false;
        }
        $image = str_replace(" ", "+", $imageStr);
        $data = substr($image, strpos($image, ","));
        if (sugar_mkdir(sugar_cached("images"), 0777, true)) {
            $filepath = sugar_cached("images/$filename");
            file_put_contents($filepath, base64_decode($data));
            if (!verify_uploaded_image($filepath)) {
                unlink($filepath);
                return false;
            }
        } else {
            return false;
        }
    }

    function getFilename($id, $ext) {
        return $GLOBALS['sugar_config']['cache_dir'] .
                "xml/" . $GLOBALS['current_user']->getUserPrivGuid() . "_{$id}." . $ext;
    }

    function getReportChartCss() {
        $config = $this->getReportChartConfigProperties();
        $css['gridLineColor'] = str_replace("0x", "#", $config->gridLines);
        $css['font-family'] = (string) $config->labelFontFamily;
        $css['color'] = str_replace("0x", "#", $config->labelFontColor);
        return $css;
    }

    function getReportChartConfigProperties() {

        $path = \SugarThemeRegistry::current()->getImageURL('sugarColors.xml', false);

        if (!file_exists($path)) {
            $GLOBALS['log']->debug("Cannot open file ($path)");
        }
        $xmlstr = file_get_contents($path);
        $xml = new \SimpleXMLElement($xmlstr);
        return $xml->charts;
    }

    function getReportChartConfigParams($chartType) {

        switch ($chartType) {
            case "Funnel":
                return array(
                    "funnelType" => "basic",
                    "tip" => "name",
                    "chartType" => "funnelChart"
                );
                break;
            case "Pie":
                return array(
                    "pieType" => "basic",
                    "tip" => "name",
                    "chartType" => "pieChart"
                );
                break;
            case "Columns":
                return array(
                    "orientation" => "vertical",
                    "barType" => "stacked",
                    "tip" => "name",
                    "chartType" => "barChart",
                    "scroll" => true
                );
                break;
            default:
                break;
        }
    }

    /**
     * Set default setting and header for pdf.
     * 
     * */
    function initPdf() {
        global $mod_strings;

        $report = \Reports\Settings\Storage::getFocus();
        $this->setNameOfPdf($report->name . '.pdf');

        $this->tcpdfInstance->SetSubject('Report');
        $this->tcpdfInstance->setMargins(8, 5, 8);
        $this->tcpdfInstance->setPrintHeader(false);
        $this->tcpdfInstance->setPrintFooter(false);
        $this->tcpdfInstance->SetAutoPageBreak(true, 10);
        $this->tcpdfInstance->SetFillColor(241, 241, 242);
        $this->tcpdfInstance->SetFont('helvetica', '', 10);
        $this->tcpdfInstance->AddPage('L');

        // set header
        $this->tcpdfInstance->writeHTML( 
            '<div style="font-size: 20px;">' .
              translate('LBL_NAME', 'rls_Reports') . ': ' . $report->name . '<br>' .
              translate('LBL_DESCRIPTION', 'rls_Reports') . ': ' .$report->description .
             '</div>'
         );
    }

    /**
     *  Add chart image into pdf.
     *
     * */
    function addChartIntoPdf() {
        $img = $this->getChartImageFilename();

        // Verify the existence of an image file and creates a chart when the image file does not exist
        //if (!file_exists($img)) {
        //    $this->createChartImage($img);
        //}
        // always creates an image, regardless of the existence of an image file
        if ($this->nodeJSmode()) {
            $this->createChartImage($img);
            $this->tcpdfInstance->Image($img, 5, 30, 280);
            if (isset($_REQUEST['legendData'])) {
                $this->tcpdfInstance->SetXY(10, 170);
                $this->tcpdfInstance->writeHTML(
                        $this->fixHistoryHtml(
                                html_entity_decode($_REQUEST['legendData'])
                        )
                );
            }
            $this->tcpdfInstance->AddPage();
        }
    }

    /**
     * Return the HTML of Spreadsheet of Report.
     * 
     * Here we use the Reports\Grid class.
     * @access public
     * @return string
     * @ReturnType string
     */
    public function getSpreadsheetHtml() {
        \Reports\Settings\Storage::load();
        $settings = \Reports\Settings\Storage::getSettings();
        if(empty($settings['grid'])){
            return '';
        }
        $focus = \Reports\Settings\Storage::getFocus();
        if (($focus->chart_type == 'None') && (count($settings['data']['grouping'][0]) == 3))
            $settings['grid']['type'] = 'NoGrouped';
        $spreadsheet = \Reports\Grid\Factory::loadGrid($settings['grid']['type']);

        return $this->fixSpreadsheetHtml($spreadsheet->display());
    }

    /**
     * Fix spreadsheet html for inserting into pdf.
     *  
     * @param string $spreadsheet html of spreadsheet
     * return string fixed html of spreadsheet
     * */
    public function fixSpreadsheetHtml($spreadsheet) {
        $spreadsheet = preg_replace(
            array(
                '/<h1>/',
                '/<\/h1>/',
            
                '/<th width="(.*?)">/',
                '/<\/th/',
                
                '/<table /',
            ),
            array(
                '<div style="font-size: 24px;"><b>',
                '</b></div>',
            
                '<td><b>',
                '</b></td',
                
                '<table border="1" style="font-size: 16px;"',
                
            ),
            $spreadsheet
        );
        return $spreadsheet;   
    }

    /**
     *  Fix html content for normal converting into PDF.
     *
     * @param string html_content The contents of html
     * @return string
     * */
    public function fixHistoryHtml($html_content) {
        $html_content = preg_replace(
            array(
                '/class="label"/',
            ),
            array(
                'style="font-size: 20px;"',
                
            ),
            html_entity_decode($html_content)
        );
        return $html_content;
    }

    
    
}
