<?php
/**
 * The file used to get REST API configuration
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */

chdir('../../..');

require_once('SugarWebServiceImplv4_1_custom.php');

$webservice_path = 'service/core/SugarRestService.php';
$webservice_class = 'SugarRestService';
$webservice_impl_class = 'SugarWebServiceImplv4_1_custom';

$registry_path = 'custom/service/v4_1_custom/registry.php';
$registry_class = 'registry_v4_1_custom';
$location = 'custom/service/v4_1_custom/rest.php';

require_once('service/core/webservice.php');

?>