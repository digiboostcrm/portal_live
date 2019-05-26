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

//require_once('include/MVC/View/views/view.detail.php');

class rls_ReportsViewDetail extends ViewDetail {


  function rls_ReportsViewDetail(){
    parent::ViewDetail();
  }

  /**
   * Override standard display  
   * 
   */
  function display()
  {
      $license_strings=OutfittersLicense::loadLicenseStrings();
      $validate_license = OutfittersLicense::isValid('rls_Reports');
      if($validate_license !== true) {
	if(is_admin($current_user)) {
           SugarApplication::appendErrorMessage($license_strings['LBL_MESSAGE_ERROR'].': '.$validate_license); 
	  }
	 SugarApplication::appendErrorMessage($license_strings['LBL_MESSAGE_ERROR'].': '.$validate_license);
	return;
      }
      
       //Add To Prospect List Button
       
        $this->dv->defs['templateMeta']['form']['buttons'][101] = array (
             'customCode' => '<input
                             id="addToPL"
                             class="button"  
                             type="submit"  
                             name="button"  
                             value="{$APP.LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL}" ',
              
             );
             
          $this->dv->defs['templateMeta']['form']['buttons'][102] = array (
             'customCode' => '<input
                             id="createApps"
                             class="button"  
                             type="submit"  
                             name="button"                              
                             value="{$APP.LBL_CREATE_APPS_BUTTON_LABEL}" ',              
             );    
     

                         
      $javascript = <<<EOQ
      <script language='javascript'>
              $( "#addToPL" ).click(function() {
                open_popup(
                    'ProspectLists',
                    '600',
                    '400',
                    '',
                    true,
                    false,
                    { 
                        'call_back_function':'setReturnAndSaveTargetList',
                        'form_name':'targetlist_form',
                        'field_to_name_array':{'id':'prospect_list'}
                    }
                );
              
              });
              
              function setReturnAndSaveTargetList(popupData){
                    var id_report="{$this->bean->id}";
                    $.ajax({
                    type :"POST",
                    async: true,
                    url:"index.php?module=rls_Reports&action=addToTargetlist",
                    //data:"prospect_list_id="+popupData.name_to_value_array.prospect_list,
                    data:({id:id_report,prospect_list_id:popupData.name_to_value_array.prospect_list}),
                    success:function(prin){
                        alert(prin);
                    }
                    });
              console.log(popupData);
             }
             
			  var type_report="{$this->bean->type}";            
             
              if (type_report=='Prospects' || type_report=='Contacts' || type_report=='Users' || 
                  type_report=='Leads' || type_report=='Accounts' || type_report=='rls_Candidates') {
                      $('#addToPL').prop('disabled', false);
              }
              else { 
                  $('#addToPL').prop('disabled', true);
              }
              
              
              
              if (type_report != 'rls_Candidates') $('#createApps').remove();                            
              else $("#createApps").click(function() {
                  open_popup ('rls_Jobs', '600', '400', '', true, false, { 
                      'call_back_function': 'setReturnCreateApplications',                     
                      'form_name': 'EditView',                
                      'field_to_name_array': {'id': 'job_id'}
                  });              
              });
                            
              function setReturnCreateApplications (popupData){
                  var report_id = "{$this->bean->id}";                          
                  if (typeof ($.blockUI) == 'function') 
                      $.blockUI ({ message: '<h1><img src="custom/include/img/busy.gif" /> Just a moment...</h1>' });  
                  $.ajax ({
                      type: "POST",
                      async: true,
                      url: "index.php?module=rls_Reports&action=createApplications",                  
                      data: {
                          id: report_id, 
                          job_id: popupData.name_to_value_array.job_id
                      },
                      success: function (prin) {
                           if (typeof ($.unblockUI) == 'function') $.unblockUI();
                          alert (prin);
                      }
                  });                                                        
                  //console.log(popupData);
              }
                                                                     
      </script> 
EOQ;
                  
      parent::display();
      //echo '<script type="text/javascript" src="custom/include/javascript/rls_Candidates/jquery.blockUI.js"></script>';
      echo $javascript; 
      $this->displayCss();
      $this->displayReport();
      
  }
  
  /**
   * PreDisplay hook
   * 
   * */
  public function displayCss()
  {
      echo '<link rel="stylesheet" type="text/css" href="modules/rls_Reports/css/ReportsView.css" />';
  }
  
  /**
   * Dispalying Report view
   * 
   * */
  public function displayReport()
  {
      global $mod_strings;
      Reports\Settings\Storage::setFocus($this->bean);
      Reports\Settings\Storage::load();
      
      $settings = Reports\Settings\Storage::getSettings();
      
      $storage     = new Reports\Settings\Storage();
      $filter      = new Reports\Filter\Collection();
      $chart       = Reports\Chart\Factory::loadChart($this->bean->chart_type);
      if($this->bean->chart_type=='None')$settings['grid']['type']='NoGrouped';
      if (isset($settings['grid']['type'])) {
          $spreadsheet = Reports\Grid\Factory::loadGrid($settings['grid']['type']);
      }

      $display_filters = new Reports\Configurator\DisplayFilters();  
      $wizard_display_filters = new Reports\Settings\WIzard\DisplayFilters();
      
      
      require dirname(__FILE__).'/../tpls/report.php';
  }
}

