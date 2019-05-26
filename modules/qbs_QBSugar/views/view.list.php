<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/MVC/View/views/view.list.php');

class qbs_QBSugarViewList extends ViewList
{
	public function display()	{
		global $db;
		/*
		// Get all Invoice and update account id
		$invoiceBean = BeanFactory::getBean('AOS_Invoices');
		$invoicesList = $invoiceBean->get_full_list();
		foreach($invoicesList as $invoice)	{
			if($invoice->billing_contact_id) {
				$contactBean = BeanFactory::getBean('Contacts', $invoice->billing_contact_id);
                                $account_id = $contactBean->account_id;
				$invoice->billing_account_id = $account_id;
				if(empty($invoice->type_c))	{
					$invoice->type_c = 'invoice';
				}
				$invoice->save();
			}
		
		}*/

		$moduleName = (! empty ( $_REQUEST [ 'module' ] )) ? $_REQUEST [ 'module' ] : null ;
		$packageName = (! empty ( $_REQUEST [ 'view_package' ] )) ? $_REQUEST [ 'view_package' ] : null ;
		$subpanelName = (! empty ( $_REQUEST [ 'subpanel' ] )) ? $_REQUEST [ 'subpanel' ] : null ;

		require_once('modules/ModuleBuilder/parsers/ParserFactory.php');
		$parser = ParserFactory::getParser($this->editLayout, $this->editModule, $packageName, $subpanelName);
		$smarty = $this->constructSmarty($parser);

		// get sync informations
		$getSyncInfo = $db->query('select module, addedtoqueue from sugar_qbsyncdetails');
		while($row = $db->fetchByAssoc($getSyncInfo))	{
			// assign display values for each service
			// below code will determine whether to show or hide a service queue button
			$dismodule = $row['module'];
                        if($row['addedtoqueue'] == 0)	{
                                $smarty->assign($dismodule, "block");
                                $smarty->assign($dismodule."_delete", "none");
                        }
                        else	{
                                $smarty->assign($dismodule, "none");
                                $smarty->assign($dismodule."_delete", "block");
                        }
        	}

		// show or hide views
                $service_set = $_REQUEST['service_set'];
                if($_REQUEST['service_set'] == 'API')	{
                        $smarty->assign("manual_show", "none");
                        $smarty->assign("api_show", "block");
                        $smarty->assign("email_not", "none");
                        $smarty->assign("api_selected", "QBSelected");
                }
                else if($_REQUEST['service_set'] == 'EMAIL')	{
                        $smarty->assign("manual_show", "none");
                        $smarty->assign("api_show", "none");
                        $smarty->assign("email_not", "block");
                        $smarty->assign("email_selected", "QBSelected");
                }
                else	{
                        $smarty->assign("manual_show", "block");
                        $smarty->assign("api_show", "none");
                        $smarty->assign("email_not", "none");
                        $smarty->assign("manual_selected", "QBSelected");
                }
                $module = $this->module;
                require_once("modules/{$module}/QuickBooks.php");
                require_once("modules/{$module}/config.php");
                $QuickBooksConfigObj = new Quickbooks_GokuConfig();
                $status = $QuickBooksConfigObj->quickbooks_is_connected;
                $quickbooks_menu_url = $QuickBooksConfigObj->quickbooks_menu_url;
                $quickbooks_oauth_url = $QuickBooksConfigObj->url;
		if($status){
			$getCountry = $db->getOne('select country from sugar_qbtigersettings');
			if(empty($getCountry)){
				$quickbooks_CountryInfo = $QuickBooksConfigObj->quickbooks_CountryInfo->getCountry();
				$db->query("update sugar_qbtigersettings set country = '$quickbooks_CountryInfo'");	
			}
		}
		$view = $_REQUEST['view'];
		$smarty->assign('VIEW', $view);
		$smarty->assign('currentModule', $moduleName);
	        $smarty->assign('status', $status);
	        $smarty->assign('quickbooks_menu_url', $quickbooks_menu_url);
	        $smarty->assign('quickbooks_oauth_url', $quickbooks_oauth_url);
		if($status)
		        $smarty->assign('quickbooks_company_name', $QuickBooksConfigObj->quickbooks_CountryInfo->getCompanyName());

		echo $smarty->fetch("modules/{$moduleName}/tpls/Main.tpl");
	}

	public function constructSmarty($parser)	{
		$smarty = new Sugar_Smarty();
		return $smarty ;
	}
}
