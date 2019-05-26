<?php

/*
 * Created by Richlode Solutions
 * Author: Andrii Vasylchenko
 */

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

if (!is_admin($current_user)) {
    sugar_die('Admin Only');
}

require_once('modules/Configurator/Forms.php');
require_once('modules/Configurator/Configurator.php');

$configMeta = array(
    'nodejshost' => array(
        'default' => 'http://192.168.1.239:3000/chart',
        'type' => 'varchar',
        'section' => 'Default'
    ),
    'switch_mode' => array(
        'default' => '0',
        'type' => 'checkbox',
        'section' => 'Default'
    ),
);

global $sugar_config, $mod_strings;
foreach ($configMeta as $key => $value) {
    if (!isset($sugar_config[$key])) {
        $sugar_config[$key] = '';
        $GLOBALS['sugar_config'][$key] = '';
    }
}
$configurator = new Configurator();
$configurator->loadConfig();
$focus = new Administration();
$isSave = filter_input(INPUT_POST, 'save');
if ($isSave) {
    $postData = $_POST;
    foreach ($configMeta as $field => $data) {
        $value = $postData[$field];
        $configurator->config[$field] = $value? : $data['default'];
    }
    $configurator->saveConfig();
    $focus->saveConfig();
    header('Location: index.php?module=Administration&action=index');
}
$focus->retrieveSettings();
$isRestore = filter_input(INPUT_POST, 'restore');
if ($isRestore) {
    $configurator->restoreConfig();
}
$reports_config = array();
foreach ($configMeta as $key => $value) {
    $reports_config[$key] = $value['default'];
}
$config = array();
foreach ($configMeta as $field => $data) {
    $value = $configurator->config[$field];
    $config[$field] = $value? : $data['default'];
}
require_once('include/Sugar_Smarty.php');
$smarty = new Sugar_Smarty();
$smarty->assign('config', $config);
$smarty->assign('MOD', $mod_strings);
$smarty->assign('APP', $app_strings);
$smarty->assign('APP_LIST', $app_list_strings);
$smarty->assign('reports_config', $reports_config);
$smarty->assign('error', $configurator->errors);
$smarty->display('custom/modules/Configurator/rls_Reports_configurator.tpl');
