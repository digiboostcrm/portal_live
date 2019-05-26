<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class bc_NotificationViewSetNotification extends SugarView
{

    function display()
    {
        global $moduleList, $db, $app_list_strings;
        $excluded_modules = array('Home', 'Calendar', 'Bugs', 'bc_Notification');
        $saved_module_array = $saved_module_row = array();

        //Get saved module preference
        $select_query = "SELECT module,id FROM notification_modules";
        $saved_module_result = $db->query($select_query);
        while ($saved_module_row = $db->fetchByAssoc($saved_module_result)) {
            $saved_module_array[$saved_module_row['id']] = $saved_module_row['module'];
        }

        //Load all sugar module list
        foreach ($moduleList as $modulename) {
            $modules = $app_list_strings['moduleList'][$modulename];
        }
        $html = '';
        if (!empty($_REQUEST['msg']) && $_REQUEST['msg'] == 'success') {
            $html = "<div style='margin:10px;text-align:center'><span style='color:green;font-weight:bold;'> Changes saved successfully.</span></div>";
        }
        $html .= '<tr>
            <td colspan="3">
                <input type="button" name="selectAllModules" id="selectAllModules" value="Select All" onclick="checkAllmodules();">
                <input type="button" name="deselectAllModules" id="deselectAllModules" value="Deselect All" onclick="uncheckAllModules();">
            </td>
        </tr>';
        $html .= "<form name='notification_module' method='post' action='index.php?module=bc_Notification&action=saveNotificatonModule'>
                 <table width='100%' cellspacing='0' cellpadding='0' id='ModuleTable'><tr>";
        $currentColumn = 0;
        $maxColumn = 4;
        sort($moduleList);
        foreach ($moduleList as $module) {
            if (!in_array($module, $excluded_modules)) {
                if (in_array($module, $saved_module_array)) {
                    $checked = "checked=checked";
                } else {
                    $checked = "";
                }
                if ($currentColumn < $maxColumn) {
                    $html .= "<td style='height:20px;
                border:#ccc 1px solid;padding:3px 5px'><input type='checkbox' name='chkmodule[]' style='margin:0px 10px' value='{$module}' {$checked}>{$app_list_strings['moduleList'][$module]}</td>";
                    $currentColumn++;
                } else {
                    $html .= "</tr><tr><td style='height:20px;border:#ccc 1px solid;padding:3px 5px;'><input type='checkbox' name='chkmodule[]' style='margin:0px 10px' value='{$module}' {$checked}>{$app_list_strings['moduleList'][$module]}</td>";
                    $currentColumn = 1;
                }
            }
        }
        if ($currentColumn < $maxColumn) {
            $diff = $maxColumn - $currentColumn;
        }
        for ($i = 0; $i < $diff; $i++) {
            $html .= "<td style='height:20px;border:#ccc 1px solid;padding:3px 5px;'> &nbsp;</td>";
        }
        $html .= '</tr></table>';
        $html .= '<div style="padding-top:10px;">
                        <input type="submit" name="save" value="Save" class="button" >
                        <input type="button" name="cancel" value="Cancel" class="button" onclick="redirectToindex();">
                 </div></form>';

        parent::display();
        echo $html;

        echo '
            <script type="text/javascript">
            function redirectToindex(){
                location.href = "index.php?module=Administration&action=index";
            }
            function checkAllmodules(){
                  var inputs =   document.getElementById("ModuleTable").getElementsByTagName("input");
                    for (var i = 0; i < inputs.length; i++) {
                        if(inputs[i].name.indexOf("chkmodule[]") == 0) {
                                inputs[i].checked =  true;
                        }
                    }
            }
            function uncheckAllModules(){
                  var inputs =   document.getElementById("ModuleTable").getElementsByTagName("input");
                    for (var i = 0; i < inputs.length; i++) {
                        if(inputs[i].name.indexOf("chkmodule[]") == 0) {
                                inputs[i].checked =  false;
                        }
                    }
            }       
                      
         </script>';
    }

}

?>

