<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

require_once('include/MVC/Controller/SugarController.php');
require_once('modules/rls_Reports/classes/load.php');
require_once('modules/rls_Reports/license/OutfittersLicense.php');

/**
 * This is controller class for RLS Reports module
 * 
 * */
class rls_ReportsController extends SugarController
{
    /**
     * Override std constructor
     * 
     * */
    public function preProcess()
    {
        $license_strings = OutfittersLicense::loadLicenseStrings();
        $validate_license = OutfittersLicense::isValid('rls_Reports');
        if (array_key_exists('action', $_REQUEST) && !in_array($_REQUEST['action'], array("license", "outfitterscontroller")) && $validate_license !== true) {
            if (is_admin($current_user)) {
               // SugarApplication::appendErrorMessage($license_strings['LBL_MESSAGE_ERROR'] . ': ' . $validate_license);
            }
            //SugarApplication::appendErrorMessage($license_strings['LBL_MESSAGE_ERROR'] . ': ' . $validate_license);
            die($license_strings['LBL_MESSAGE_ERROR'] . ': ' . $validate_license);


        }
        // Bind Create Action
        if (isset($_REQUEST['action'])
            && $_REQUEST['action'] == 'EditView'
            && !isset($_REQUEST['record'])
            && !isset($_REQUEST['root'])

        ) {
            SugarApplication::redirect('index.php?module=rls_Reports&action=wizardStepOne');
        }

    }

    /**
     * Action for download PDF.
     *
     * */
    public function action_downloadPDF()
    {
        $report = loadbean('rls_Reports')->retrieve($_REQUEST['record']);
        \Reports\Settings\Storage::setFocus($report);
        $pdf = \Reports\PDF\Factory::load('DefaultType');
        $pdf->generateContent();
        
        exit($pdf->outputPdf());
    }

    /**
     * Action for download CSV.
     *
     * */
    public function action_exportCSV()
    {
        require_once 'include/TimeDate.php';
        $curdate = TimeDate::getNow();
        Reports\Settings\Storage::setFocus($this->bean);
        Reports\Settings\Storage::load();
        $settings = Reports\Settings\Storage::getSettings();

        if (isset($settings['grid']['type'])) {
            $spreadsheet = Reports\Grid\Factory::loadGrid($settings['grid']['type']);
            $content=$spreadsheet->getRecordsContent();
        }
        $filename = $this->bean->name.'-'.date('Y-m-d H:i:s', strtotime($curdate));
        //strip away any blank spaces
        $filename = str_replace(' ','_',$filename);
        $transContent = $GLOBALS['locale']->translateCharset($content, 'UTF-8', $GLOBALS['locale']->getExportCharset());

        $transContent = str_replace("\n"," ", $transContent);
        if (headers_sent()) {echo 'HTTP header already sent';}
        else {
            if (ob_get_level()) { ob_end_clean();}
            header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
            header("Content-type: application/octet-stream; charset=".$GLOBALS['locale']->getExportCharset());
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length: ".mb_strlen($transContent, '8bit'));
            header("Content-Disposition: attachment; filename={$filename}.csv");
            print $transContent;
            sugar_cleanup(true);
        }
    }




    /**
     * Load index dashboard.
     * 
     * */
    public function dashboardIndex()
    {
        $view  = new Dashboard\View();
        $pages = Dashboard\Tabs::getConfiguration();
        
        foreach ($pages as $page_guid => $page) {
            $dashlets = new Dashboard\Dashlets($page_guid); 
            
            $view->setTabsStack(array_merge(
                $view->getTabsStack(),
                array(
                    array(
                        'order' => $page['order'],
                        'title' => $page['pageTitle'],
                        'guid'  => $page_guid,
                    )
                )
            ));

            $view->setPageStack(array_merge(
                $view->getPageStack(),
                array(
                    array(
                        'dashlets' => $dashlets->getHtmlForDashlets(),
                        'guid'  => $page_guid,
                    )
                )
            ));
        }

        $view->getTemplate();
    }
    
