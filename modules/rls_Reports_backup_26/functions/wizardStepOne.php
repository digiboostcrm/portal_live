<?php

/**
 *  Get Left and Right columns html of modules list.
 *
 * @return array
 * */
function getStepOneColumnsHtml()
{
    global $moduleList, $app_list_strings, $mod_strings;

    $hide_module_list = array(
        'Home',
        'Calendar',
        'rls_Dashboards',
    );

    $left_column_html = '';
    $right_column_html = '';
    $num = 0;

    $custom_moduleList = $moduleList;
    $custom_moduleList[] = 'Users';
    foreach ($custom_moduleList as $index => $module) {
        if (in_array($module, $hide_module_list)) {
            continue;
        }
        $num++;
        $src = SugarThemeRegistry::current()->getImageURL('icon_' . $module . '_32.gif');
        if(empty($src)){
            $src = SugarThemeRegistry::current()->getImageURL('icon_SugarNews_32.gif');
        }
        $html_module = '
            <tr class="wizard_step1_row_'.$index.'">
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr valign="top" class="wizard_step1_row_'.$index.'">
                <td>
                    <img
                        name="' . $module . '"
                        class="report-type"
                        style="cursor: pointer"
                        src="' . $src . '"
                        onmouseout="document.' . $module . '.src=\'' . $src . '\'"
                        onmouseover="document.' . $module . '.src=\'' . $src . '\'"
                    >
                </td>
                <td>&nbsp;&nbsp;</td>
                <td><b class="wizard_step1_module_name">'. $app_list_strings["moduleList"][$module] .'</b>
                </td>
            </tr>
            ';
        if ($num & 1) {
           $left_column_html .= $html_module;
        } else {
           $right_column_html .= $html_module;
        }
    }

    return array(
        'left_column' => $left_column_html,
        'right_column' => $right_column_html,
    );
}
