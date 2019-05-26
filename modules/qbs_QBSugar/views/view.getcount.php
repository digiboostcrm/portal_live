<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/MVC/View/views/view.list.php');
class qbs_QBSugarViewGetCount extends ViewList
{
	/**
         * update last modified date
         * @param string $service
         */
        public function updateLastModifiedTime($service)        {
                global $db;
                $datetime = date('Y-m-d h:i:s');
                $response = $db->pquery("update sugar_qbsyncdetails set lastsync = '?' where module = '?'", array($datetime, $service));
        }

	public function display()	{
		global $db;
		$moduleName = $this->module;
		require_once("modules/{$moduleName}/config.php");
                require_once("modules/{$moduleName}/helper.php");
                require_once("modules/{$moduleName}/QuickBooks.php");
                require_once("modules/{$moduleName}/QuickBooksHelper.php");
                require_once("modules/{$moduleName}/SmackHelper.php");
                require_once("modules/{$moduleName}/Functions.php");
		// request values
		$mode = $_REQUEST['mode'];
		$service = $_REQUEST['service'];

		$addQBQuery = ''; $count = 0;
                $helper   = new qbtigerHelper();
                $QuickBooksHelper = new Quickbooks_GokuHelper();
                $QuickBooksConfigObj = new Quickbooks_GokuConfig();

		$lastUpdatedTime = $helper->getLastSyncDetails($service);
		// get modified time for service
                $query_modifiedtime = " and vtiger_crmentity.modifiedtime >= '{$lastUpdatedTime}'";	

		if(empty($service))
                        $helper->handleError('No service passed to get record count', 'qbtiger');

                if(($service == 'Invoice' || $service == 'Item' || $service == 'Customer' || $service == 'Vendor'))
                {
                        $response = $QuickBooksHelper->getQBValuesCount($QuickBooksConfigObj, $service, 'create');
                        echo $response;die;
                }

		// check whether interruption occured when last sync happend
                $cachedLastTime = $helper->getLastAddedIds($service);
                if($cachedLastTime)     {
                        $lastUpdatedTime = $cachedLastTime;
                }

                if($service == 'SalesOrder')	{
                        $response = $QuickBooksHelper->getQBValuesCount($QuickBooksConfigObj, 'SalesReceipt', 'create');
                        echo $response;die;
                }
                else if($service == 'Quotes')	{
                        $response = $QuickBooksHelper->getQBValuesCount($QuickBooksConfigObj, 'Estimate', 'create');
                        echo $response;die;
                }
		else if($service == 'QBCustomer')	{
			if($lastUpdatedTime)
	                        $query_modifiedtime = " and c.date_modified >= '{$lastUpdatedTime}'";

                        $service_mode = getModRecSelection('Contacts');

                        if($mode == 'edit')	{
                                if($lastUpdatedTime)    {
					$query = "SELECT count(*) as count FROM contacts c where deleted = 0 $query_modifiedtime and c.id IN (select crmid from sugar_qbids where module = 'Contacts') $addQBQuery";
                                }
                                else    {
                                        $this->updateLastModifiedTime($service);
                                        die($count);
                                }
                        }
                        else if($mode == 'create')	{
                                // query to get contacts which are not added to quickbooks
				$query = "SELECT count(*) as count FROM contacts c left join sugar_qbids ei on ei.crmid = c.id and ei.module = 'Contacts' where ei.crmid is NULL and deleted = 0 $addQBQuery";
                        }
                }
		else if($service == 'QBQuotes')	{
                        $statusQuery = $helper->getStatusQuery($service);
			if($lastUpdatedTime)
                                $query_modifiedtime = " and q.date_modified >= '{$lastUpdatedTime}'";

                        $service_mode = getModRecSelection('Quotes');

                        if($mode == 'edit')     {
                                if($lastUpdatedTime)    {
                                        $query = "SELECT count(*) as count FROM aos_quotes q where deleted = 0 $query_modifiedtime and q.id IN (select crmid from sugar_qbids where module = 'Estimate') $addQBQuery $statusQuery";
                                }
                                else    {
                                        $this->updateLastModifiedTime($service);
                                        die($count);
                                }
                        }
                        else if($mode == 'create')      {
                                // query to get contacts which are not added to quickbooks
                                $query = "SELECT count(*) as count FROM aos_quotes q left join sugar_qbids ei on ei.crmid = q.id and ei.module = 'Estimate' where ei.crmid is NULL and deleted = 0 $addQBQuery $statusQuery";
                        }
                }
		else if($service == 'QBInvoice')
                {
			$statusQuery = $helper->getStatusQuery($service);
                        if($lastUpdatedTime)
                                $query_modifiedtime = " and i.date_modified >= '{$lastUpdatedTime}'";

                        $service_mode = getModRecSelection('Invoice');

                        if($mode == 'edit')     {
                                if($lastUpdatedTime)    {
                                        $query = "SELECT count(*) as count FROM aos_invoices i where deleted = 0 $query_modifiedtime and i.id IN (select crmid from sugar_qbids where module = 'Invoice') $addQBQuery $statusQuery";
                                }
                                else    {
                                        $this->updateLastModifiedTime($service);
                                        die($count);
                                }
                        }
                        else if($mode == 'create')      {
                                // query to get contacts which are not added to quickbooks
                                $query = "SELECT count(*) as count FROM aos_invoices i left join sugar_qbids ei on ei.crmid = i.id and ei.module = 'Invoice' where ei.crmid is NULL and deleted = 0 $addQBQuery $statusQuery";
                        }
                }
		else if($service == 'QBItem')	{
			if($lastUpdatedTime)
                                $query_modifiedtime = " and c.date_modified >= '{$lastUpdatedTime}'";

                        $service_mode = getModRecSelection('Products');

                        if($mode == 'edit')     {
                                if($lastUpdatedTime)    {
                                        $query = "SELECT count(*) as count FROM aos_products c where deleted = 0 $query_modifiedtime and c.id IN (select crmid from sugar_qbids where module = 'Products') $addQBQuery";
                                }
                                else    {
                                        $this->updateLastModifiedTime($service);
                                        die($count);
                                }
                        }
                        else if($mode == 'create')      {
                                // query to get contacts which are not added to quickbooks
                                $query = "SELECT count(*) as count FROM aos_products c left join sugar_qbids ei on ei.crmid = c.id and ei.module = 'Products' where ei.crmid is NULL and deleted = 0 $addQBQuery";
                        }
                }
		$count = $db->getOne($query);
                die($count);
	}

	public function constructSmarty($parser)	{
		$smarty = new Sugar_Smarty();
		return $smarty ;
	}
}
