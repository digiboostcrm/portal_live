<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * ****************************************************************************** */

//Bug 30094, If zlib is enabled, it can break the calls to header() due to output buffering. This will only work php5.2+
require_once 'include/export_utils.php';
ob_start();

$recordID = explode(',', $_REQUEST['uid']);
$finalExportData = array();
$count = '0';
foreach ($recordID as $key => $record) {
    $contacts = new Contact();
    $contacts->retrieve($record);

    $finalExportData[$record]['Username'] = $contacts->username_c;
    $finalExportData[$record]['Password'] = $contacts->password_c;
    $finalExportData[$record]['User email id'] = $contacts->email1;
    $finalExportData[$record]['First name'] = $contacts->first_name;
    $finalExportData[$record]['Last name'] = $contacts->last_name;
    
   
}
foreach ($finalExportData as $row) {
 $exportArray = array_keys($row); 
}

$filename = $_REQUEST['module'];

$content .= implode(getDelimiter(), array_values($exportArray)) . "\r\n";
foreach ($finalExportData as $row) {
    $line = implode(getDelimiter(), $row) . "\r\n";
    $content .= $line;
    
}
$transContent = $GLOBALS['locale']->translateCharset($content, 'UTF-8', $GLOBALS['locale']->getExportCharset());
ob_clean();
header("Pragma: cache");
header("Content-type: application/octet-stream; charset=" . $GLOBALS['locale']->getExportCharset());
header("Content-Disposition: attachment; filename={$filename}.csv");
header("Content-transfer-encoding: binary");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . TimeDate::httpTime());
header("Cache-Control: post-check=0, pre-check=0", false);
header("Content-Length: " . mb_strlen($transContent, '8bit'));
if (!empty($sugar_config['export_excel_compatible'])) {
    $transContent = chr(255) . chr(254) . mb_convert_encoding($transContent, 'UTF-16LE', 'UTF-8');
}
print $transContent;

sugar_cleanup(true);

