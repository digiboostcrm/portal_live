<?php

/**
 * Outputs HTML for Wizard
 * 
 * */
function getWizardTemplate($focus, $field = 'wizard', $value, $view = 'DetailView')
{
    global $app_list_strings, $mod_strings;
    if ($view == 'EditView'){        
        ob_start();
        if (($focus->id 
            && $type = $focus->type)
            || ($type = $_REQUEST['root'])
        ) {
            Reports\Settings\Storage::setFocus($focus);
            Reports\Settings\Storage::load($type);
        } else {
            SugarApplication::redirect('index.php?module=rls_Reports');
            return false;
        }
        
        $filter   = new Reports\Filter\Collection();  
        $settings = Reports\Settings\Storage::getSettings();
        
        $display_columns = new Reports\Configurator\DisplayColumns();  
        $wizard_displayfields = new Reports\Settings\WIzard\DisplayFields();
        
        $display_filters = new Reports\Configurator\DisplayFilters();  
        $display_groupby = new Reports\Configurator\DisplayGroupBy();
        $display_summaries = new Reports\Configurator\DisplaySummaries(); 
        
        include dirname(__FILE__).'/../tpls/wizard.php';
        $output = ob_get_clean();
        
        return $output;
    }
    
    return false;
}

