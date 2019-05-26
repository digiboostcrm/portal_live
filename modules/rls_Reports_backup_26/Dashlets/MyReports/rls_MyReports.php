<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/Dashlets/Dashlet.php');
require_once('modules/rls_Reports/rls_Reports.php');
require_once('modules/rls_Reports/classes/load.php');

/**
 * This class is provide fucntionality for Reports Dashlet
 * 
 *
 */
class rls_MyReports extends Dashlet 
{ 
    /**
     * Path for template which will used for configuring
     * 
     * @var string
     */
    public $configureTpl = 'modules/rls_Reports/Dashlets/MyReports/configure.tpl';
    
    /**
     * Name selected report
     * 
     * @var string
     * */
    public $report_c = null;

    /**
     * ID selected report
     * 
     * @var string
     * */
    public $rls_reports_id_c = null;
    
    /**
     * Adds custom controls to header of Dashlet
     * 
     * @return string
     * */
    public function setConfigureIcon() 
    {
        $html = null;
        
        if ($this->rls_reports_id_c) {
            $html .= '
              <td>
                <a title="'.translate('LBL_MORE_DETAILS_INFO', 'rls_Reports').'" href="index.php?module=rls_Reports&action=DetailView&record='. $this->rls_reports_id_c .'">'
                    .translate('LBL_MORE_DETAILS', 'rls_Reports').'</a>
              </td>
            ';
        }
        
        return $html .= parent::setConfigureIcon();
    }
    
    /**
     * Constructor for Generic Dashlet
     * 
     * @param string $id
     * @param array $options
     */
    public function rls_MyReports($id, array $options = null) 
    {
        parent::Dashlet($id);
        $this->isConfigurable = true;
        
        if(empty($options['title'])) { 
            $this->title = translate('LBL_HOMEPAGE_TITLE_CHART', 'rls_Reports'); 
        } else {
            $this->title = $options['title'];
        }

        if ( isset($options['report_c']) ) {
            $this->report_c = $options['report_c'];
        }
        if ( isset($options['rls_reports_id_c']) ) {
            $this->rls_reports_id_c = $options['rls_reports_id_c'];
        }

        if(isset($options['autoRefresh'])) $this->autoRefresh = $options['autoRefresh'];     
    }
    
    /**
     * Save options.
     * 
     * @param array $req post data
     * @return data for save
     */
    function saveOptions($req) {
        $options = array();
        
        if ( isset($req['title']) ) {
            $options['title'] = $req['title'];
        }
        if ( isset($req['report_c']) ) {
            $options['report_c'] = $req['report_c'];
        }
        if ( isset($req['rls_reports_id_c']) ) {
            $options['rls_reports_id_c'] = $req['rls_reports_id_c'];
        }
        $options['autoRefresh'] = empty($req['autoRefresh']) ? '0' : $req['autoRefresh'];
        
        return $options;
    }
    
    /**
     * Generate HTML for display options.
     *
     * @return string HTML
     */
    function displayOptions() {
        global $app_strings;
        $ss = new Sugar_Smarty();
        $ss->assign('titleLBL', translate('LBL_DASHLET_OPT_TITLE', 'Home'));
        $ss->assign('urlLBL', translate('LBL_DASHLET_OPT_URL', 'Home'));
        $ss->assign('heightLBL', translate('LBL_DASHLET_OPT_HEIGHT', 'Home'));
        $ss->assign('reportLBL', translate('LBL_MODULE_TITLE', 'rls_Reports'));
        $ss->assign('module', $_REQUEST['module']);
        $ss->assign('title', $this->title);
        $ss->assign('id', $this->id);

        $ss->assign('report_c', isset($this->report_c)?$this->report_c:'');
        $ss->assign('rls_reports_id_c', isset($this->rls_reports_id_c)?$this->rls_reports_id_c:'');
        
        $ss->assign('saveLBL', $app_strings['LBL_SAVE_BUTTON_LABEL']);
        $ss->assign('clearLBL', $app_strings['LBL_CLEAR_BUTTON_LABEL']);
        if($this->isAutoRefreshable()) {
            $ss->assign('isRefreshable', true);
            $ss->assign('autoRefresh', $GLOBALS['app_strings']['LBL_DASHLET_CONFIGURE_AUTOREFRESH']);
            $ss->assign('autoRefreshOptions', $this->getAutoRefreshOptions());
            $ss->assign('autoRefreshSelect', $this->autoRefresh);
        }
        
        return  $ss->fetch($this->configureTpl);        
    }    
    
    /**
     * Generate display view.
     *
     * @return string HTML
     */
    function display()
    {
        $html = '';
        $rls_Reports = loadbean('rls_Reports');
        
        if ($rls_Reports->retrieve($this->rls_reports_id_c)) {

            Reports\Chart\Drilldown::resetReport();
            Reports\Settings\Joins::resetReport();
            Reports\Data\Grouping::resetReport();
            Reports\Data\Summarizing::resetReport();
            Reports\Data\Criterion::resetReport();
            Reports\Settings\Storage::resetReport();

            Reports\Settings\Storage::setFocus($rls_Reports);
            Reports\Settings\Storage::load();
            
            $settings = Reports\Settings\Storage::getSettings();
            $storage     = new Reports\Settings\Storage();
            //$filter      = new Reports\Filter\Collection();
            $chart       = Reports\Chart\Factory::loadChart($rls_Reports->chart_type);
            //$spreadsheet = Reports\Grid\Factory::loadGrid($settings['grid']['type']);
            
            $html .= $chart->display();
        } else {
            $html .= translate('LBL_SELECT_REPORT', 'rls_Reports');
        }
        
        $this->seedBean = $rls_Reports;
        return $html . $this->processAutoRefresh();
    }
}

