<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
$module_name = 'UT_RSActivities';
$listViewDefs[$module_name] = array(
	'NAME' => array(
		'width' => '20', 
		'label' => 'LBL_NAME', 
		'default' => true,
        'link' => true
    ),
    'RS_SUMMARY'=>array(
        'label' => 'LBL_SUMMARY',
        'width' => '20',
        'default' => true,
        'sortable' => false,
    ),
	'ASSIGNED_USER_NAME' => array(
		'width' => '9', 
		'label' => 'LBL_ASSIGNED_TO_NAME',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true
    ),
);
?>