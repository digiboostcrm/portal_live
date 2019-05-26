<?php 
$admin_option_defs=array();
$admin_option_defs['Administration']['UT_RightSignature_Key']= array('UrdhvaTech','LBL_UT_RIGHTSIGNATURE_KEY_ICON','LBL_UT_RIGHTSIGNATURE_KEY_TITLE','./index.php?module=UT_RightSignature&action=RightSignatureAppKeys');
$admin_option_defs['Administration']['ut_esignature_info']= array('','LBL_ESIGN_RIGHSIGNATURE_LICENSE_TITLE','LBL_ESIGN_RIGHSIGNATURE_LICENSE','./index.php?module=UT_RightSignature&action=license');
$admin_group_header[]= array('LBL_UT_RIGHTSIGNATURE_TITLE','',false,$admin_option_defs, 'LBL_UT_RIGHTSIGNATURE_DESC');
