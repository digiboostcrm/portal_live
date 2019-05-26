<?php

/*
 * Created by Richlode Solutions
 * Author: Andrii Vasylchenko
 */

$admin_option_defs = array();
$admin_option_defs['Administration']['rls_Reports_configurator'] = array('Administration', 'LBL_MANAGE_REPORT_CONFIG', 'LBL_REPORT_CONFIG', './index.php?module=Configurator&action=rls_Reports_configurator');

$admin_group_header[] = array('LBL_REPORT_CONFIG_TITLE', '', false, $admin_option_defs, 'LBL_REPORT_CONFIG_DESC');
