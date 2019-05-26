<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/**
 * Implement get_body function to correctly populate the template for the ModuleBuilder/Studio
 * Add field page.
 * 
 * @param Sugar_Smarty 	$ss
 * @param array 		$vardef
 *
 */
function get_body(&$ss, $vardef)
{
    global $app_list_strings, $dictionary;
	$vars 	= $ss->get_template_vars();
	$fields = $vars['module']->mbvardefs->vardefs['fields'];
	$fieldOptions = array();
	foreach($fields as $id => $def) {
		$fieldOptions[$id] = $def['name'];
	}
	
	$ss->assign('fieldOpts', $fieldOptions);
    $ss->assign('MODULE', $vars['module']);
    
    $ss->assign('hideReportable', true);
    $ss->assign('hideImportable', true);
    $ss->assign('hideDuplicatable', true);
	
    return $ss->fetch('custom/modules/DynamicFields/templates/Fields/Forms/multiupload.tpl');
 }
?>