    /**
     *  Copy dashboard configuration of current user to selected.
     * 
     */
    public function action_DashboardCopyCofiguration()
    {
        $pages = Dashboard\Tabs::getConfiguration();
        $dashlets = Dashboard\Dashlets::getConfiguration();

        if (isset($_REQUEST['selection_list']) and is_array($_REQUEST['selection_list'])) {
            foreach ($_REQUEST['selection_list'] as $user_id) {
                $user = loadbean('Users');
                $user->retrieve($user_id);

                $user->setPreference(
                    'pages', 
                    $pages, 
                    0, 
                    $_REQUEST['module']
                );
                $user->setPreference(
                    'dashlets', 
                    $dashlets, 
                    0, 
                    $_REQUEST['module']
                );
                
                $UserPreference = new UserPreference($user);
                $UserPreference->savePreferencesToDB();
            }
        } else {
            exit('Please select the record for copy!');
        }
        exit(translate('LBL_CONFIGURATION_IS_COPIED', 'rls_Reports'));
    }

    /**
     * Adding and Empty tab to the Dashboard
     * 
     */
    public function action_DashboardAddEmptyTab()
    {
        $tab_guid = Dashboard\Tabs::addEmptyTab();
        $dashlets = new Dashboard\Dashlets($tab_guid);
        $json     = getJSONobj();
        
        $dashlets->addEmptyDashlet();
        
        exit(
            $json->encode(array(
                'guid' => $tab_guid,
            ))
        ); 
    }
    
    /**
     * Removing Tab from the Dashboard
     * 
     */
    public function action_DashboardRemoveTab()
    {
        $dashlets = new Dashboard\Dashlets($_REQUEST['tab_guid']);
        $dashlets->removeAll();
        
        Dashboard\Tabs::removeTab($_REQUEST['tab_guid']);
        
        exit();
    }
    
    /**
     * Sets Caption of Tab
     * 
     */
    public function action_DashboardSetTabCaption()
    {
        Dashboard\Tabs::setCaption($_REQUEST['tab_guid'], $_REQUEST['value'] ? $_REQUEST['value'] : 'no_caption');
        exit();
    }
    
    /**
     * Returns HTML for Tab 
     * 
     */
    public function action_DashboardGetTabContent()
    {
        $dashlets          = new Dashboard\Dashlets($_REQUEST['tab_guid']);
        $view              = new Dashboard\View();
        $html_for_dashlets = $view->displayControls($_REQUEST['tab_guid']) . $dashlets->getHtmlForDashlets();
        
        exit($html_for_dashlets);
    }
    
    /**
     * Adds Empty Dashlet to the Tab 
     * 
     */
    public function action_DashboardAddEmptyDashlet()
    {
        $json     = getJSONobj();
        $dashlets = new Dashboard\Dashlets($_REQUEST['tab_guid']);
        $guid     = $dashlets->addEmptyDashlet();
        $content  = $dashlets->getCodeForOne($guid);
        
        exit(
            $json->encode(array(
                'guid' => $guid,
                'html' => $content,
            ))
        );
    }
    
    /**
     * Adds Empty Dashlet to the Tab 
     * 
     */
    public function action_DashboardSaveTabPosition()
    {
        $order_set = array();
        foreach ($_REQUEST['order'] as $tab_position) {
            $order_set[$tab_position['tab_guid']] = $tab_position['index'];
        }
        
        $result = Dashboard\Tabs::setOrder($order_set);
        
        exit($result ? 'ok' : 'false');
    }
    
    /**
     * Adds Empty Dashlet to the Tab 
     * 
     */
    public function action_DashboardSaveDashletPosition()
    {
        $dashlets = new Dashboard\Dashlets($_REQUEST['tab_guid']);
        $result = $dashlets->savePosition(array(
            'guid'   => $_REQUEST['dashlet_guid'],
            'column' => $_REQUEST['column'],
            'index'  => $_REQUEST['index'],
        ));  
        
        exit($result ? 'ok' : 'false');
    }
    
    /**
     * Adds Empty Dashlet to the Tab 
     * 
     */
    public function action_DashboardSaveLayout()
    {
        Dashboard\Layout::getInstance($_REQUEST['tab_guid'])
          ->setColumnsCount($_REQUEST['layout_type'])
          ->saveLayout();
        
        exit;
    }
    
