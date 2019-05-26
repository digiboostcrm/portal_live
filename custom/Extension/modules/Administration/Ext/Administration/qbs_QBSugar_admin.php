<?php

global $sugar_version;

$admin_option_defs=array();

if(preg_match( "/^6.*/", $sugar_version) ) {
    $admin_option_defs['Administration']['qbs_QBSugar_info']= array('helpInline','LBL_qbs_QBSugar_LICENSE_TITLE','LBL_qbs_QBSugar_LICENSE','./index.php?module=qbs_QBSugar&action=license');
} else {
    $admin_option_defs['Administration']['qbs_QBSugar_info']= array('helpInline','LBL_qbs_QBSugar_LICENSE_TITLE','LBL_qbs_QBSugar_LICENSE','javascript:parent.SUGAR.App.router.navigate("#bwc/index.php?module=qbs_QBSugar&action=license", {trigger: true});');
}

$admin_group_header[]= array('LBL_qbs_QBSugar','',false,$admin_option_defs, '');
