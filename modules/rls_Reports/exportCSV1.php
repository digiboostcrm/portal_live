<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once 'include/TimeDate.php';
$curdate = TimeDate::getNow();
Reports\Settings\Storage::setFocus($this->bean);
Reports\Settings\Storage::load();
$settings = Reports\Settings\Storage::getSettings();

if (isset($settings['grid']['type'])) {
  $spreadsheet = Reports\Grid\Factory::loadGrid($settings['grid']['type']);
  $content=$spreadsheet->getRecordsContent();
}
$filename = $this->bean->name.'-'.date('Y-m-d H:i:s', strtotime($curdate));
//strip away any blank spaces
$filename = str_replace(' ','_',$filename);
$transContent = $GLOBALS['locale']->translateCharset($content, 'UTF-8', $GLOBALS['locale']->getExportCharset());

//var_dump($transContent);exit;
$transContent = str_replace("\n"," ", $transContent);
if (headers_sent()) {echo 'HTTP header already sent';} 
else {
    if (ob_get_level()) { ob_end_clean();}
    header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
    header("Content-type: application/octet-stream; charset=".$GLOBALS['locale']->getExportCharset());
    header("Content-Transfer-Encoding: Binary");
    header("Content-Length: ".mb_strlen($transContent, '8bit'));
    header("Content-Disposition: attachment; filename={$filename}.csv");
    print $transContent;
    sugar_cleanup(true);
}
