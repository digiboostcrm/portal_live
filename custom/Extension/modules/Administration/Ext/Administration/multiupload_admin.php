<?php

global $sugar_version;

$admin_option_defs=array();

if(preg_match( "/^6.*/", $sugar_version) ) {
    $admin_option_defs['Administration']['multiupload_info']= array('helpInline','LBL_MULTIUPLOAD_LICENSE_TITLE','LBL_MULTIUPLOAD_LICENSE','./index.php?module=Multiupload&action=license');
} else {
    $admin_option_defs['Administration']['multiupload_info']= array('helpInline','LBL_MULTIUPLOAD_LICENSE_TITLE','LBL_MULTIUPLOAD_LICENSE','javascript:parent.SUGAR.App.router.navigate("#bwc/index.php?module=Multiupload&action=license", {trigger: true});');
}

$admin_group_header[]= array('LBL_MULTIUPLOAD','',false,$admin_option_defs, '');