    /**
     * Indicate adding of the new tab
     * 
     */
    public function action_DashboardNewTabIndicator() 
    {
        exit('
            Adding new Tab ... 
        ');
    }
    
    
    
    /**
     * Get Accessible Modules List 
     * Ajax function
     * TODO add recording errors in the log
     * */
    public function action_getAccessibleModulesList()
    {
        Reports\Settings\Storage::setFocus($this->bean);
        Reports\Settings\Storage::load($_REQUEST['type']);
        
        $json     = getJSONobj();
        $settings = Reports\Settings\Storage::getSettings();   
        if (!isset($_REQUEST['moduleName'])) {
            exit(
                $json->encode(array(
                    array(
                        'data' => array(
                            'title' => $settings['label'],
                            'attr' => array(
                                'id' => 'CHILD_PARAMETERS-'.$settings['data']['module_of_report'],
                                'class' => 'wizard-show-module-fields'
                            ),
                        ),
                        'state'=> 'closed',
                    )
                ))
            );

        } else {
            $mdoules =  new Reports\Configurator\ModulesControl;
            //$reportModuleList = $mdoules->getModulesCode($_REQUEST['moduleName']);
            $reportModuleList = $mdoules->setRootModule($_REQUEST['type'])->setRelationName($_REQUEST['relationName'])->getModulesCode($_REQUEST['type']);
            
            exit(
                $json->encode($reportModuleList)
            );
        }
    }
    
    
    /**
     * Get Accessible Fields list 
     * Ajax function
     * TODO add recording errors in the log
     * */
    public function action_getAccessibleFieldsList()
    {
        $fields_code = '';
        if(empty($_REQUEST['moduleName']) || !loadBean($_REQUEST['moduleName'])){
            exit($fields_code);
        }

        \Reports\Settings\Storage::load($_REQUEST['type']);
        $this->updateDrilldown();
        
        $fields =  new Reports\Configurator\FieldsControl;
        $fields_code = $fields->setStepName($_REQUEST['stepName'])
                              ->setModule($_REQUEST['moduleName'])
                              ->setReletion(isset($_REQUEST['reletionName'])?$_REQUEST['reletionName']:null)
                              ->getFieldsCode($_REQUEST['td_class_name']);
        exit($fields_code);
    }
    
    /**
     * Get Field row
     * Ajax function
     * TODO add recording errors in the log
     * */
    public function action_getFieldHtml()
    {
        $fieldHtmlcode = '';
        if(empty($_REQUEST['moduleName'])
            || !loadBean($_REQUEST['moduleName'])
            || empty($_REQUEST['fieldName'])
            || empty($_REQUEST['type'])
        ) {
            exit($fieldHtmlcode);
        }
        
        $this->updateDrilldown();

        $configurator = Reports\Configurator\Factory::load($_REQUEST['type']);
        $fieldHtmlcode = $configurator
                            ->setModule($_REQUEST['moduleName'])
                            ->setReletion($_REQUEST['reletionName'])
                            ->setFieldName($_REQUEST['fieldName'])
                            ->getFieldHTML($_REQUEST['fieldName']);
        exit($fieldHtmlcode);
    }

    /**
     * Action for get html list of configured filters, groupings etc.
     * after change drilldown settings.
     *
     * */
    public function action_getSelectedDataHtml()
    {
        $report = loadbean('rls_Reports');
        $report->retrieve($_REQUEST['record']);
        
        \Reports\Settings\Storage::setFocus($report);
        \Reports\Settings\Storage::load($_REQUEST['type']);

        $this->updateDrilldown();

        $class_name = 'Reports\Configurator\\' . $_REQUEST['class_step'];
        $display_filters = new $class_name();  
        exit($display_filters->display());
    }
    /**
     * Action for add records to Prospect List.
     *
     * */
    public function action_addToTargetlist()
    {
        $prospect_list_id=$_POST['prospect_list_id'];       
        $report = loadbean('rls_Reports');
        $report->retrieve($_POST['id']);        
        \Reports\Settings\Storage::setFocus($report);
        Reports\Settings\Storage::load();
        $settings = Reports\Settings\Storage::getSettings();
        if (isset($settings['grid']['type'])) {
          $spreadsheet = Reports\Grid\Factory::loadGrid('Grouped');
          $records_id=$spreadsheet->getRecordsId();
        }
        
        if (!in_array ($report->type , array ('Prospects', 'Contacts', 'Users', 'Leads', 'Accounts', 'rls_Candidates')))
			exit ('Adding records to target list is not allowed for this module');
             
        foreach ($records_id as $record_id) {
            $related_ids .= "'".$record_id."',";
            $prospect_list_ids .= "'".$prospect_list_id."',";         
        }
        $related_ids = substr($related_ids,0,-1);
        $prospect_list_ids = substr($prospect_list_ids,0,-1);
        
        //Calc exists records for msg
        $sql = "SELECT count(*) as c FROM prospect_lists_prospects WHERE related_id in(".$related_ids.") 
			AND prospect_list_id in(".$prospect_list_ids.") AND related_type = '".$report->type."' AND deleted = '0'";
        $result = $GLOBALS['db']->query ($sql); 
        $row = $GLOBALS['db']->fetchByAssoc ($result);
        $skipped = $row['c'];
                
        //Clear all records
        $sqldel = "DELETE FROM prospect_lists_prospects WHERE related_id in(".$related_ids.") 
			AND prospect_list_id in(".$prospect_list_ids.") AND related_type = '".$report->type."' AND deleted = '0'";
        $resultdel = $GLOBALS['db']->query($sqldel); 
        $row = $GLOBALS['db']->fetchByAssoc($resultdel);
        $GLOBALS['log']->fatal (print_r ($row, true));
        
        //Add new records
        $sql_insert = "INSERT INTO prospect_lists_prospects (id,prospect_list_id,related_id,related_type,date_modified,deleted) VALUES";        
        foreach ($records_id as $record_id) {
            $sql_insert.="(UUID(),'".$prospect_list_id."','".$record_id."','".$report->type."',CURDATE(),'0'),";             
        }
        $sql_insert = substr($sql_insert,0,-1);
        $result_insert = $GLOBALS['db']->query($sql_insert);
        $msg = (count($records_id)-$skipped).' records added to the Target List, '.$skipped.' skipped';
                        
        exit ($msg); 
    }

    /**
     * Check drilldown setting from ajax request.
     *
     * */
    public function updateDrilldown()
    {
        $drilldown = \Reports\Chart\Drilldown::getInstance();
        $drilldown->setDrilldown((isset($_REQUEST['drill_down']) and $_REQUEST['drill_down'] ? true : false));
    }
    
    
    /**
     * Action for generation Applications for Job.
     *
     * */
    public function action_createApplications()
    {
		global $current_user;
        $job_id = $_POST['job_id'];       
        $report_id = $_POST['id'];
        if (empty ($job_id) || empty ($report_id)) exit ('Incorrect Report');
        
        //require_once('modules/rls_Jobs/rls_Jobs.php');        
        //$job = BeanFactory::retrieveBean ('rls_Jobs', $job_id);
        $job = new rls_Jobs();      
        $job->retrieve ($job_id);
        if (empty ($job)) exit ('Incorrect Job');
        $job->load_relationship ('rls_jobs_rls_applications');
        
        //$report = BeanFactory::retrieveBean ('rls_Reports', $report_id); 
        $report = new rls_Reports();      
        $report->retrieve ($report_id);                     
        if ($report->type != 'rls_Candidates') exit ('Function is not allowed for this module');
			     
        \Reports\Settings\Storage::setFocus($report);
        Reports\Settings\Storage::load();
        $settings = Reports\Settings\Storage::getSettings();
        if (isset($settings['grid']['type'])) {
          $spreadsheet = Reports\Grid\Factory::loadGrid('Grouped');
          $candidates_ids = $spreadsheet->getRecordsId();
        }
       	
       	require_once('modules/rls_Applications/rls_Applications.php');
       	require_once('modules/rls_Candidates/rls_Candidates.php');
       	//$application = BeanFactory::newBean ('rls_Applications');
       	$application = new rls_Applications(); 
       	$candidates_skipped = 0;

       	       	
        foreach ($candidates_ids as $candidate_id) {
			//$cand = BeanFactory::retrieveBean ('rls_Candidates', $candidate_id);
			$cand = new rls_Candidates();      
            $cand->retrieve ($candidate_id);
			if (empty ($cand)) exit ('Incorrect Candidate');
			$cand->load_relationship ('rls_candidas_applications');
			
			$where = 'rls_applications.rls_jobs_id_c="'.$job_id.'" AND rls_applications.rls_candidates_id_c="'.$candidate_id.'" ';                
            $appl_list = $application->get_full_list ("rls_applications.name", $where, true);
            if (!empty ($appl_list)) { $candidates_skipped++; continue; } // Application exists
                                   
            $application->id = 0;                        
            $application->name = $cand->name;
            $application->status = 1;
            $application->rls_jobs_id_c = $job_id;
            $application->rls_candidates_id_c = $candidate_id;
            $application->assigned_user_id = $current_user->id;
            $application->unique_idj = $job->unique_id;
            $application->unique_idc = $cand->unique_id;
            $application->save();        

            $job->rls_jobs_rls_applications->add ($application->id);            
            $cand->rls_candidas_applications->add ($application->id);              
        }

        $msg = (count($candidates_ids)-$candidates_skipped). ' Application created, '.  $candidates_skipped. ' skipped';
        exit ($msg);
    }
    /**
     * Action save report setting for role user
     *
     * */
    public function action_reportSettingsSave()
    {
        global $current_user;
        $_SESSION['REQUEST'] = $_REQUEST;
        $reports = new rls_Reports();

        $queryParams = array(
          'module' => 'rls_Reports',
          'action' => 'Save',
          'record' => $_REQUEST['record'],
        );
        if(!array_key_exists('wizard',$_REQUEST)){
            $queryParams['action']='DetailView';
            SugarApplication::redirect('index.php?' . http_build_query($queryParams));
        }
        if(isset($_SESSION['ACL'][$current_user->id]['rls_Reports']['module'])) {

            if($_SESSION['ACL'][$current_user->id]['rls_Reports']['module']['edit']['aclaccess'] == '-99') {
                $_SESSION['ACL'][$current_user->id]['rls_Reports']['module']['edit']['aclaccess'] = '90';
                $_SESSION['open_access_role'] = true;
                SugarApplication::redirect('index.php?' . http_build_query($queryParams));
            } else {
                SugarApplication::redirect('index.php?' . http_build_query($queryParams));
            }
        } else {
            SugarApplication::redirect('index.php?' . http_build_query($queryParams));
        }
    }


    /**
     * Action set OrderBy For report setting for role user
     *
     * */
    public function action_setOrderByForReport()
    {
        global $current_user;
        $reports = new rls_Reports();
        $reports->retrieve($_REQUEST['record']);

        $reports_settings = unserialize(
            base64_decode(
                html_entity_decode(($reports->reports_settings))
            )
        );
        if(empty($reports_settings)){
            $reports_settings = unserialize(
                html_entity_decode(($reports->reports_settings))
            );
        }

        foreach($reports_settings['DisplayFields']['columns'] as $index=>$field_settings){
            if($field_settings['dataField']==$_REQUEST['order_by']){
                if(array_key_exists('radio_btn',$field_settings)){
                    if($field_settings['orderBy'] == 'a'){
                        $reports_settings['DisplayFields']['columns'][$index]['orderBy']='d';
                    } else {
                        $reports_settings['DisplayFields']['columns'][$index]['orderBy']='a';
                    }
                } else {
                    $reports_settings['DisplayFields']['columns'][$index]['orderBy']='a';
                    $reports_settings['DisplayFields']['columns'][$index]['radio_btn']='on';
                }
            } else {
                unset($reports_settings['DisplayFields']['columns'][$index]['radio_btn']);
            }
        }

        $reports->reports_settings = base64_encode(serialize($reports_settings));

        if(isset($_SESSION['ACL'][$current_user->id]['rls_Reports']['module'])) {

            if($_SESSION['ACL'][$current_user->id]['rls_Reports']['module']['edit']['aclaccess'] == '-99') {
                $_SESSION['ACL'][$current_user->id]['rls_Reports']['module']['edit']['aclaccess'] = '90';
                $_SESSION['open_access_role'] = true;
                $reports->parentSave();
            } else {
                $reports->parentSave();
            }
        } else {
            $reports->parentSave();
        }


        $queryParams = array(
            'module' => 'rls_Reports',
            'action' => 'DetailView',
            'record' => $_REQUEST['record'],
        );
        SugarApplication::redirect('index.php?' . http_build_query($queryParams));





    }
}
