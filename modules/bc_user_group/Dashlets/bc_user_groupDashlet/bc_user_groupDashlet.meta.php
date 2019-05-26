<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/**
 * The file used to store matadata for dashlet view for User Group module. 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */ 

 
global $app_strings;

$dashletMeta['bc_user_groupDashlet'] = array('module'		=> 'bc_user_group',
										  'title'       => translate('LBL_HOMEPAGE_TITLE', 'bc_user_group'), 
                                          'description' => 'A customizable view into bc_user_group',
                                          'icon'        => 'icon_bc_user_group_32.gif',
                                          'category'    => 'Module Views');