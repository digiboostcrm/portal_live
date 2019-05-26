<?php
namespace Reports\PDF;

/**
 * This class contains basic manipulations for PDF document
 *
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.PDF
 */
abstract class Basic implements Content
{
    /**
     * The storage for TCPDF instance
     */
    protected $tcpdfInstance = null;

    /**
     * The name of PDF file which could be saved or downloaded
     */
    private $nameOfPdf = null;

    /**
     * Path for save data.
     */
    public $pathToSave = 'cache/upload/';

    /**
     * Type images into pdf.
     */
    public $typeImage = '.png';

    /**
     * This method will init the TCPDF class which built-in into Sugar.
     *
     * Stores the instance of TCPDF in self
     * @access public
     * @return TCPDF
     * @ReturnType TCPDF
     */
    public function loadTcpdf()
    {
        require_once('include/tcpdf/tcpdf.php');

        $this->tcpdfInstance = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        return $this->tcpdfInstance;
    }

    /**
     * Return the path to PNG Image of Chart
     *
     * @return string
     */
    public final function getChartImageFilename() {
        require_once('include/SugarCharts/SugarChartFactory.php');
        $sugar_сhart = \SugarChartFactory::getInstance();
        if (!$sugar_сhart->supports_image_export) {
            throw new \Exception('The Image building is curently not supported by SugarChart', 25);
        }
        return $sugar_сhart->get_image_cache_file_name($sugar_сhart->getXMLFileName(\Reports\Settings\Storage::getFocus()->id));
    }


    /**
     * Return the path to PNG Image of Chart
     *
     * @return string
     */
    public final function getSummaryTable() {

        require_once('include/SugarCharts/SugarChartFactory.php');
        $sugar_сhart = \SugarChartFactory::getInstance();
        if (!$sugar_сhart->supports_image_export) {
            throw new \Exception('The Image building is curently not supported by SugarChart', 25);
        }

        \Reports\Settings\Storage::load();
        $chart_type = \Reports\Settings\Storage::getFocus()->chart_type;
        $chart       = \Reports\Chart\Factory::loadChart($chart_type);
        $chart->display(true);


        $sugar_сhart->xmlFile = $sugar_сhart->getXMLFileName(\Reports\Settings\Storage::getFocus()->id);
        $xmlStr = $sugar_сhart->processXML($sugar_сhart->xmlFile);
        $json = $sugar_сhart->buildJson($xmlStr);
        $sugar_сhart->saveJsonFile($json);


        $xmlContent = $sugar_сhart->generateXML();
        $sugar_сhart->saveXMLFile($sugar_сhart->xmlFile, $xmlContent);


        $jsone_array = json_decode($json,true);
        $SummaryTable = '';
        if(array_key_exists('values',$jsone_array)
            && is_array($jsone_array['values'])
            && !empty($jsone_array['values'])
        ){
            foreach($jsone_array['values'] as $row){
                $child_str = '';
                if(array_key_exists('values',$row)
                    && is_array($row['values'])
                    && !empty($row['values'])
                    && !in_array($row['label'],$jsone_array['label'])
                ){
                    foreach($row['values'] as $child_index=>$child){
                    $child_str.='<tr>
                                    <td>&nbsp;&nbsp;' . $jsone_array['label'][$child_index] . '</td>
                                    <td>' . $child . '</td>
                                 </tr>';
                    }
                }
                $SummaryTable.= '<table border="1" style="font-size: 16px;"class="reports-sheet" width="100%" cellpadding="1">
                                    <tr>
                                        <td>' . $row['label'] . '</td>
                                        <td>' . $row['gvalue'] . '</td>
                                    </tr>'.$child_str.'
                                 </table><br><br>';
            }
        }
        if(!empty($SummaryTable)){
            $SummaryTable = '<br><br>'. $SummaryTable . '';
        }

        return $SummaryTable;
    }


    /**
     * Return the HTML of Spreadsheet of Report.
     *
     * Here we use the Reports\Grid class.
     * @access public
     * @return string
     * @ReturnType string
     */
    public function getSpreadsheetHtml()
    {
        \Reports\Settings\Storage::load();
        $settings = \Reports\Settings\Storage::getSettings();
        $spreadsheet = \Reports\Grid\Factory::loadGrid($settings['grid']['type']);

        return $spreadsheet->display();
    }

    /**
     * Output the PDF document into Client (Browser)
     * @access public
     * @return string
     * @ReturnType string
     */
    public function outputPdf()
    {
        $name = preg_replace('/(\'|&#0*39;)/', '', $this->getNameOfPdf());
        $result = $this->tcpdfInstance->Output(
            $name, 
            'I'
        );

        return $result;
    }

    /**
     * Saves the PDF document to disk.
     * @access public
     * @param path The path for PDF file
     * @return boolean
     * @ParamType path
     * The path for PDF file
     * @ReturnType boolean
     */
    public function savePdfToDisk($path)
    {
        $result = $this->tcpdfInstance->Output(
            $path. '/' .$this->getNameOfPdf(),
            'F'
        );

        return $result;
    }

    /**
     * @access public
     * @param tcpdfInstance
     * @ParamType tcpdfInstance
     */
    public function setTcpdfInstance($tcpdfInstance)
    {
        $this->tcpdfInstance = $tcpdfInstance;
    }

    /**
     * @access public
     */
    public function getTcpdfInstance()
    {
        return $this->tcpdfInstance;
    }

    /**
     * @access public
     * @param nameOfPdf
     * @ParamType nameOfPdf
     */
    public function setNameOfPdf($nameOfPdf)
    {
        $this->nameOfPdf = $nameOfPdf;
    }

    /**
     * @access public
     */
    public function getNameOfPdf()
    {
        return $this->nameOfPdf;
    }
}
