<?php
namespace Reports\PDF;

/**
 * This is standard type of PDF document for reports.
 * @access public
 * @author Richlode Solutions
 * @package classes.Reports.PDF
 */
class DefaultType extends Basic 
{
    /**
     * This method will be used for building of contents for PDF.
     * 
     * Adding the Image for Diagram.
     * Adding the Spreadsheet for Table information.
     * @access public
     */
    public function generateContent() 
    {
        $this->loadTcpdf();
        $this->initPdf();
        $focus = \Reports\Settings\Storage::getFocus();
        
        if ($focus->chart_type != 'None')
            $this->addChartIntoPdf();
        $this->tcpdfInstance->writeHTML( $this->getSpreadsheetHtml() );
    }

    /**
     * Set default setting and header for pdf.
     * 
     * */
    function initPdf()
    {
        global $mod_strings;

        $report = \Reports\Settings\Storage::getFocus();
        $this->setNameOfPdf($report->name . '.pdf');

        $this->tcpdfInstance->SetSubject('Report');
        $this->tcpdfInstance->setMargins(8,5,8);
        $this->tcpdfInstance->setPrintHeader(false);
        $this->tcpdfInstance->setPrintFooter(false);
        $this->tcpdfInstance->SetAutoPageBreak(true,10);
        $this->tcpdfInstance->SetFillColor(241, 241, 242);
        $this->tcpdfInstance->SetFont('helvetica', '', 10);
        $this->tcpdfInstance->AddPage('L');
        
        // set header
        $this->tcpdfInstance->writeHTML( 
            '<div style="font-size: 20px;">' .
              $mod_strings['LBL_NAME'] . ': ' . $report->name . '<br>' .
              $mod_strings['LBL_DESCRIPTION'] . ': ' .$report->description .
             '</div>'
         );
    }

    /**
     *  Add chart image into pdf.
     *
     * */
    function addChartIntoPdf()
    {
        $img = $this->getChartImageFilename();
      
        if (file_exists($img)) {
            //$tcpdf->Image($img, 5, 60, 200, ($report->chart_type == 'Pie' ? 150 : 100));
            $this->tcpdfInstance->Image($img, 5, 50, 280);
            if (isset($_REQUEST['legendData'])) {
                $this->tcpdfInstance->SetXY(10, 190);
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
    public function getSpreadsheetHtml() 
    {
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
    public function fixSpreadsheetHtml($spreadsheet)
    {
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
    public function fixHistoryHtml($html_content)
    {
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
