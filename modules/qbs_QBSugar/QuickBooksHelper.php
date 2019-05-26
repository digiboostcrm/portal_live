<?php
/**
 *	Author: Rajkumar Mohan
 *	Company: Smackcoders
 *	Date:	5-1-2013
 **/
class Quickbooks_GokuHelper
{
        public $createdTime;
        public $modifiedTime;
        public $message = array();

        public function  __construct()
        {
                $this->createdTime  = date("Y-m-d H:i:s");
                $this->modifiedTime = date("Y-m-d H:i:s");
		$this->message['content'] = null;
                $this->message['success']['create'] = 0;
                $this->message['success']['edit'] = 0;
                $this->message['failure']['create'] = 0;
		$this->message['failure']['edit']  = 0;
        }

	/**
         * delete failure entry failureQueue
         * @param string moduleid
         **/
        public function deleteFailureQueue($moduleid)
        {
                global $db;
                $db->pquery("update qbs_qbqueue set deleted = 1 where qborvtid = '?'", array($moduleid));
        }

        /**
         *      This function get the mapping field array
         *      @param modulename
         **/

        function getQBTigerMapping($module)
        {
                global $db;
                $mapfields = null;
                $getMapping = $db->getOne('select mapping from sugar_qbfieldmapping where module = "' . $module . '"');
                if(trim($getMapping) != '')      {
                       $mapfields = unserialize(base64_decode($getMapping));
                }
                return $mapfields;
        }

	function addFailureQueue($vtid, $mod, $source, $mode, $errormsg = "", $qbid = "")
        {
                global $db;
                # Check whether id already inserted into queue. If so Update it
                $getQueueObj = $db->pquery("select id, failcount from qbs_qbqueue where qborvtid = '?' and source = '?' and qbqueuemode = '?'", array($vtid, $source, $mode));
		$rowCount = $db->getRowCount($getQueueObj);
                if($rowCount >= 1)	{
			// get values from queue table
			$getQueue = $db->fetchByAssoc($getQueueObj);
	                $queueid = $getQueue['id'];
        	        $failcount = $getQueue['failcount'];
                	$failcount = $failcount + 1;
			// increment failcount and update value in table
                        $db->pquery("update qbs_qbqueue set failcount = '?', description = '?' where id = '?'",array($failcount, $errormsg, $queueid));
                }
                else	{
			global $current_user;
		        $userId = $current_user->id;
			if(empty($userId))
	                        $userId = getSugarAdminUserId();

			require_once('modules/qbs_QBQueue/qbs_QBQueue.php');
			$QBQueue = new qbs_QBQueue();
			$QBQueue->id = '';
			$QBQueue->mode = 'create';
			$QBQueue->qborvtid = $vtid;
			$QBQueue->name = $mod;
			$QBQueue->source = $source;
			$QBQueue->qbqueuemode = $mode;
			$QBQueue->description = $errormsg;
			$QBQueue->assigned_user_id = $userId;
			$QBQueue->failcount = 0;
			$QBQueue->save();
                }
		$log_id = createLog('Suite => Quickbooks', $mode, 'fail', $errormsg, $vtid , $qbid, $mod);
		return $log_id;
        }

        /**
	 * get id from quickbooks account
	 * @param  object   $QuickbooksConfig 
	 * @param  interger $accountName
	 * @return string   $accountId
	 */
	public function getAccountId($QuickbooksConfig, $accountName)	{
		if(empty($accountName))
			return false;

		$response = $this->init($QuickbooksConfig);

		if(!$response)
			$this->handle_error('unable_to_load_context');

		$Service = new QuickBooks_IPP_Service_Account();

		$account = $Service->query($response['context'], $response['realm'], "SELECT * FROM Account where Name = '$accountName'");
		if($account)	{
			$accountId = $account[0]->get('Id');
		}
		return $accountId;

	}


        /**
	 * get name from quickbooks account
	 * @param  object   $QuickbooksConfig 
	 * @param  interger $id
	 * @return string   $accountName
	 */
	public function getAccountName($QuickbooksConfig, $id)	{

        if(empty($id))
            return false;

        $response = $this->init($QuickbooksConfig);

        if(!$response)
            $this->handle_error('unable_to_load_context');

		        $Service = new QuickBooks_IPP_Service_Account();

			$trim_QBId = substr($id ,1); $trim_QBId = substr($trim_QBId, 0, -1);
                        $trim_QBId = abs($trim_QBId);
			$account = $Service->query($response['context'], $response['realm'], "SELECT * FROM Account where id = '$trim_QBId'");

			if($account)	{
				$accountName = $account[0]->get('Name');
			}
			return $accountName;
	}

        /**
	 * get expense account from quickbooks
	 * @param object $QuickbooksConfig 
	 * @return array $expenseAccountList
	 */
        public function getExpenseAccount($QuickbooksConfig)
        {
                # Set up the IPP instance
                $IPP = new QuickBooks_IPP($QuickbooksConfig->dsn);

                # Get our OAuth credentials from the database
                $creds = $QuickbooksConfig->IntuitAnywhere->load($QuickbooksConfig->username, $QuickbooksConfig->tenant);

                # Tell the framework to load some data from the OAuth store
                $IPP->authMode(QuickBooks_IPP::AUTHMODE_OAUTH, $QuickbooksConfig->username, $creds);

                # Current realm
                $realm = $creds['qb_realm'];

		$expenseAccountList = array();
                # Load the OAuth information from the database
                if ($Context = $IPP->context())
                {
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);

                        // Set the DBID
                        $IPP->dbid($Context, 'dbid');

                        // Set the IPP flavor
                        $IPP->flavor($creds['qb_flavor']);
                        // Get the base URL if it's QBO
                        if ($creds['qb_flavor'] == QuickBooks_IPP_IDS::FLAVOR_ONLINE)
                        {
//                                $IPP->baseURL($IPP->getBaseURL($Context, $realm));
                        }

		        $Service = new QuickBooks_IPP_Service_Account();

			//$expenseAccount = $Service->query($Context, $realm, "SELECT * FROM Account where AccountType = 'Expense' and active = true");
			$expenseAccount = $Service->query($Context, $realm, "SELECT * FROM Account where active = true");

			if($expenseAccount)	{
				foreach($expenseAccount as $singleExpenseAccount)	{
					$expenseAccountList[] = $singleExpenseAccount->get('Name');
				}
			}
			return $expenseAccountList;
                }
                else
                {
                        return $expenseAccountList;
                }
        }

        /**
	 * get income account from quickbooks
	 * @param object $QuickbooksConfig 
	 * @return array $incomeAccount 
	 */
        public function getIncomeAccount($QuickbooksConfig)
        {
                # Set up the IPP instance
                $IPP = new QuickBooks_IPP($QuickbooksConfig->dsn);

                # Get our OAuth credentials from the database
                $creds = $QuickbooksConfig->IntuitAnywhere->load($QuickbooksConfig->username, $QuickbooksConfig->tenant);

                # Tell the framework to load some data from the OAuth store
                $IPP->authMode(QuickBooks_IPP::AUTHMODE_OAUTH, $QuickbooksConfig->username, $creds);

                # Current realm
                $realm = $creds['qb_realm'];

		$incomeAccountList = array();
                # Load the OAuth information from the database
                if ($Context = $IPP->context())
                {
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);

                        // Set the DBID
                        $IPP->dbid($Context, 'dbid');

                        // Set the IPP flavor
                        $IPP->flavor($creds['qb_flavor']);
                        // Get the base URL if it's QBO
                        if ($creds['qb_flavor'] == QuickBooks_IPP_IDS::FLAVOR_ONLINE)
                        {
//                                $IPP->baseURL($IPP->getBaseURL($Context, $realm));
                        }

		        $Service = new QuickBooks_IPP_Service_Account();

			//$incomeAccount = $Service->query($Context, $realm, "SELECT * FROM Account where AccountType = 'Income' and active = true");
			$incomeAccount = $Service->query($Context, $realm, "SELECT * FROM Account where active = true");
			if($incomeAccount)	{
				foreach($incomeAccount as $singleIncomeAccount)	{
					$incomeAccountList[] = $singleIncomeAccount->get('Name');
				}
			}
			return $incomeAccountList;
                }
                else
                {
                        return $incomeAccountList;
                }
        }



        /**
         *      This Function will get values by Id from Quickbooks.
         *      @param Quickbooks_GokuConfig Object
         **/

        function getQBValuesById($QuickbooksConfig, $module, $id)
        {
                # Set up the IPP instance
                $IPP = new QuickBooks_IPP($QuickbooksConfig->dsn);

                # Get our OAuth credentials from the database
                $creds = $QuickbooksConfig->IntuitAnywhere->load($QuickbooksConfig->username, $QuickbooksConfig->tenant);

                # Tell the framework to load some data from the OAuth store
                $IPP->authMode(QuickBooks_IPP::AUTHMODE_OAUTH, $QuickbooksConfig->username, $creds);

                # Current realm
                $realm = $creds['qb_realm'];

                # Load the OAuth information from the database
                if ($Context = $IPP->context())
                {
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);

                        // Set the DBID
                        $IPP->dbid($Context, 'dbid');

                        // Set the IPP flavor
                        $IPP->flavor($creds['qb_flavor']);
                        // Get the base URL if it's QBO
                        if ($creds['qb_flavor'] == QuickBooks_IPP_IDS::FLAVOR_ONLINE)
                        {
//                               $IPP->baseURL($IPP->getBaseURL($Context, $realm));
                        }

			$ServiceName = "QuickBooks_IPP_Service_".$module;
		        $Service = new $ServiceName();

			$trim_QBId = substr($id ,1); $trim_QBId = substr($trim_QBId, 0, -1);
			$trim_QBId = abs($trim_QBId);
			$list = $Service->query($Context, $realm, "SELECT * FROM $module where Id = '$trim_QBId'");
			if($list)
				return $list; 
			else 
				return "No $module present in Quickbooks"; 

                }
                else
                {
                        return('Unable to load Context');
                }
        }

	/**
	 * return total count of record from quickbooks
	 * @param object QuickbooksConfig
	 * @param string module
	 */
	public function getQBValuesCount($QuickbooksConfig, $module, $modOfTransfer)
	{
		// Set up the IPP instance
		$IPP = new QuickBooks_IPP($QuickbooksConfig->dsn);

		// Set up IntuitAnywhere Instance
		$creds = $QuickbooksConfig->IntuitAnywhere->load($QuickbooksConfig->username, $QuickbooksConfig->tenant);

		// Tell the framework to load some data from the OAuth store
		$IPP->authMode(QuickBooks_IPP::AUTHMODE_OAUTH, $QuickbooksConfig->username, $creds);

		// Current realm
		$realm = $creds['qb_realm'];

		// Load the OAuth information from the database
		if ($Context = $IPP->context())
		{
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);

			// Set the DBID
			$IPP->dbid($Context, 'dbid');

			// Set the IPP flavor
			$IPP->flavor($creds['qb_flavor']);

			// Get the base URL if it's QBO
			if ($creds['qb_flavor'] == QuickBooks_IPP_IDS::FLAVOR_ONLINE)
			{
//				$IPP->baseURL($IPP->getBaseURL($Context, $realm));
			}

 
			$Helper = new Quickbooks_vtigerHelper();
			$ServiceName = "QuickBooks_IPP_Service_".$module;
			if($module == 'Item')
				$ServiceName = "QuickBooks_IPP_Service_Term";

			$Service = new $ServiceName();
             if($module=='SalesOrder'){
				$module='SalesReceipt';
			}
			$updatedtime = getLastSyncDetails($module);

			global $db;
                        // check whether lastaddedids present in table. if so some interuption occured while last sync. start sync from there
                        $getLastUpdatedTime = $db->getOne('select lastaddedids from sugar_qbsyncdetails where module = "' . $module . '"');
                        if(!empty($getLastUpdatedTime)) {
                        	$splitted_result = explode(',', $getLastUpdatedTime);
                                $lastUpdatedTime = $splitted_result[0];
                        }
			else	{
				// if last updated time is not set
                        	$lastUpdatedTime = $db->getOne('select lastsync from sugar_qbsyncdetails where module = "' . $module . '"');
			}

			if(!empty($lastUpdatedTime) && trim($lastUpdatedTime) != '')
                                $where = "Metadata.LastUpdatedTime >= '$lastUpdatedTime'";

			if($module == 'Invoice' || $module == 'Estimate' || $module == 'SalesReceipt')
			{
				if($where)
					$trans_where = " where $where";

				if($module == 'Item')
					$query = "SELECT * FROM $module $trans_where";
				else
					$query = "SELECT count(*) FROM $module $trans_where";

			}
			else
			{
				if($where)
					$trans_where = " $where and active = true";
				else
					$trans_where = " active = true";

				if($module == 'Item')
					$query = "SELECT * FROM $module where $trans_where";
				else
					$query = "SELECT count(*) FROM $module where $trans_where";

			}

			$response = $Service->query($Context, $realm, $query);
			if($module == 'Item')
				$response = count($response);

			if($modOfTransfer == 'cron')
				return $response;

			echo $response;die;
		}
		else
		{
			echo 'Unable to load Context';die;
		}
	}

        /**
	 * records from quickbooks will be sent to vtiger to sync (limited records not all the records)
         * @param object $quickbooksConfig
	 * @param string $module
	 * @param object $vtigerHelper
	 * @param integer $startPosition
  	 * @param integer $maxResult
	 * @param datetime $lastUpdatedTime
	 * @param string $finalcall
	 * @param object $helper
         */
	function processQuickbooksModule($quickbooksConfig, $module, $vtigerHelper, $startposition, $maxresult, $lastUpdatedTime, $finalcall, $helper, $modOfTransfer)
	{
		// Set up the IPP instance
		$IPP = new QuickBooks_IPP($quickbooksConfig->dsn);

		// Set up IntuitAnywhere Instance
		$creds = $quickbooksConfig->IntuitAnywhere->load($quickbooksConfig->username, $quickbooksConfig->tenant);

		// Tell the framework to load some data from the OAuth store
		$IPP->authMode(QuickBooks_IPP::AUTHMODE_OAUTH, $quickbooksConfig->username, $creds);

		// Current realm
		$realm = $creds['qb_realm'];

		// Load the OAuth information from the database
		if($Context = $IPP->context())
		{
			$IPP->version(QuickBooks_IPP_IDS::VERSION_3);

			// Set the DBID
			$IPP->dbid($Context, 'dbid');

			// Set the IPP flavor
			$IPP->flavor($creds['qb_flavor']);

			// Get the base URL if it's QBO
			if ($creds['qb_flavor'] == QuickBooks_IPP_IDS::FLAVOR_ONLINE)
			{
//				$IPP->baseURL($IPP->getBaseURL($Context, $realm));
			}

			global $db;
			if($startposition != 0)	{
	 			 // no need to get lastaddedids which is act as cache time. if we use that time, then there might be chance of lossing data 
				$lastUpdatedTime = $db->getOne('select lastsync from sugar_qbsyncdetails where module = "' . $module . '"');
			}
			else	{
				// first call
				// check whether lastaddedids present in table. if so some interuption occured while last sync. start sync from there
				$getLastUpdatedTime = $db->getOne('select lastaddedids from sugar_qbsyncdetails where module = "' . $module . '"');
				if(!empty($getLastUpdatedTime))	{
					$splitted_result = explode(',', $getLastUpdatedTime);
	                                $lastUpdatedTime = $splitted_result[0];
					// start position not need for first call becoz we will use cached updatedtime alone to get records from quickbooks
				}
			}

			$ServiceName = "QuickBooks_IPP_Service_".$module;
			if($module == 'Item')
				$ServiceName = "QuickBooks_IPP_Service_Term";

			$Service = new $ServiceName();
			if(!empty($lastUpdatedTime) && trim($lastUpdatedTime) != '')    
				$where = "Metadata.LastUpdatedTime >= '$lastUpdatedTime'";
	
			if($module == 'Invoice' || $module == 'Estimate' || $module == 'SalesReceipt')
			{
				if($where)
					$trans_where = "where $where ";

				$query = "SELECT * FROM $module $trans_where ORDER BY Metadata.LastUpdatedTime ASC STARTPOSITION $startposition MAXRESULTS $maxresult";
			}
			else
			{
				if($where)
					$trans_where = " and $where";

				$query = "SELECT * FROM $module where active = true $trans_where ORDER BY Metadata.LastUpdatedTime ASC STARTPOSITION $startposition MAXRESULTS $maxresult";
			}

			$response = $Service->query($Context, $realm, $query);
			$response_count = count($response);
			$startposition = $startposition + $maxresult;
			if($response_count != 0 && !empty($response_count))	{
				if($module == 'Customer')
					$res = $vtigerHelper->addContacts($response);
				else if($module == 'Item')
					$res = $vtigerHelper->addProducts($response);
				else if($module == 'Invoice')
					$res = $vtigerHelper->addInvoice($response,'invoice');
				else if($module == 'SalesReceipt')
					$res = $vtigerHelper->addInvoice($response,'salesreceipt');
				/*else if($module == 'Invoice')
					$res = $vtigerHelper->addInvoice($response);
				else if($module == 'SalesReceipt')
					$res = $vtigerHelper->addSalesOrder($response);*/
				else if($module == 'Estimate')
					$res = $vtigerHelper->addQuotes($response);
				else if($module == 'Vendor')
					$res = $vtigerHelper->addVTVendor($response); 

				$lastId = $response_count - 1;
				$lastUpdatedTime = $this->getLastUpdatedTime($response[$lastId]);
				// if call is final then update the lastmodifiedtime to lastsync column else update the time on lastaddedids
				if($modOfTransfer == 'cron')	{
					$helper->updateLastModifiedTimeCron($module, $lastUpdatedTime, $finalcall, $startposition);	
				}
				else	{
					$helper->updateLastModifiedTime($module, $lastUpdatedTime, $finalcall, $startposition, null);
				}
			}
			return $res;
		}
		else
		{
			return 'Unable to load Context';
		}
	}

	/**
	 * return lastupdatetime of given object
	 * @param object $service
	 * @return string $lastUpdatedTime
	 */
	public function getLastUpdatedTime($service)
	{
		$lastUpdatedTime = null;
		if($service)	{
			$lastUpdatedTime = $service->get('MetaData')->get('LastUpdatedTime');
		}
		return $lastUpdatedTime;	
	}


	/**
	 * add items to quickbooks
	 * @param array $ProductObj
	 * @param object QuickbooksConfig
	 **/
	public function addItemToQB($ProductObj, $QuickbooksConfig, $return = false)	{
		global $db;
		$helper = new qbtigerHelper();
		$response = $this->init($QuickbooksConfig);

		if(!$response)
			$this->handle_error('unable_to_load_context');

			if($ProductObj)	{
				foreach($ProductObj as $single_item)	{	
					$getMapping = $db->getOne("select mapping from sugar_qbfieldmapping where module = 'Products'");
					if(trim($getMapping) != '' && !empty($getMapping))      {
						$mapfields = unserialize(base64_decode($getMapping));
					}

					$ItemObj = new Quickbooks_Item();
					// For Record Duplication.
					$checkDuplication = getDuplication();
					if($checkDuplication == 'Add Suffix')   
						$dup_productname = $single_item[$proname]." ".$single_item[$prono];
					else	
						$dup_productname = $single_item['name'];

					$ItemObj->ItemName = $dup_productname;
					$ItemObj->ItemAmount = $single_item['price'];
					$ItemObj->ItemDesc = $single_item[$mapfields['description']];
					$ItemObj->VTItemId = $single_item['id'];

                                        $ItemObj->QtyOnHand = $single_item[$mapfields['qbqtyinhand_c']];
                                        $ItemObj->InvStartDate = $single_item[$mapfields['qbstartdate_c']];
					$ItemObj->Type = $single_item['qbtype_c'];

					$ItemObj->incomeAccount = $this->getAccountId($QuickbooksConfig, $single_item[$mapfields['qbincomeaccount_c']]);
					$ItemObj->expenseAccount = $this->getAccountId($QuickbooksConfig, $single_item[$mapfields['qbexpenseaccount_c']]);
				        $ItemObj->assetAccount = $this->getAccountId($QuickbooksConfig, $single_item[$mapfields['qbassetaccount_c']]);
					$ItemObj->purchaseCost = $single_item['cost'];
					$ItemObj->purchaseDescription = $single_item[$mapfields['purchasedescription']];

					$ItemQBObj = new QuickBooks_IPP_Object_Item();
					$ItemQBObj->setName($ItemObj->ItemName);
					$ItemQBObj->setFullyQualifiedName($ItemObj->ItemName);
					$ItemQBObj->setDesc($ItemObj->ItemDesc);
                                        $ItemQBObj->setType($ItemObj->Type);

                                        $ItemQBObj->setQtyOnHand($ItemObj->QtyOnHand);
                                        if(!empty($ItemObj->InvStartDate))      {
                                                $inventory_start_date = date("Y-m-d", strtotime($ItemObj->InvStartDate));
                                                $ItemQBObj->setInvStartDate($inventory_start_date);
                                        }

					$ItemQBObj->setUnitPrice($ItemObj->ItemAmount);

					$ItemQBObj->setIncomeAccountRef($ItemObj->incomeAccount);
					$ItemQBObj->setExpenseAccountRef($ItemObj->expenseAccount);
				        $ItemQBObj->setAssetAccountRef($ItemObj->assetAccount);
					$ItemQBObj->setPurchaseCost($ItemObj->purchaseCost);
					$ItemQBObj->setPurchaseDesc($ItemObj->purchaseDescription);
                                        if($ItemObj->Type == 'Inventory' || $ItemObj->Type == 'Stock')
                                                $ItemQBObj->setTrackQtyOnHand(true);
					$Service = new QuickBooks_IPP_Service_Item();
					$response1 = $Service->add($response['context'], $response['realm'], $ItemQBObj);
					if($response1)	{
						$db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($ItemObj->VTItemId, $response1, 'Products'));
						$this->message['success']['create'] = $this->message['success']['create'] + 1;
						createLog('Suite => Quickbooks', 'create', 'pass', "",  $ItemObj->VTItemId, $response, 'Products');
						$this->deleteFailureQueue($ItemObj->VTItemId);
	                                        $smackHelper = new Quickbooks_vtigerHelper();
	                                        $smackHelper->updateQBExtravalues('aos_products', $QBId, $response1->VTItemId, $mode, 'id');

						$this->message['content'] .= $helper->generateMessageContent('AOS_Products', $dup_productname, $mode, $response1, $ItemObj->VTItemId, 'success', 'quickbooks');
                        if($return)
                            return $response1;
					}	
					else
					{
						$errormsg = $Service->errorDetail();
						$this->message['failure']['create'] = $this->message['failure']['create'] + 1;
						$log_id = $this->addFailureQueue($ItemObj->VTItemId, 'Products', 'sugarcrm', 'create', $errormsg, $ItemObj->VTItemId);	
						$this->message['content'] .= $helper->generateMessageContent('AOS_Products', $dup_productname, $mode, $response1, $ItemObj->VTItemId, 'fail', 'quickbooks', $log_id);
						if($return)
							return 'Failed';
					}
				}
			}
		return $this->message;
	}

	/**
	 * update items to quickbooks
	 * @param array ProductObj
	 * @param object QuickbooksConfig
	 **/
	public function updateItemToQB($ProductObj, $QuickbooksConfig)
	{
		global $db;
		$helper = new qbtigerHelper();
		$response = $this->init($QuickbooksConfig);

		if(!$response)
			$this->handle_error('unable_to_load_context');

			if($ProductObj)	{
				foreach($ProductObj as $single_item)	
				{
					$getMapping = $db->getOne("select mapping from sugar_qbfieldmapping where module = 'Products'");
					if(trim($getMapping) != '' && !empty($getMapping))      {
						$mapfields = unserialize(base64_decode($getMapping));
					}

					$dup_productname = $single_item['name'];

					$ItemObj = new Quickbooks_Item();
					$ItemObj->ItemName = $dup_productname;
					$ItemObj->ItemAmount = $single_item['price'];
                                        $ItemObj->ItemDesc = $single_item[$mapfields['description']];
					$ItemObj->VTItemId = $single_item['id'];

                                        $ItemObj->QtyOnHand = $single_item[$mapfields['qbqtyinhand_c']];
                                        $ItemObj->InvStartDate = $single_item[$mapfields['qbstartdate_c']];
                                        $ItemObj->Type = $single_item['qbtype_c'];

					$ItemObj->incomeAccount = $this->getAccountId($QuickbooksConfig, $single_item[$mapfields['qbincomeaccount_c']]);
					$ItemObj->expenseAccount = $this->getAccountId($QuickbooksConfig, $single_item[$mapfields['qbexpenseaccount_c']]);
                                        $ItemObj->assetAccount = $this->getAccountId($QuickbooksConfig, $single_item[$mapfields['qbassetaccount_c']]);
					$ItemObj->purchaseCost = $single_item['cost'];
					$ItemObj->purchaseDescription = $single_item[$mapfields['purchasedescription']];

					$QBId = $db->getOne("select qbid from sugar_qbids where module = 'Products' and crmid = '{$single_item['id']}'");

					$Service = new QuickBooks_IPP_Service_Item();
					$trim_QBId = substr($QBId ,1); $trim_QBId = substr($trim_QBId, 0, -1);
					$trim_QBId = abs($trim_QBId);
					$query = "SELECT * FROM Item WHERE Id = '$trim_QBId' ";
					$getSRQB = $Service->query($response['context'], $response['realm'], $query);

					$ItemQBObj = new QuickBooks_IPP_Object_Item(); 
					$ItemQBObj->setName($ItemObj->ItemName);	
					$ItemQBObj->setFullyQualifiedName($ItemObj->ItemName);
					$ItemQBObj->setDesc($ItemObj->ItemDesc);
					$ItemQBObj->setType($ItemObj->Type);

					$ItemQBObj->setQtyOnHand($ItemObj->QtyOnHand);
					if(!empty($ItemObj->InvStartDate))      {
						$inventory_start_date = date("Y-m-d", strtotime($ItemObj->InvStartDate));
						$ItemQBObj->setInvStartDate($inventory_start_date);
					}

					$ItemQBObj->setSyncToken($getSRQB[0]->getSyncToken());
					$ItemQBObj->setId($QBId);
					$ItemQBObj->setUnitPrice($ItemObj->ItemAmount);

					$ItemQBObj->setIncomeAccountRef($ItemObj->incomeAccount);
					$ItemQBObj->setExpenseAccountRef($ItemObj->expenseAccount);
					$ItemQBObj->setPurchaseCost($ItemObj->purchaseCost);
					$ItemQBObj->setPurchaseDesc($ItemObj->purchaseDescription);
					if($ItemObj->Type == 'Inventory' || $ItemObj->Type == 'Stock')
						$ItemQBObj->setTrackQtyOnHand(true);

					$response1 = $Service->update($response['context'], $response['realm'], $QBId, $ItemQBObj);
					$mode = 'edit';

					if($response1)	{
						$this->message['success']['edit'] = $this->message['success']['edit'] + 1;
						createLog('Suite => Quickbooks', 'edit', 'pass', "",  $single_item['id'], $QBId, 'Products');
						$this->deleteFailureQueue($single_item['id']);
                                                $smackHelper = new Quickbooks_vtigerHelper();
                                                $smackHelper->updateQBExtravalues('aos_products', $QBId, $single_item['id'], $mode, 'id');
						$this->message['content'] .= $helper->generateMessageContent('AOS_Products', $dup_productname, $mode, $QBId, $single_item['id'], 'success', 'quickbooks');
					}	
					else	{
						$errormsg = $Service->errorDetail();
						$this->message['failure']['edit'] = $this->message['failure']['edit'] + 1;
						$log_id = $this->addFailureQueue($ItemObj->VTItemId, 'Products', 'sugarcrm', 'edit', $errormsg);				
						$this->message['content'] .= $helper->generateMessageContent('AOS_Products', $dup_productname, $mode, $QBId, $single_item['id'], 'fail', 'quickbooks', $log_id);
					}
				}
			}
		return $this->message;
	}

/**
 * add estimate to quickbooks
 * @param array $SEObject
 * @param object QuickbooksConfig
 **/
public function addEstimateToQB($SEObject, $QuickbooksConfig)
{
	global $db;
	$mode = 'create';
	$helper = new qbtigerHelper();

	foreach($SEObject as $SEObj)	{
		// if contact not related to quote, no need to proceed. quote is not 
		if(empty($SEObj['billing_contact_id']))	{
			$this->message['content'] = 'Contact not related to Invoice <br>';
		}
		else
		{
			$response = $this->init($QuickbooksConfig);

			if(!$response)
				$this->handle_error('unable_to_load_context');

				$SE = new QuickBooks_IPP_Object_Estimate();
                                $country = $db->getOne('select country from sugar_qbtigersettings');
				$Products = null;
				// get related products using quote id
				$getProductObj = $db->pquery("select * from aos_products_quotes where parent_id = '?'", array($SEObj['id']));
				if($getProductObj)	{
					while($fetchProduct = $db->fetchByAssoc($getProductObj))
						$Products[] = $fetchProduct;

				}

				$linecount = 1;
                                $ItemService = new QuickBooks_IPP_Service_Item();
                                foreach($Products as $single_product)	{
					// check whether product already added to sugar
                                        $checkProduct = $db->pquery("select * from sugar_qbids where crmid = '?' and module = '?'",array($single_product['id'], 'Products'));
                                        $countProduct = $db->getRowCount($checkProduct);
					if($countProduct > 0)  {
						$QBItemId = $db->getOne("select qbid from sugar_qbids where crmid = '{$single_product['id']}' and module = 'Products'");
                                        }

                                        if($country == 'US'){
                                                $vat = $single_product['vat'];
                                                preg_match('/^\d+/', $vat, $result);
                                                $result1 = explode($result[0] . ' ', $vat);
                                                $vat = implode($result1);
                                                $vatId = $db->getOne("select qbid from sugar_qbtaxcodemapping where name = '$vat'");
                                                $txnTaxCodeRef = $vatId;
                                                $taxCodeRef = 'TAX';
                                                if(empty($txnTaxCodeRef))
                                                        $txnTaxCodeRef = 'TAX';
                                        }
                                        else{
                                                $vat = $single_product['vat'];
                                                $vatId = $db->getOne("select qbid from sugar_qbtaxcodemapping where name = '$vat'");
                                                $taxCodeRef = $vatId;
                                                $txnTaxCodeRef = $taxCodeRef;
                                                if(empty($taxCodeRef))
                                                {
                                                        $taxCodeRef = '{-13}';
                                                        $txnTaxCodeRef = '{-13}';
                                                }

                                        }

					
					// if product not added to quickbooks. add product
                                        if($countProduct <= 0 || empty($countProduct))	{
						// check whether product already present in that name
						$IS = new QuickBooks_IPP_Service_Term();
                                                $query = "select * from Item where Name = '{$single_product['name']}'";
                                                $itemInfo = $IS->query($response['context'],$response['realm'], $query);
						$itemInfo = $itemInfo[0];

                                                $mappingPro = $this->getQBTigerMapping('Products');
                                                if(empty($itemInfo))	{
	                                                // add product to quickbooks
        	                                        $QBItemObj = new Quickbooks_Item();
        	        		                $dup_productname = $single_product['id'];

							// get product information from sugar products table to add new product to quickbooks
							// dont get product information from variable - $single_product
							// variable contains product information of quotes which may differ from original product information
							$currentProductObj = array();
							$currentProductObj = $db->pquery("select * from aos_products where id = '?'", array($single_product['product_id']));
							$currentProductInfo = $db->fetchByAssoc($currentProductObj);
							if(empty($currentProductInfo))  {
//                                                                continue;
                                                                $currentProductObj = $db->pquery("select * from aos_products_quotes where name = '?' and parent_id = '?'", array($single_product['name'], $InvoiceObj['id']));
                                                                $currentProductInfo = $db->fetchByAssoc($currentProductObj);
                                                                $currentProductInfo['price'] = $currentProductInfo['product_unit_price'];
                                                                $currentProductInfo['qbincomeaccount_c'] = 'Sales';
                                                                $currentProductInfo['qbtype_c'] = 'Service';

                                                        }

							$QBItemObj->ItemName = $currentProductInfo['name'];
							$QBItemObj->ItemAmount = $currentProductInfo['price'];
        		                                $QBItemObj->ItemDesc = $currentProductInfo[$mappingPro['desc']];
	                	                        $QBItemObj->VTItemId = $currentProductInfo['id'];
							$QBItemObj->incomeAccount = $currentProductInfo[$mappingPro['qbincomeaccount_c']];
							$QBItemObj->expenseAccount = $currentProductInfo[$mappingPro['qbexpenseaccount_c']];
							$QBItemObj->purchaseCost = $currentProductInfo['cost'];
							$QBItemObj->purchaseDescription = $currentProductInfo[$mappingPro['purchasedescription']];
                                                        $QBItemId = $this->addItemToQB(array($currentProductInfo), $QuickbooksConfig, true);
                                                        //If Product failed means skip
                                                        if($QBItemId == 'Failed')
                                                                continue;

							// add product information which are related to quotes
							// done get information from $currentProductInfo
							// variable contains original product information which may differ from related product information
                	                                $itemQuantity = $single_product['product_qty'];
							if(empty($itemQuantity))
                                                                $itemQuantity = 1;

                        	                        $itemDis = $single_product['product_discount_amount'];
							$itemAmount = ($single_product['product_unit_price'] - $itemDis) * $single_product['product_qty'];
	                                                $itemId = $QBItemId;
        	                                        $itemPrice = $single_product['product_total_price'];
                	                                $itemDesc = $single_product['item_description'];
                                                }
                                                else	{
	                                                $itemId = $itemInfo->get('Id');
							$itemDis = $single_product['product_discount_amount'];
							$itemPrice = $single_product['product_unit_price'];
							$itemQuantity = $single_product['product_qty'];
							if(empty($itemQuantity))
                                                                $itemQuantity = 1;

							$itemDesc = $single_product['item_description'];
                                                	$itemAmount = ($itemPrice - $itemDis) * $itemQuantity;

							// add entry to qbids table
							$db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')", array($single_product['id'], $itemId, 'Products'));
                                                }
                                        }
					else	{
						$itemId = $QBItemId;
						$itemDis = $single_product['product_discount_amount'];
						$itemPrice = $single_product['product_unit_price'];
						$itemQuantity = $single_product['product_qty'];
						if(empty($itemQuantity))
                                                	$itemQuantity = 1;

						$itemDesc = $single_product['item_description'];
						$itemAmount = ($itemPrice - $itemDis) * $itemQuantity;
                                        }

					$Line = new QuickBooks_IPP_Object_Line();
                                        $Line->setDescription($itemDesc);
                                        $Line->setTaxable('true');
                                        $lineid = "{-".$linecount."}";
                                        $Line->setId($lineid);
                                        $Line->setAmount($itemAmount);
                                        $Line->setDetailType('SalesItemLineDetail');
                                        $Line->setLineNum($linecount);

                                        $SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
                                        $SalesItemLineDetail->setItemRef($itemId);
                                        $SalesItemLineDetail->setUnitPrice($itemPrice);
                                        $SalesItemLineDetail->setQty($itemQuantity);
					$SalesItemLineDetail->setTaxCodeRef($taxCodeRef);
                                        $Line->setSalesItemLineDetail($SalesItemLineDetail);

                                        $SE->addLine($Line);
                                        $linecount = $linecount + 1;
                                }

				$finalDiscount = $SEObj['discount_amount'];
				$totalTax = $SEObj['tax_amount'];
				$shippingAndHandlingCharge = $SEObj['shipping_amount']; 
                                if(empty($shippingAndHandlingCharge))
                                        $shippingAndHandlingCharge = 0;

				$Line = new QuickBooks_IPP_Object_Line();
				$Line->setAmount($SEObj['subtotal_amount']);
				$Line->setDetailType('SubTotalLineDetail');
				$SE->addLine($Line);
				
				$Line = new QuickBooks_IPP_Object_Line();
				//$Line->setAmount($finalShippingAndHandlingCharge);
				$Line->setAmount($shippingAndHandlingCharge);
				$Line->setDetailType('SalesItemLineDetail');
				$SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
                                $SalesItemLineDetail->setItemRef('SHIPPING_ITEM_ID');
                                if($country == 'GB'){
                                        $shippingTaxCode = $SEObj['shipping_tax'];
                                        $shippingTaxCodeRef = $db->getOne("select qbid from sugar_qbtaxcodemapping where value = '$shippingTaxCode'");
                                        $SalesItemLineDetail->setTaxCodeRef($shippingTaxCodeRef);
                                }
                                $Line->setSalesItemLineDetail($SalesItemLineDetail);
				$SE->addLine($Line);

				$Line = new QuickBooks_IPP_Object_Line();
				$Line->setAmount($finalDiscount);
				$Line->setDetailType('DiscountLineDetail');
				$SalesItemLineDetail = new QuickBooks_IPP_Object_DiscountLineDetail();
				// name - Discount's Given. value 50 is hardcoded. referred https://developer.intuit.com/apiexplorer?apiname=V3QBO
				$SalesItemLineDetail->setDiscountAccountRef(50);
				$Line->setDiscountLineDetail($SalesItemLineDetail);
				$SE->addLine($Line);

				// check whether tax added on quickbooks. if so add tax amount using added tax id
                                $taxCode = $this->getTaxCodes($response['context'], $response['realm']);
                                if($taxCode)    {
					if($totalTax > 0)	{
                        	                $TaxField = new QuickBooks_IPP_Object_TxnTaxDetail();
                	                        $TaxField->setTxnTaxCodeRef($txnTaxCodeRef);
        	                                $TaxField->setTotalTax($totalTax);
	                                        $SE->addTxnTaxDetail($TaxField);
					}
                                }
                                $SE->setTotalAmt($SEObj['total_amount']);

				// to retrieve customer information
				$CustomerService = new QuickBooks_IPP_Service_Customer();
	
				// check customer already added to quickbooks
				$checkCustomer = $db->pquery("select * from sugar_qbids where sugar_qbids.crmid = '?' and module = '?'",array($SEObj['billing_contact_id'], 'Contacts'));
				$countCustomer = $db->getRowCount($checkCustomer);
				if($countCustomer > 0)	{
					$getQbId = $db->fetchByAssoc($checkCustomer);
					$CustomerId = $getQbId['qbid'];
				}

				$mapfields = $this->getQBTigerMapping('Contacts');
				$getCustomerQuery = $db->pquery("select * from contacts where id = '?'",array($SEObj['billing_contact_id']));
				// if customer not added to quickbooks. add new customer
				if($countCustomer <= 0 || empty($countCustomer))	{
					$customerInfo = $db->fetchByAssoc($getCustomerQuery);
					$firstname = $customerInfo['first_name'];
					$lastname = $customerInfo['last_name'];
					if(empty($firstname) || $firstname == '')	{
						$CustomerName = $lastname;
					}
					else	{
						if($mapfields['displayname'] == 'last_name-first_name')	{
							$CustomerName  = $lastname." ".$firstname;
							$checkCusAgain = $firstname." ".$lastname;
						}
						else	{
							$CustomerName = $firstname." ".$lastname;
							$checkCusAgain = $lastname." ".$firstname;	
						}
					}

					$CS = new QuickBooks_IPP_Service_Customer();
                                        $query = "select * from Customer where GivenName = '$firstname' and FamilyName = '$lastname'";
                                        $CustomerInfo = $CS->query($response['context'], $response['realm'], $query);
					$CustomerInfo = $CustomerInfo[0];
					if(empty($CustomerInfo) || $CustomerInfo == '')	{
						$ContactObj = new Contact();
						$ContactObj->retrieve($SEObj['billing_contact_id']);
//						$CustomerObj = smackCustomerObj($ContactObj->fetched_row, 'add');
                        $QBId = $this->addCustomerToQB(array($ContactObj->fetched_row), $QuickbooksConfig, true);
						$CustomerId = $QBId;
					}
					else	{
						$CustomerId = $CustomerInfo->get('Id');
					}
				}
				$SE->setCustomerRef($CustomerId);

				$mapfields_mod = $this->getQBTigerMapping('Quotes');
				$SE->setDueDate($this->changeDateToQBFormat($SEObj[$mapfields_mod['duedate']]));
				$SE->setTxnDate($this->changeDateToQBFormat($SEObj[$mapfields_mod['invoicedate']]));
				// memo in qb
                                $SE->setPrivateNote($SEObj[$mapfields_mod['note']]);
                                // message displayed on invoice
                                $SE->setCustomerMemo($SEObj[$mapfields_mod['msg']]);

				$BillAddr = new QuickBooks_IPP_Object_BillAddr();
				$BillAddr->setLine1($SEObj[$mapfields_mod['billstreet']]);
				$BillAddr->setCity($SEObj[$mapfields_mod['billcity']]);
				$BillAddr->setCountrySubDivisionCode($SEObj[$mapfields_mod['billstate']]);
				$BillAddr->setCountry($SEObj[$mapfields_mod['billcoutry']]);
				$BillAddr->setPostalCode($SEObj[$mapfields['billpo']]);
				$SE->addBillAddr($BillAddr);

				$ShipAddr = new QuickBooks_IPP_Object_ShipAddr();
				$ShipAddr->setLine1($SEObj[$mapfields_mod['shipstreet']]);
				$ShipAddr->setCity($SEObj[$mapfields_mod['shipcity']]);
				$ShipAddr->setCountrySubDivisionCode($SEObj[$mapfields_mod['shipstate']]);
				$ShipAddr->setCountry($SEObj[$mapfields_mod['shipcoutry']]);
				$ShipAddr->setPostalCode($SEObj[$mapfields_mod['shippo']]);
				$SE->addShipAddr($ShipAddr);

				$Service = new QuickBooks_IPP_Service_Estimate();
				$response1 = $Service->add($response['context'], $response['realm'], $SE);

				$mode = 'create';
				if($response1)	{
			                $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($SEObj['id'], $response, 'Estimate'));	
                                        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
      					createLog('Suite => Quickbooks', 'create', 'pass', "",  $SEObj['id'], $response, 'Quotes');
					$this->deleteFailureQueue($SEObj['id']);
					$this->message['content'] .= $helper->generateMessageContent('AOS_Quotes', $SEObj['name'], $mode, $response1, $SEObj['id'], 'success', 'quickbooks');
                                }
                                else	{
                                        $errormsg = $Service->errorDetail();
                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
                                        $log_id = $this->addFailureQueue($SEObj['id'], 'Quotes', 'sugarcrm', 'create', $errormsg);
					$this->message['content'] .= $helper->generateMessageContent('AOS_Quotes', $SEObj['name'], $mode, $response1, $SEObj['id'], 'fail', 'quickbooks', $log_id);
                                }
			}
		}
	return $this->message;
}

/**
 * update quotes to quickbooks
 * @param array $SRObject
 * @param object $QuickbooksConfig
 **/
public function updateEstimateToQB($SRObject, $QuickbooksConfig)	{
	global $db;
	$mode = 'edit';
	$helper = new qbtigerHelper();
        $response = $this->init($QuickbooksConfig);

        if(!$response)
            $this->handle_error('unable_to_load_context');

        $country = $db->getOne('select country from sugar_qbtigersettings');
	foreach($SRObject as $SEObj)	{
		$QBId = $db->getOne("select qbid from sugar_qbids where module = 'Estimate' and crmid = '{$SEObj['id']}'");
		// if contact not related to quotes, dont update the record. contacts is mandatory to add/update estimate on quickbooks
		if(empty($SEObj['billing_contact_id']))	{
			$this->message['content'] = 'Contact not related to Invoice <br>';
		}
		else
		{
			$response = $this->init($QuickbooksConfig);

			if(!$response)
				$this->handle_error('unable_to_load_context');

				$Service = new QuickBooks_IPP_Service_Estimate();	

				$trim_QBId = substr($QBId ,1); $trim_QBId = substr($trim_QBId, 0, -1);
                                $trim_QBId = abs($trim_QBId);

				$query = "SELECT * FROM Estimate WHERE Id = '$trim_QBId' ";
                                $getSRQB = $Service->query($response['context'], $response['realm'], $query);
                                $getSRQB = $getSRQB[0];

				$SE = new QuickBooks_IPP_Object_Estimate();
				$SE->setSyncToken($getSRQB->getSyncToken());
				$SE->setId($QBId);

				$Products = null;
                                // get related products using quote id
                                $getProductObj = $db->pquery("select * from aos_products_quotes where parent_id = '?'", array($SEObj['id']));
                                if($getProductObj)      {
                                        while($fetchProduct = $db->fetchByAssoc($getProductObj))
                                                $Products[] = $fetchProduct;

                                }

				$linecount = 1;
                                $ItemService = new QuickBooks_IPP_Service_Item();
                                foreach($Products as $single_product)   {
                                        // check whether product already added to sugar
                                        $checkProduct = $db->pquery("select * from sugar_qbids where crmid = '?' and module = '?'",array($single_product['id'], 'Products'));
                                        $countProduct = $db->getRowCount($checkProduct);
                                        if($countProduct > 0)  {
                                                $QBItemId = $db->getOne("select qbid from sugar_qbids where crmid = '{$single_product['id']}' and module = 'Products'");
                                        }

                                        if($country == 'US'){
                                                $vat = $single_product['vat'];
                                                preg_match('/^\d+/', $vat, $result);
                                                $result1 = explode($result[0] . ' ', $vat);
                                                $vat = implode($result1);
                                                $vatId = $db->getOne("select qbid from sugar_qbtaxcodemapping where name = '$vat'");
                                                $txnTaxCodeRef = $vatId;
                                                $taxCodeRef = 'TAX';
                                                if(empty($txnTaxCodeRef))
                                                        $txnTaxCodeRef = 'TAX';
                                        }
                                        else{
                                                $vat = $single_product['vat'];
                                                $vatId = $db->getOne("select qbid from sugar_qbtaxcodemapping where name = '$vat'");
                                                $taxCodeRef = $vatId;
                                                $txnTaxCodeRef = $taxCodeRef;
                                                if(empty($taxCodeRef))
                                                {
                                                        $taxCodeRef = '{-13}';
                                                        $txnTaxCodeRef = '{-13}';
                                                }

                                        }

                                        // if product not added to quickbooks. add product
                                        if($countProduct <= 0 || empty($countProduct))  {
                                                // check whether product already present in that name
                                                $IS = new QuickBooks_IPP_Service_Term();
                                                $query = "select * from Item where Name = '{$single_product['name']}'";
                                                $itemInfo = $IS->query($response['context'],$response['realm'], $query);
                                                $itemInfo = $itemInfo[0];

                                                $mappingPro = $this->getQBTigerMapping('Products');
                                                if(empty($itemInfo))    {
                                                        // add product to quickbooks
                                                        $QBItemObj = new Quickbooks_Item();
                                                        $dup_productname = $single_product['id'];

                                                        // get product information from sugar products table to add new product to quickbooks
                                                        // dont get product information from variable - $single_product
                                                        // variable contains product information of quotes which may differ from original product information
                                                        $currentProductObj = array();
                                                        $currentProductObj = $db->pquery("select * from aos_products where id = '?'", array($single_product['product_id']));
                                                        $currentProductInfo = $db->fetchByAssoc($currentProductObj);
							if(empty($currentProductInfo))  {
//                                                                continue;
                                                                $currentProductObj = $db->pquery("select * from aos_products_quotes where name = '?' and parent_id = '?'", array($single_product['name'], $InvoiceObj['id']));
                                                                $currentProductInfo = $db->fetchByAssoc($currentProductObj);
                                                                $currentProductInfo['price'] = $currentProductInfo['product_unit_price'];
                                                                $currentProductInfo['qbincomeaccount_c'] = 'Sales';
                                                                $currentProductInfo['qbtype_c'] = 'Service';

                                                        }

                                                        $QBItemObj->ItemName = $currentProductInfo['name'];
                                                        $QBItemObj->ItemAmount = $currentProductInfo['price'];
                                                        $QBItemObj->ItemDesc = $currentProductInfo[$mappingPro['desc']];
                                                        $QBItemObj->VTItemId = $currentProductInfo['id'];
                                                        $QBItemObj->incomeAccount = $currentProductInfo[$mappingPro['qbincomeaccount_c']];
                                                        $QBItemObj->expenseAccount = $currentProductInfo[$mappingPro['qbexpenseaccount_c']];
                                                        $QBItemObj->purchaseCost = $currentProductInfo['cost'];
                                                        $QBItemObj->purchaseDescription = $currentProductInfo[$mappingPro['purchasedescription']];
                                                        $QBItemId = $this->addItemToQB(array($currentProductInfo), $QuickbooksConfig, true);

                                                        // add product information which are related to quotes
                                                        // done get information from $currentProductInfo
                                                        // variable contains original product information which may differ from related product information
                                                        $itemQuantity = $single_product['product_qty'];
							if(empty($itemQuantity))
                                                                $itemQuantity = 1;

                                                        $itemDis = $single_product['product_discount_amount'];
                                                        $itemAmount = ($single_product['product_unit_price'] - $itemDis) * $single_product['product_qty'];
                                                        $itemId = $QBItemId;
                                                        $itemPrice = $single_product['product_total_price'];
                                                        $itemDesc = $single_product['item_description'];
                                                }
                                                else    {
                                                        $itemId = $itemInfo->get('Id');
                                                        $itemDis = $single_product['product_discount_amount'];
                                                        $itemPrice = $single_product['product_unit_price'];
                                                        $itemQuantity = $single_product['product_qty'];
							if(empty($itemQuantity))
                                                                $itemQuantity = 1;

                                                        $itemDesc = $single_product['item_description'];
                                                        $itemAmount = ($itemPrice - $itemDis) * $itemQuantity;

                                                        // add entry to qbids table
                                                        $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')", array($single_product['id'], $itemId, 'Products'));
                                                }
                                        }
					else    {
                                                $itemId = $QBItemId;
                                                $itemDis = $single_product['product_discount_amount'];
                                                $itemPrice = $single_product['product_unit_price'];
                                                $itemQuantity = $single_product['product_qty'];
						if(empty($itemQuantity))
                                                	$itemQuantity = 1;

                                                $itemDesc = $single_product['item_description'];
                                                $itemAmount = ($itemPrice - $itemDis) * $itemQuantity;
                                        }

					$Line = new QuickBooks_IPP_Object_Line();
                                        $Line->setDescription($itemDesc);
                                        $Line->setTaxable('true');
                                        $lineid = "{-".$linecount."}";
                                        $Line->setId($lineid);
                                        $Line->setAmount($itemAmount);
                                        $Line->setDetailType('SalesItemLineDetail');
                                        $Line->setLineNum($linecount);

                                        $SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
                                        $SalesItemLineDetail->setItemRef($itemId);
                                        $SalesItemLineDetail->setUnitPrice($itemPrice);
                                        $SalesItemLineDetail->setQty($itemQuantity);
                                        $SalesItemLineDetail->setTaxCodeRef($taxCodeRef);
                                        $Line->setSalesItemLineDetail($SalesItemLineDetail);

                                        $SE->addLine($Line);
                                        $linecount = $linecount + 1;
                                }

				$finalDiscount = $SEObj['discount_amount'];
                                $totalTax = $SEObj['tax_amount'];
                                $shippingAndHandlingCharge = $SEObj['shipping_amount'];
                                if(empty($shippingAndHandlingCharge))
                                        $shippingAndHandlingCharge = 0;

                                $Line = new QuickBooks_IPP_Object_Line();
                                $Line->setAmount($SEObj['subtotal_amount']);
                                $Line->setDetailType('SubTotalLineDetail');
                                $SE->addLine($Line);

                                $Line = new QuickBooks_IPP_Object_Line();
                                //$Line->setAmount($finalShippingAndHandlingCharge);
                                $Line->setAmount($shippingAndHandlingCharge);
                                $Line->setDetailType('SalesItemLineDetail');
                                $SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
                                $SalesItemLineDetail->setItemRef('SHIPPING_ITEM_ID');
                                if($country == 'GB'){
                                        $shippingTaxCode = $SEObj['shipping_tax'];
                                        $shippingTaxCodeRef = $db->getOne("select qbid from sugar_qbtaxcodemapping where value = '$shippingTaxCode'");
                                        $SalesItemLineDetail->setTaxCodeRef($shippingTaxCodeRef);
                                }
                                $Line->setSalesItemLineDetail($SalesItemLineDetail);
                                $SE->addLine($Line);

                                $Line = new QuickBooks_IPP_Object_Line();
                                $Line->setAmount($finalDiscount);
                                $Line->setDetailType('DiscountLineDetail');
                                $SalesItemLineDetail = new QuickBooks_IPP_Object_DiscountLineDetail();
                                // name - Discount's Given. value 50 is hardcoded. referred https://developer.intuit.com/apiexplorer?apiname=V3QBO
                                $SalesItemLineDetail->setDiscountAccountRef(50);
                                $Line->setDiscountLineDetail($SalesItemLineDetail);
                                $SE->addLine($Line);

				// check whether tax added on quickbooks. if so add tax amount using added tax id
                                $taxCode = $this->getTaxCodes($response['context'], $response['realm']);
                                if($taxCode)    {
                                        if($totalTax > 0)       {
                                                $TaxField = new QuickBooks_IPP_Object_TxnTaxDetail();
                                                $TaxField->setTxnTaxCodeRef($txnTaxCodeRef);
                                                $TaxField->setTotalTax($totalTax);
                                                $SE->addTxnTaxDetail($TaxField);
                                        }
                                }
                                $SE->setTotalAmt($SEObj['total_amount']);

                                // to retrieve customer information
                                $CustomerService = new QuickBooks_IPP_Service_Customer();

                                // check customer already added to quickbooks
                                $checkCustomer = $db->pquery("select * from sugar_qbids where sugar_qbids.crmid = '?' and module = '?'",array($SEObj['billing_contact_id'], 'Contacts'));
                                $countCustomer = $db->getRowCount($checkCustomer);
                                if($countCustomer > 0)  {
                                        $getQbId = $db->fetchByAssoc($checkCustomer);
                                        $CustomerId = $getQbId['qbid'];
                                }

                                $mapfields = $this->getQBTigerMapping('Contacts');
                                $getCustomerQuery = $db->pquery("select * from contacts where id = '?'",array($SEObj['billing_contact_id']));
                                // if customer not added to quickbooks. add new customer
                                if($countCustomer <= 0 || empty($countCustomer))        {
                                        $customerInfo = $db->fetchByAssoc($getCustomerQuery);
                                        $firstname = $customerInfo['first_name'];
                                        $lastname = $customerInfo['last_name'];
                                        if(empty($firstname) || $firstname == '')       {
                                                $CustomerName = $lastname;
                                        }
                                        else    {
                                                if($mapfields['displayname'] == 'last_name-first_name') {
                                                        $CustomerName  = $lastname." ".$firstname;
                                                        $checkCusAgain = $firstname." ".$lastname;
                                                }
                                                else    {
                                                        $CustomerName = $firstname." ".$lastname;
                                                        $checkCusAgain = $lastname." ".$firstname;
                                                }
                                        }

                                        $CS = new QuickBooks_IPP_Service_Customer();
                                        $query = "select * from Customer where GivenName = '$firstname' and FamilyName = '$lastname'";
                                        $CustomerInfo = $CS->query($response['context'], $response['realm'], $query);
                                        $CustomerInfo = $CustomerInfo[0];
                                        if(empty($CustomerInfo) || $CustomerInfo == '') {
                                                $ContactObj = new Contact();
                                                $ContactObj->retrieve($SEObj['billing_contact_id']);
//                                                $CustomerObj = smackCustomerObj($ContactObj->fetched_row, 'add');
                                                $QBId = $this->addCustomerToQB(array($ContactObj->fetched_row), $QuickbooksConfig, true);

                                                $CustomerId = $QBId;
                                        }
                                        else    {
                                                $CustomerId = $CustomerInfo->get('Id');
                                        }
                                }
                                $SE->setCustomerRef($CustomerId);

				$mapfields_mod = $this->getQBTigerMapping('Quotes');
                                $SE->setDueDate($this->changeDateToQBFormat($SEObj[$mapfields_mod['duedate']]));
                                $SE->setTxnDate($this->changeDateToQBFormat($SEObj[$mapfields_mod['invoicedate']]));
                                // memo in qb
                                $SE->setPrivateNote($SEObj[$mapfields_mod['note']]);
                                // message displayed on invoice
                                $SE->setCustomerMemo($SEObj[$mapfields_mod['msg']]);

                                $BillAddr = new QuickBooks_IPP_Object_BillAddr();
                                $BillAddr->setLine1($SEObj[$mapfields_mod['billstreet']]);
                                $BillAddr->setCity($SEObj[$mapfields_mod['billcity']]);
                                $BillAddr->setCountrySubDivisionCode($SEObj[$mapfields_mod['billstate']]);
                                $BillAddr->setCountry($SEObj[$mapfields_mod['billcoutry']]);
                                $BillAddr->setPostalCode($SEObj[$mapfields['billpo']]);
                                $SE->addBillAddr($BillAddr);

                                $ShipAddr = new QuickBooks_IPP_Object_ShipAddr();
                                $ShipAddr->setLine1($SEObj[$mapfields_mod['shipstreet']]);
                                $ShipAddr->setCity($SEObj[$mapfields_mod['shipcity']]);
                                $ShipAddr->setCountrySubDivisionCode($SEObj[$mapfields_mod['shipstate']]);
                                $ShipAddr->setCountry($SEObj[$mapfields_mod['shipcoutry']]);
                                $ShipAddr->setPostalCode($SEObj[$mapfields_mod['shippo']]);
                                $SE->addShipAddr($ShipAddr);

                                $Service = new QuickBooks_IPP_Service_Estimate();
				$response1 = $Service->update($response['context'], $response['realm'], $QBId, $SE);

				$mode = 'edit';
				if($response1)	{
                                        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
					createLog('Suite => Quickbooks', 'edit', 'pass', "",  $SEObj['id'], $QBId, 'Quotes');
					$this->deleteFailureQueue($SEObj['id']);
					$this->message['content'] .= $helper->generateMessageContent('AOS_Quotes', $SEObj['name'], $mode, $QBId, $SEObj['id'], 'success', 'quickbooks');
                                }
                                else
                                {
                                        $errormsg = $Service->errorDetail();
                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
                                        $log_id = $this->addFailureQueue($SEObj['id'], 'Quotes', 'sugarcrm', 'edit', $errormsg);
					$this->message['content'] .= $helper->generateMessageContent('AOS_Quotes', $SEObj['name'], $mode, $QBId, $SEObj['id'], 'fail', 'quickbooks', $log_id);
                                }
		}
	}
	return $this->message;
}

/**
 * add invoice to quickbooks
 * @param array $InvoiceObject
 * @param object $QuickbooksConfig
 **/
public function addInvoiceToQB($InvoiceObject, $QuickbooksConfig)
{
	global $db;
	$mode = 'create';
	$helper = new qbtigerHelper();
	foreach($InvoiceObject as $InvoiceObj)
	{
		// if no contact related to invoice, dont continue. contact is mandatory on quickbooks
		if(empty($InvoiceObj['billing_contact_id']))	{
			$this->message['content'] = 'Contact not related to Invoice <br>';
		}
		else
		{
			$response = $this->init($QuickbooksConfig);
			if(!$response)
				$this->handle_error('unable_to_load_context');	

				$Invoice = new QuickBooks_IPP_Object_Invoice();

				$Products = null;
                                // get related products using quote id
                                $getProductObj = $db->pquery("select * from aos_products_quotes where parent_id = '?'", array($InvoiceObj['id']));
                                if($getProductObj)      {
                                        while($fetchProduct = $db->fetchByAssoc($getProductObj))
                                                $Products[] = $fetchProduct;

                                }
	                        $country = $db->getOne('select country from sugar_qbtigersettings');

				$linecount = 1;
                                $ItemService = new QuickBooks_IPP_Service_Item();

                                foreach($Products as $single_product)   {
                                        // check whether product already added to sugar
                                        $checkProduct = $db->pquery("select * from sugar_qbids where crmid = '?' and module = '?'",array($single_product['id'], 'Products'));
                                        $countProduct = $db->getRowCount($checkProduct);
                                        if($countProduct > 0)  {
                                                $QBItemId = $db->getOne("select qbid from sugar_qbids where crmid = '{$single_product['id']}' and module = 'Products'");
                                        }
					if($country == 'US'){	
						$vat = $single_product['vat'];
						preg_match('/^\d+/', $vat, $result);
						$result1 = explode($result[0] . ' ', $vat);
						$vat = implode($result1);
						$vatId = $db->getOne("select qbid from sugar_qbtaxcodemapping where name = '$vat'");
						$txnTaxCodeRef = $vatId;
						$taxCodeRef = 'TAX';
						if(empty($txnTaxCodeRef))
	                                                $txnTaxCodeRef = 'TAX';
					}
					else{
						$vat = $single_product['vat'];
						$vatId = $db->getOne("select qbid from sugar_qbtaxcodemapping where name = '$vat'");
						$taxCodeRef = $vatId;
						$txnTaxCodeRef = $taxCodeRef;
						if(empty($taxCodeRef))
						{
							$taxCodeRef = '{-13}';
							$txnTaxCodeRef = '{-13}';
						}

					}
                                        // if product not added to quickbooks. add product
                                        if($countProduct <= 0 || empty($countProduct))  {
                                                // check whether product already present in that name
                                                $IS = new QuickBooks_IPP_Service_Term();
                                                $query = "select * from Item where Name = '{$single_product['name']}'";
                                                $itemInfo = $IS->query($response['context'],$response['realm'], $query);
                                                $itemInfo = $itemInfo[0];
                                                $mappingPro = $this->getQBTigerMapping('Products');
                                                if(empty($itemInfo))    {
                                                        // add product to quickbooks
                                                        $QBItemObj = new Quickbooks_Item();
                                                        $dup_productname = $single_product['id'];

                                                        // get product information from sugar products table to add new product to quickbooks
                                                        // dont get product information from variable - $single_product
                                                        // variable contains product information of quotes which may differ from original product information
                                                        $currentProductObj = array();
                                                        $currentProductObj = $db->pquery("select * from aos_products where id = '?'", array($single_product['product_id']));
                                                        $currentProductInfo = $db->fetchByAssoc($currentProductObj);
							// if empty, it may be service
							if(empty($currentProductInfo))	{
							//	continue;
								$currentProductObj = $db->pquery("select * from aos_products_quotes where name = '?' and parent_id = '?'", array($single_product['name'], $InvoiceObj['id']));
								$currentProductInfo = $db->fetchByAssoc($currentProductObj);
								$currentProductInfo['price'] = $currentProductInfo['product_unit_price'];
				                                $currentProductInfo['qbincomeaccount_c'] = 'Sales';
                                				$currentProductInfo['qbtype_c'] = 'Service';

							}
							$QBItemObj->ItemName = $currentProductInfo['name'];
                                                        $QBItemObj->ItemAmount = $currentProductInfo['price'];
                                                        $QBItemObj->ItemDesc = $currentProductInfo[$mappingPro['desc']];
                                                        $QBItemObj->VTItemId = $currentProductInfo['id'];
                                                        $QBItemObj->incomeAccount = $currentProductInfo[$mappingPro['qbincomeaccount_c']];
                                                        $QBItemObj->expenseAccount = $currentProductInfo[$mappingPro['qbexpenseaccount_c']];
                                                        $QBItemObj->purchaseCost = $currentProductInfo['cost'];
                                                        $QBItemObj->purchaseDescription = $currentProductInfo[$mappingPro['purchasedescription']];
//                                                        $currentProductInfo['qbincomeaccount_c'] = 'Sales';
                                                        $QBItemId = $this->addItemToQB(array($currentProductInfo), $QuickbooksConfig, true);
							//If Product failed means skip to add Invoice
							if($QBItemId == 'Failed')
								continue;
                                                        // add product information which are related to quotes
                                                        // done get information from $currentProductInfo
                                                        // variable contains original product information which may differ from related product information
                                                        $itemQuantity = $single_product['product_qty'];
							if(empty($itemQuantity))
								$itemQuantity = 1;

                                                        $itemDis = $single_product['product_discount_amount'];
                                                        if($currentProductInfo['qbtype_c'] == 'Service')
								$single_product['product_qty'] = 1;
	                                                $itemAmount = ($single_product['product_list_price']) * $single_product['product_qty'];
                                                        $itemId = $QBItemId;
                                                        $itemPrice = $single_product['product_list_price'];
                                                        $itemDesc = $single_product['item_description'];
                                                }
                                                else    {
                                                        $itemId = $itemInfo->get('Id');
                                                        $itemDis = $single_product['product_discount_amount'];
                                                        $itemPrice = $single_product['product_list_price'];
                                                        $itemQuantity = $single_product['product_qty'];
							if(empty($itemQuantity))
                                                                $itemQuantity = 1;

                                                        $itemDesc = $single_product['item_description'];
//							if($currentProductInfo['qbtype_c'] != 'Service')
	                                                        $itemAmount = ($itemPrice) * $itemQuantity;

                                                        // add entry to qbids table
                                                        $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')", array($single_product['id'], $itemId, 'Products'));
                                                }
                                        }
					else    {
                                                $itemId = $QBItemId;
                                                $itemDis = $single_product['product_discount_amount'];
                                                $itemPrice = $single_product['product_list_price'];
                                                $itemQuantity = $single_product['product_qty'];
						if(empty($itemQuantity))
                                                	$itemQuantity = 1;

                                                $itemDesc = $single_product['item_description'];
                                                $itemAmount = ($itemPrice) * $itemQuantity;
                                        }
                                        $Line = new QuickBooks_IPP_Object_Line();
                                        $Line->setDescription($itemDesc);
                                        $Line->setTaxable('true');
                                        $lineid = "{-".$linecount."}";
                                        $Line->setId($lineid);
                                        $Line->setAmount($itemAmount);
                                        $Line->setDetailType('SalesItemLineDetail');
                                        $Line->setLineNum($linecount);

                                        $SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
                                        $SalesItemLineDetail->setItemRef($itemId);
                                        $SalesItemLineDetail->setUnitPrice($itemPrice);
                                        $SalesItemLineDetail->setQty($itemQuantity);
                                        $SalesItemLineDetail->setTaxCodeRef($taxCodeRef);
                                        $Line->setSalesItemLineDetail($SalesItemLineDetail);

                                        $Invoice->addLine($Line);
                                        $linecount = $linecount + 1;
                                }

				$finalDiscount = str_replace('-', '', $InvoiceObj['discount_amount']);
                                $totalTax = $InvoiceObj['tax_amount'];
                                $shippingAndHandlingCharge = $InvoiceObj['shipping_amount'];
                                if(empty($shippingAndHandlingCharge))
                                        $shippingAndHandlingCharge = 0;

                                $Line = new QuickBooks_IPP_Object_Line();
                                $Line->setAmount($InvoiceObj['subtotal_amount']);
                                $Line->setDetailType('SubTotalLineDetail');
                                $Invoice->addLine($Line);

                                $Line = new QuickBooks_IPP_Object_Line();
                                //$Line->setAmount($finalShippingAndHandlingCharge);
                                $Line->setAmount($shippingAndHandlingCharge);
                                $Line->setDetailType('SalesItemLineDetail');
                                $SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
                                $SalesItemLineDetail->setItemRef('SHIPPING_ITEM_ID');
				if($country == 'GB'){
	                                $shippingTaxCode = $InvoiceObj['shipping_tax'];
        	                        $shippingTaxCodeRef = $db->getOne("select qbid from sugar_qbtaxcodemapping where value = '$shippingTaxCode'");
					$SalesItemLineDetail->setTaxCodeRef($shippingTaxCodeRef);
				}
                                $Line->setSalesItemLineDetail($SalesItemLineDetail);
                                $Invoice->addLine($Line);

                                $Line = new QuickBooks_IPP_Object_Line();
                                $Line->setAmount($finalDiscount);
                                $Line->setDetailType('DiscountLineDetail');
                                $SalesItemLineDetail = new QuickBooks_IPP_Object_DiscountLineDetail();
                                // name - Discount's Given. value 50 is hardcoded. referred https://developer.intuit.com/apiexplorer?apiname=V3QBO
                                if ($single_product['discount'] == 'Percentage') {
                                    $SalesItemLineDetail->setPercentBased(true);
                                    $SalesItemLineDetail->setDiscountAmount($InvoiceObj['discount_amount']);

                                }
                                else {
                                    $SalesItemLineDetail->setPercentBased(0);
                                    $SalesItemLineDetail->setDiscountAmount($InvoiceObj['discount_amount']);
                                }

                                $Line->setDiscountLineDetail($SalesItemLineDetail);
                                $Invoice->addLine($Line);

				// check whether tax added on quickbooks. if so add tax amount using added tax id
                                $taxCode = $this->getTaxCodes($response['context'], $response['realm']);
                                if($taxCode)    {
                                        if($totalTax > 0)       {
                                                $TaxField = new QuickBooks_IPP_Object_TxnTaxDetail();
                                                $TaxField->setTxnTaxCodeRef($txnTaxCodeRef);
                                                $TaxField->setTotalTax($totalTax);
                                                $Invoice->addTxnTaxDetail($TaxField);
                                        }
                                }
                                $Invoice->setTotalAmt($InvoiceObj['total_amount']);

                                // to retrieve customer information
                                $CustomerService = new QuickBooks_IPP_Service_Customer();

                                // check customer already added to quickbooks
                                $checkCustomer = $db->pquery("select * from sugar_qbids where sugar_qbids.crmid = '?' and module = '?'",array($InvoiceObj['billing_contact_id'], 'Contacts'));
                                $countCustomer = $db->getRowCount($checkCustomer);
                                if($countCustomer > 0)  {
                                        $getQbId = $db->fetchByAssoc($checkCustomer);
                                        $CustomerId = $getQbId['qbid'];
                                }

                                $mapfields = $this->getQBTigerMapping('Contacts');
                                $getCustomerQuery = $db->pquery("select * from contacts where id = '?'",array($InvoiceObj['billing_contact_id']));
                                // if customer not added to quickbooks. add new customer
				if($countCustomer <= 0 || empty($countCustomer))        {
                                        $customerInfo = $db->fetchByAssoc($getCustomerQuery);
                                        $firstname = $customerInfo['first_name'];
                                        $lastname = $customerInfo['last_name'];
                                        if(empty($firstname) || $firstname == '')       {
                                                $CustomerName = $lastname;
                                        }
                                        else    {
                                                if($mapfields['displayname'] == 'last_name-first_name') {
                                                        $CustomerName  = $lastname." ".$firstname;
                                                        $checkCusAgain = $firstname." ".$lastname;
                                                }
                                                else    {
                                                        $CustomerName = $firstname." ".$lastname;
                                                        $checkCusAgain = $lastname." ".$firstname;
                                                }
                                        }

                                        $CS = new QuickBooks_IPP_Service_Customer();
                                        $query = "select * from Customer where GivenName = '$firstname' and FamilyName = '$lastname'";
                                        $CustomerInfo = $CS->query($response['context'], $response['realm'], $query);
                                        $CustomerInfo = $CustomerInfo[0];
                                        if(empty($CustomerInfo) || $CustomerInfo == '') {
                                                $ContactObj = new Contact();
                                                $ContactObj->retrieve($InvoiceObj['billing_contact_id']); 
//                                                $CustomerObj = smackCustomerObj($ContactObj->fetched_row, 'add');
                                                $QBId = $this->addCustomerToQB(array($ContactObj->fetched_row), $QuickbooksConfig, true);

                                                $CustomerId = $QBId;
                                        }
                                        else    {
                                                $CustomerId = $CustomerInfo->get('Id');
                                        }
                                }
                                $Invoice->setCustomerRef($CustomerId);
					
				$mapfields_mod = $this->getQBTigerMapping('Invoice');
                                $Invoice->setDueDate($this->changeDateToQBFormat($InvoiceObj[$mapfields_mod['duedate']]));
                                $Invoice->setTxnDate($this->changeDateToQBFormat($InvoiceObj[$mapfields_mod['invoicedate']]));

                                // memo in qb
                                $Invoice->setPrivateNote($InvoiceObj[$mapfields_mod['note']]);
                                // message displayed on invoice
                                $Invoice->setCustomerMemo($InvoiceObj[$mapfields_mod['msg']]);
				$shipMethod = $InvoiceObj[$mapfields_mod['ship_via']];
				$Invoice->setShipMethodRef("{-$shipMethod}");
				$Invoice->setShipDate($InvoiceObj[$mapfields_mod['shipping_date']]);
				$Invoice->setTrackingNum($InvoiceObj[$mapfields_mod['tracking_no']]);
                                $BillAddr = new QuickBooks_IPP_Object_BillAddr();
                                $BillAddr->setLine1($InvoiceObj[$mapfields_mod['billstreet']]);
                                $BillAddr->setCity($InvoiceObj[$mapfields_mod['billcity']]);
                                $BillAddr->setCountrySubDivisionCode($InvoiceObj[$mapfields_mod['billstate']]);
                                $BillAddr->setCountry($InvoiceObj[$mapfields_mod['billcoutry']]);
                                $BillAddr->setPostalCode($InvoiceObj[$mapfields['billpo']]);
                                $Invoice->addBillAddr($BillAddr);

                                $ShipAddr = new QuickBooks_IPP_Object_ShipAddr();
                                $ShipAddr->setLine1($InvoiceObj[$mapfields_mod['shipstreet']]);
                                $ShipAddr->setCity($InvoiceObj[$mapfields_mod['shipcity']]);
                                $ShipAddr->setCountrySubDivisionCode($InvoiceObj[$mapfields_mod['shipstate']]);
                                $ShipAddr->setCountry($InvoiceObj[$mapfields_mod['shipcoutry']]);
                                $ShipAddr->setPostalCode($InvoiceObj[$mapfields_mod['shippo']]);
                                $Invoice->addShipAddr($ShipAddr);
				if($InvoiceObj['status'] == 'Paid')
					$Invoice->setDeposit($InvoiceObj['total_amount']);
				$Service = new QuickBooks_IPP_Service_Invoice();
				$response1 = $Service->add($response['context'], $response['realm'], $Invoice);
				$mode = 'create';
				if($response1)	{
                                        $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($InvoiceObj['id'], $response1, 'Invoice'));	
                                        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
					createLog('Suite => Quickbooks', 'create', 'pass', "",  $InvoiceObj['id'], $response1, 'Invoice');
					$this->deleteFailureQueue($InvoiceObj['id']);
                                        $smackHelper = new Quickbooks_vtigerHelper();
                                        $smackHelper->updateQBExtravalues('aos_invoices', $response1, $InvoiceObj['id'], $mode, 'id');
					$this->message['content'] .= $helper->generateMessageContent('AOS_Invoices', $InvoiceObj['name'], $mode, $response1, $InvoiceObj['id'], 'success', 'quickbooks');
                                }
                                else
                                {
                                        $errormsg = $Service->errorDetail();
                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
                                        $log_id = $this->addFailureQueue($InvoiceObj['id'], 'Invoice', 'sugarcrm', 'create', $errormsg);
					$this->message['content'] .= $helper->generateMessageContent('AOS_Invoices', $InvoiceObj['name'], $mode, $response1, $InvoiceObj['id'], 'fail', 'quickbooks', $log_id);
                                }
		}
	}
	return $this->message;
}

/**
 * change datetime to quickbooks time format
 * @param datetime $datetochange
 * @return datetime (QB date format)
 **/
public function changeDateToQBFormat($datetochange)
{
	if($datetochange) { $datetochange = $datetochange."-08:00"; }
	return $datetochange;
}


/**
 * update invoice to quickbooks
 * @param array $InvoiceObject
 * @param object $QuickbooksConfig
 **/
public function updateInvoiceToQB($InvoiceObject, $QuickbooksConfig)	{
	global $db;
	$mode = 'edit';
	$helper = new qbtigerHelper();
	foreach($InvoiceObject as $InvoiceObj)
	{
		$QBId = $db->getOne("select qbid from sugar_qbids where module = 'Invoice' and crmid = '{$InvoiceObj['id']}'");
		// if no contact related to invoice, dont add it. contact is mandatory in quickbooks invoice
		if(empty($InvoiceObj['billing_contact_id']))	{
			$this->message['content'] = 'Contact not related to Invoice <br>';
		}
		else
		{
			$response = $this->init($QuickbooksConfig);
                        $country = $db->getOne('select country from sugar_qbtigersettings');

			if(!$response)
				$this->handle_error('unable_to_load_context');

				$Service = new QuickBooks_IPP_Service_Invoice();	
                                $trim_QBId = substr($QBId ,1); $trim_QBId = substr($trim_QBId, 0, -1);
                                $trim_QBId = abs($trim_QBId);

                                $query = "SELECT * FROM Invoice WHERE Id = '$trim_QBId' ";
                                $getInvoiceQB = $Service->query($response['context'], $response['realm'], $query);
				$getInvoiceQB = $getInvoiceQB[0];

				$Invoice = new QuickBooks_IPP_Object_Invoice();
				$Invoice->setSyncToken($getInvoiceQB->getSyncToken());
				$Invoice->setId($QBId);

				$Products = null;
                                // get related products using quote id
                                $getProductObj = $db->pquery("select * from aos_products_quotes where parent_id = '?'", array($InvoiceObj['id']));
                                if($getProductObj)      {
                                        while($fetchProduct = $db->fetchByAssoc($getProductObj))
                                                $Products[] = $fetchProduct;

                                }

                                $linecount = 1;
                                $ItemService = new QuickBooks_IPP_Service_Item();
                                foreach($Products as $single_product)   {
                                        // check whether product already added to sugar
                                        $checkProduct = $db->pquery("select * from sugar_qbids where crmid = '?' and module = '?'",array($single_product['id'], 'Products'));
                                        $countProduct = $db->getRowCount($checkProduct);
                                        if($countProduct > 0)  {
                                                $QBItemId = $db->getOne("select qbid from sugar_qbids where crmid = '{$single_product['id']}' and module = 'Products'");
                                        }

                                        if($country == 'US'){
                                                $vat = $single_product['vat'];
                                                preg_match('/^\d+/', $vat, $result);
                                                $result1 = explode($result[0] . ' ', $vat);
                                                $vat = implode($result1);
                                                $vatId = $db->getOne("select qbid from sugar_qbtaxcodemapping where name = '$vat'");
                                                $txnTaxCodeRef = $vatId;
                                                $taxCodeRef = 'TAX';
                                                if(empty($txnTaxCodeRef))
                                                        $txnTaxCodeRef = 'TAX';
                                        }
                                        else{
                                                $vat = $single_product['vat'];
                                                $vatId = $db->getOne("select qbid from sugar_qbtaxcodemapping where name = '$vat'");
                                                $taxCodeRef = $vatId;
                                                $txnTaxCodeRef = $taxCodeRef;
                                                if(empty($taxCodeRef))
                                                {
                                                        $taxCodeRef = '{-13}';
                                                        $txnTaxCodeRef = '{-13}';
                                                }

                                        }


                                        // if product not added to quickbooks. add product
                                        if($countProduct <= 0 || empty($countProduct))  {
                                                // check whether product already present in that name
                                                $IS = new QuickBooks_IPP_Service_Term();
                                                $query = "select * from Item where Name = '{$single_product['name']}'";
                                                $itemInfo = $IS->query($response['context'],$response['realm'], $query);
                                                $itemInfo = $itemInfo[0];

                                                $mappingPro = $this->getQBTigerMapping('Products');
                                                if(empty($itemInfo))    {
                                                        // add product to quickbooks
                                                        $QBItemObj = new Quickbooks_Item();
                                                        $dup_productname = $single_product['id'];

                                                        // get product information from sugar products table to add new product to quickbooks
                                                        // dont get product information from variable - $single_product
                                                        // variable contains product information of quotes which may differ from original product information
							$currentProductObj = array();
                                                        $currentProductObj = $db->pquery("select * from aos_products where id = '?'", array($single_product['product_id']));
                                                        $currentProductInfo = $db->fetchByAssoc($currentProductObj);
							if(empty($currentProductInfo))  {
//                                                                continue;
                                                                $currentProductObj = $db->pquery("select * from aos_products_quotes where name = '?' and parent_id = '?'", array($single_product['name'], $InvoiceObj['id']));
                                                                $currentProductInfo = $db->fetchByAssoc($currentProductObj);
                                                                $currentProductInfo['price'] = $currentProductInfo['product_unit_price'];
                                                                $currentProductInfo['qbincomeaccount_c'] = 'Sales';
                                                                $currentProductInfo['qbtype_c'] = 'Service';

                                                        }

                                                        $QBItemObj->ItemName = $currentProductInfo['name'];
                                                        $QBItemObj->ItemAmount = $currentProductInfo['price'];
                                                        $QBItemObj->ItemDesc = $currentProductInfo[$mappingPro['desc']];
                                                        $QBItemObj->VTItemId = $currentProductInfo['id'];
                                                        $QBItemObj->incomeAccount = $currentProductInfo[$mappingPro['qbincomeaccount_c']];
                                                        $QBItemObj->expenseAccount = $currentProductInfo[$mappingPro['qbexpenseaccount_c']];
                                                        $QBItemObj->purchaseCost = $currentProductInfo['cost'];
                                                        $QBItemObj->purchaseDescription = $currentProductInfo[$mappingPro['purchasedescription']];
                                                        $QBItemId = $this->addItemToQB(array($currentProductInfo), $QuickbooksConfig, true);

                                                        // add product information which are related to quotes
                                                        // done get information from $currentProductInfo
                                                        // variable contains original product information which may differ from related product information
                                                        $itemQuantity = $single_product['product_qty'];
							if(empty($itemQuantity))
                                                                $itemQuantity = 1;

                                                        if($currentProductInfo['qbtype_c'] == 'Service')
                                                                $single_product['product_qty'] = 1;

                                                        $itemDis = $single_product['product_discount_amount'];
                                                        $itemAmount = ($single_product['product_list_price']) * $single_product['product_qty'];
                                                        $itemId = $QBItemId;
                                                        $itemPrice = $single_product['product_list_price'];
                                                        $itemDesc = $single_product['item_description'];
                                                }
                                                else    {
                                                        $itemId = $itemInfo->get('Id');
                                                        $itemDis = $single_product['product_discount_amount'];
                                                        $itemPrice = $single_product['product_list_price'];
                                                        $itemQuantity = $single_product['product_qty'];
							if(empty($itemQuantity))
                                                                $itemQuantity = 1;

                                                        $itemDesc = $single_product['item_description'];
                                                        $itemAmount = ($itemPrice) * $itemQuantity;

                                                        // add entry to qbids table
                                                        $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')", array($single_product['id'], $itemId, 'Products'));
                                                }
                                        }
					else    {
                                                $itemId = $QBItemId;
                                                $itemDis = $single_product['product_discount_amount'];
                                                $itemPrice = $single_product['product_list_price'];
                                                $itemQuantity = $single_product['product_qty'];
						if(empty($itemQuantity))
                                                	$itemQuantity = 1;

                                                $itemDesc = $single_product['item_description'];
                                                $itemAmount = ($itemPrice) * $itemQuantity;
                                        }

                                        $Line = new QuickBooks_IPP_Object_Line();
                                        $Line->setDescription($itemDesc);
                                        $Line->setTaxable('true');
                                        $lineid = "{-".$linecount."}";
                                        $Line->setId($lineid);
                                        $Line->setAmount($itemAmount);
                                        $Line->setDetailType('SalesItemLineDetail');
                                        $Line->setLineNum($linecount);

                                        $SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
                                        $SalesItemLineDetail->setItemRef($itemId);
                                        $SalesItemLineDetail->setUnitPrice($itemPrice);
                                        $SalesItemLineDetail->setQty($itemQuantity);
                                        $SalesItemLineDetail->setTaxCodeRef($taxCodeRef);
                                        $Line->setSalesItemLineDetail($SalesItemLineDetail);

                                        $Invoice->addLine($Line);
                                        $linecount = $linecount + 1;
                                }

                                $finalDiscount = str_replace('-', '', $InvoiceObj['discount_amount']);
                                $totalTax = $InvoiceObj['tax_amount'];
                                $shippingAndHandlingCharge = $InvoiceObj['shipping_amount'];
                                if(empty($shippingAndHandlingCharge))
                                        $shippingAndHandlingCharge = 0;

                                $Line = new QuickBooks_IPP_Object_Line();
                                $Line->setAmount($InvoiceObj['subtotal_amount']);
                                $Line->setDetailType('SubTotalLineDetail');
                                $Invoice->addLine($Line);

                                $Line = new QuickBooks_IPP_Object_Line();
                                //$Line->setAmount($finalShippingAndHandlingCharge);
                                $Line->setAmount($shippingAndHandlingCharge);
                                $Line->setDetailType('SalesItemLineDetail');
                                $SalesItemLineDetail = new QuickBooks_IPP_Object_SalesItemLineDetail();
                                $SalesItemLineDetail->setItemRef('SHIPPING_ITEM_ID');
                                if($country == 'GB'){
                                        $shippingTaxCode = $InvoiceObj['shipping_tax'];
                                        $shippingTaxCodeRef = $db->getOne("select qbid from sugar_qbtaxcodemapping where value = '$shippingTaxCode'");
                                        $SalesItemLineDetail->setTaxCodeRef($shippingTaxCodeRef);
                                }
                                $Line->setSalesItemLineDetail($SalesItemLineDetail);
                                $Invoice->addLine($Line);

                                $Line = new QuickBooks_IPP_Object_Line();
                                $Line->setAmount($finalDiscount);
                                $Line->setDetailType('DiscountLineDetail');
                                $SalesItemLineDetail = new QuickBooks_IPP_Object_DiscountLineDetail();
                                // name - Discount's Given. value 50 is hardcoded. referred https://developer.intuit.com/apiexplorer?apiname=V3QBO
                                if ($single_product['discount'] == 'Percentage') {
                                    $SalesItemLineDetail->setPercentBased(true);
//                                    $SalesItemLineDetail->setDiscountPercent($product['discount_percent' . $product_key]);
                                    $SalesItemLineDetail->setDiscountAmount($InvoiceObj['discount_amount']);

                                }
                                else {
                                    $SalesItemLineDetail->setPercentBased(0);
                                    $SalesItemLineDetail->setDiscountAmount($InvoiceObj['discount_amount']);
                                }

//                                if($module != 'Quotes')
//                                    $SalesItemLineDetail->setDiscountAccountRef(50); // Unique Id

                                $Line->setDiscountLineDetail($SalesItemLineDetail);
                                $Invoice->addLine($Line);


                                // check whether tax added on quickbooks. if so add tax amount using added tax id
                                $taxCode = $this->getTaxCodes($response['context'], $response['realm']);
                                if($taxCode)    {
                                        if($totalTax > 0)       {
                                                $TaxField = new QuickBooks_IPP_Object_TxnTaxDetail();
                                                $TaxField->setTxnTaxCodeRef($txnTaxCodeRef);
                                                $TaxField->setTotalTax($totalTax);
                                                $Invoice->addTxnTaxDetail($TaxField);
                                        }
                                }
                                $Invoice->setTotalAmt($InvoiceObj['total_amount']);

                                // to retrieve customer information
                                $CustomerService = new QuickBooks_IPP_Service_Customer();

                                // check customer already added to quickbooks
                                $checkCustomer = $db->pquery("select * from sugar_qbids where sugar_qbids.crmid = '?' and module = '?'",array($InvoiceObj['billing_contact_id'], 'Contacts'));
                                $countCustomer = $db->getRowCount($checkCustomer);
                                if($countCustomer > 0)  {
                                        $getQbId = $db->fetchByAssoc($checkCustomer);
                                        $CustomerId = $getQbId['qbid'];
                                }

                                $mapfields = $this->getQBTigerMapping('Contacts');
                                $getCustomerQuery = $db->pquery("select * from contacts where id = '?'",array($InvoiceObj['billing_contact_id']));
                                // if customer not added to quickbooks. add new customer
				if($countCustomer <= 0 || empty($countCustomer))        {
                                        $customerInfo = $db->fetchByAssoc($getCustomerQuery);
                                        $firstname = $customerInfo['first_name'];
                                        $lastname = $customerInfo['last_name'];
                                        if(empty($firstname) || $firstname == '')       {
                                                $CustomerName = $lastname;
                                        }
                                        else    {
                                                if($mapfields['displayname'] == 'last_name-first_name') {
                                                        $CustomerName  = $lastname." ".$firstname;
                                                        $checkCusAgain = $firstname." ".$lastname;
                                                }
                                                else    {
                                                        $CustomerName = $firstname." ".$lastname;
                                                        $checkCusAgain = $lastname." ".$firstname;
                                                }
                                        }

                                        $CS = new QuickBooks_IPP_Service_Customer();
                                        $query = "select * from Customer where GivenName = '$firstname' and FamilyName = '$lastname'";
                                        $CustomerInfo = $CS->query($response['context'], $response['realm'], $query);
                                        $CustomerInfo = $CustomerInfo[0];
                                        if(empty($CustomerInfo) || $CustomerInfo == '') {
                                                $ContactObj = new Contact();
                                                $ContactObj->retrieve($InvoiceObj['billing_contact_id']);
//                                                $CustomerObj = smackCustomerObj($ContactObj->fetched_row, 'add');
                                                $QBId = $this->addCustomerToQB(array($ContactObj->fetched_row), $QuickbooksConfig, true);
                                                $CustomerId = $QBId;
                                        }
                                        else    {
                                                $CustomerId = $CustomerInfo->get('Id');
                                        }
                                }
                                $Invoice->setCustomerRef($CustomerId);
					
				$mapfields_mod = $this->getQBTigerMapping('Invoice');
                                $Invoice->setDueDate($this->changeDateToQBFormat($InvoiceObj[$mapfields_mod['duedate']]));
                                $Invoice->setTxnDate($this->changeDateToQBFormat($InvoiceObj[$mapfields_mod['invoicedate']]));


                                // memo in qb
                                $Invoice->setPrivateNote($InvoiceObj[$mapfields_mod['note']]);
                                // message displayed on invoice
                                $Invoice->setCustomerMemo($InvoiceObj[$mapfields_mod['msg']]);

                                $BillAddr = new QuickBooks_IPP_Object_BillAddr();
                                $BillAddr->setLine1($InvoiceObj[$mapfields_mod['billstreet']]);
                                $BillAddr->setCity($InvoiceObj[$mapfields_mod['billcity']]);
                                $BillAddr->setCountrySubDivisionCode($InvoiceObj[$mapfields_mod['billstate']]);
                                $BillAddr->setCountry($InvoiceObj[$mapfields_mod['billcoutry']]);
                                $BillAddr->setPostalCode($InvoiceObj[$mapfields['billpo']]);
                                $Invoice->addBillAddr($BillAddr);

                                $ShipAddr = new QuickBooks_IPP_Object_ShipAddr();
                                $ShipAddr->setLine1($InvoiceObj[$mapfields_mod['shipstreet']]);
                                $ShipAddr->setCity($InvoiceObj[$mapfields_mod['shipcity']]);
                                $ShipAddr->setCountrySubDivisionCode($InvoiceObj[$mapfields_mod['shipstate']]);
                                $ShipAddr->setCountry($InvoiceObj[$mapfields_mod['shipcoutry']]);
                                $ShipAddr->setPostalCode($InvoiceObj[$mapfields_mod['shippo']]);
                                $Invoice->addShipAddr($ShipAddr);
                                if($InvoiceObj['status'] == 'Paid')
				{
					$balance = $getInvoiceQB->getBalance();
					if($balance > 0)	
					{
                                                $total_amount = $getInvoiceQB->getTotalAmt();
						if($balance == $InvoiceObj['total_amount'] || $total_amount == $InvoiceObj['total_amount'])
		                                        $Invoice->setDeposit($balance);
						else{
							$balance = $InvoiceObj['total_amount'] - $total_amount;	
                                                        $Invoice->setDeposit($balance);
						}	
							
					}
				}
				$Service = new QuickBooks_IPP_Service_Invoice();
				$response1 = $Service->update($response['context'], $response['realm'], $QBId, $Invoice);
				if($response1)	{
                                        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
					createLog('Suite => Quickbooks', 'edit', 'pass', "",  $InvoiceObj['id'], $QBId, 'Invoice');
					$smackHelper = new Quickbooks_vtigerHelper();
                                        $smackHelper->updateQBExtravalues('aos_invoices', $QBId, $InvoiceObj['id'], $mode, 'id');
					$this->deleteFailureQueue($InvoiceObj['id']);
					$this->message['content'] .= $helper->generateMessageContent('AOS_Invoices', $InvoiceObj['name'], $mode, $QBId, $InvoiceObj['id'], 'success', 'quickbooks');
                                }
                                else
                                {
                                        $errormsg = $Service->errorDetail();
                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
                                        $log_id = $this->addFailureQueue($InvoiceObj['id'], 'Invoice', 'sugarcrm', 'edit', $errormsg);
					$this->message['content'] .= $helper->generateMessageContent('AOS_Invoices', $InvoiceObj['name'], $mode, $QBId, $InvoiceObj['id'], 'fail', 'quickbooks', $log_id);
                                }
		}
	}
	return $this->message;
}

	/**
	 * get related product info
	 * @param interger $recordId
	 * @return array $finalDetails
	 */
	public function getRelatedProductsInfo($recordId) {
		$recordModel = Inventory_Record_Model::getInstanceById($recordId);
		$relatedProducts = $recordModel->getProducts();
		return $relatedProducts[1]['final_details'];
	}	

	/**
	 * check tax codes added in quickbooks. if so return 1st tax code 
	 * @param object $Context
	 * @param string $realm
 	 * @return interger $taxcodeid
	 */
	public function getTaxCodes($Context, $realm)
	{
		$id = false;
		$service = new QuickBooks_IPP_Service_TaxCode();
		$query = "select * from TaxCode where active = true";
		$taxCode = $service->query($Context, $realm, $query);
		if($taxCode)	{
			$QBId = $taxCode[0]->getId();
			$trim_QBId = substr($QBId ,1); $trim_QBId = substr($trim_QBId, 0, -1);
                        $id = abs($trim_QBId);
		}
		return $id;
	}

	/**
   	 *	This Function will add the Vendor to Quickbooks.
	 *	@param Vendor array
	 *	@param Quickbooks_GokuConfig Object
	 **/

	function addVendorToQB($Vendors, $QuickbooksConfig)
	{
		$this->message['success']['create'] = 0;
                $this->message['success']['edit'] = 0;
                $this->message['failure']['create'] = 0;

		$helper = new qbtigerHelper();

		$response = $this->init($QuickbooksConfig);

		if(!$response)
			$this->handle_error('unable_to_load_context');

			global $adb;
			$mode = 'create';
			foreach($Vendors as $single_vendor)
			{
				$VendorService = new QuickBooks_IPP_Service_Vendor();
				$VendorObj = vtVendorObj($single_vendor);
				$Vendor = new QuickBooks_IPP_Object_Vendor();

				$Vendor->setGivenName($VendorObj->GivenName);
				$Vendor->setFamilyName($VendorObj->FamilyName);
				$Vendor->setCompanyName($VendorObj->CompanyName);
				$Vendor->setDisplayName($VendorObj->Name);

				$Vendor->setPrimaryPhone($VendorObj->Phone);
				$Vendor->setMobile($VendorObj->Mobile);
				$Vendor->setFax($VendorObj->Fax);

				$Vendor->setWebAddr($VendorObj->WebSite);
				$Vendor->setPrimaryEmailAddr($VendorObj->Email);
				$Vendor->setBillAddr($VendorObj->BillAddress);
				$response1 = $VendorService->add($response['context'], $response['realm'], $Vendor);
				$mode = 'create';

				if($response1)
                                {
                                        $adb->pquery("insert into vtiger_qbids (crmid, qbid, module) values (?, ?, ?)",array($single_vendor['vendorid'], $response1, 'Vendors'));
					createLog('VTtoQB', 'create', 'pass', "",  $single_vendor['vendorid'], $response1, 'Vendors');
					$this->deleteFailureQueue($single_vendor['vendorid']);
			               	$this->message['success'][$mode] = $this->message['success'][$mode] + 1;
					$this->message['content'] .= $helper->generateMessageContent($mode, $response1, $single_vendor['vendorid'], 'success', 'quickbooks');
                                }
                                else
                                {
                                	$errormsg = $VendorService->errorDetail();
                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
                                        $log_id = $this->addFailureQueue($single_vendor['vendorid'], 'Vendors', 'vtigercrm', 'create', $errormsg);
					$this->message['content'] .= $helper->generateMessageContent($mode, $response1, $single_vendor['vendorid'], 'fail', 'quickbooks', $log_id);
                                }
			}
		return $this->message;
	}


        /**
         *      This Function will Update the Vendor to Quickbooks.
         *      @param Vendor Array
         *      @param Quickbooks_GokuConfig Object
         **/

        function updateVendorToQB($Vendors, $QuickbooksConfig)
        {
		$helper = new qbtigerHelper();

                $this->message['success']['edit'] = 0;
                $this->message['failure']['edit'] = 0;

                global $adb;

                $mode = 'edit';

		$response = $this->init($QuickbooksConfig);

		if(!$response)
			$this->handle_error('unable_to_load_context');

                        foreach($Vendors as $single_vendor)
                        {
				$getVendorQBId = $adb->pquery("select * from vtiger_qbids where module = ? and crmid = ?",array('Vendors', $single_vendor['vendorid']));
                                $QBId = $adb->query_result($getVendorQBId, 0, "qbid");
                                $VendorService = new QuickBooks_IPP_Service_Vendor();
				$trim_QBId = substr($QBId ,1); $trim_QBId = substr($trim_QBId, 0, -1);
				$trim_QBId = abs($trim_QBId);

				$query = "SELECT * FROM Vendor WHERE Id = '$trim_QBId' ";
				$getVendorQB = $VendorService->query($response['context'], $response['realm'], $query);
				$getVendorQB = $getVendorQB[0];

                                $VendorObj = vtVendorObj($single_vendor);
                                $Vendor = new QuickBooks_IPP_Object_Vendor();
				
				$Vendor->setGivenName($VendorObj->GivenName);
                                $Vendor->setFamilyName($VendorObj->FamilyName);
                                $Vendor->setCompanyName($VendorObj->CompanyName);
                                $Vendor->setDisplayName($VendorObj->Name);

                                $Vendor->setPrimaryPhone($VendorObj->Phone);
                                $Vendor->setMobile($VendorObj->Mobile);
                                $Vendor->setFax($VendorObj->Fax);

                                $Vendor->setWebAddr($VendorObj->WebSite);
                                $Vendor->setPrimaryEmailAddr($VendorObj->Email);
                                $Vendor->setBillAddr($VendorObj->Address);

				$Vendor->setId($QBId);
				$Vendor->setSyncToken($getVendorQB->get('SyncToken'));
				$response1 = $VendorService->update($response['context'], $response['realm'], $QBId, $Vendor);
				$mode = 'edit';

                                if($response1)
                                {
                                        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
					createLog('VTtoQB', 'edit', 'pass', "",  $single_vendor['vendorid'], $response1, 'Vendors');
					$this->deleteFailureQueue($single_vendor['vendorid']);
					$this->message['content'] .= $helper->generateMessageContent($mode, $QBId, $single_vendor['vendorid'], 'success', 'quickbooks');
                                }
                                else
                                {
                                        $errormsg = $VendorService->errorDetail();
                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
                                        $log_id = $this->addFailureQueue($single_vendor['vendorid'], 'Vendors', 'vtigercrm', 'edit', $errormsg);
					$this->message['content'] .= $helper->generateMessageContent($mode, $QBId, $single_vendor['vendorid'], 'fail', 'quickbooks', $log_id);
                                }
                       }
		return $this->message;
        }


	/**
   	 * function will add the Customer to Quickbooks.
	 * @param Quickbooks_Customer Object
	 * @param Quickbooks_GokuConfig Object
	 * @return array $message
	 */
	public function addCustomerToQB($Customers, $QuickbooksConfig, $return = false)	{
		$this->message['success']['create'] = 0;
                $this->message['success']['edit'] = 0;
                $this->message['failure']['create'] = 0;
		$mode = 'create';

		$response = $this->init($QuickbooksConfig);

		if(!$response)
			$this->handle_error('unable_to_load_context');

			if($Customers)	{
				global $db;
				$helper = new qbtigerHelper();
				foreach($Customers as $single_customer)	{
					$CustomerObj = smackCustomerObj($single_customer, 'add');
			        $CustomerService = new QuickBooks_IPP_Service_Customer();

			        $Customer = new QuickBooks_IPP_Object_Customer();
					$Customer->setGivenName($CustomerObj->GivenName);
                                        $Customer->setFamilyName($CustomerObj->FamilyName);

                                        $Customer->setShowAs($CustomerObj->ShowAs);
                                        $Customer->setFullyQualifiedName($CustomerObj->Name);
                                        $Customer->setDisplayName($CustomerObj->Name);
                                        $Customer->setCompanyName($CustomerObj->CompanyName);

                                        $Customer->setPrimaryEmailAddr($CustomerObj->Email);
                                        $Customer->setWebAddr($CustomerObj->WebSite);

                                        $Customer->setBillAddr($CustomerObj->billAddress);
                                        $Customer->setShipAddr($CustomerObj->shipAddress);

                                        $Customer->setPrimaryPhone($CustomerObj->Phone);
                                        $Customer->setMobile($CustomerObj->Mobile);
                                        $Customer->setFax($CustomerObj->Fax);

                                        $Customer->setNotes($CustomerObj->Notes);
                                        $Customer->setBalance($CustomerObj->Balance);
				        $response1 = $CustomerService->add($response['context'], $response['realm'], $Customer);

					if($response1)	{
	                                        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
        	                                $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($single_customer['id'], $response1, 'Contacts'));
						createLog('Suite => Quickbooks', $mode, 'pass', "",  $single_customer['id'], $response1,'Contacts');
						$this->deleteFailureQueue($single_customer['id']);
	                                        $smackHelper = new Quickbooks_vtigerHelper();
	                                        $smackHelper->updateQBExtravalues('contacts', $response1, $single_customer['id'], $mode, 'id');

						$this->message['content'] .= $helper->generateMessageContent('Contacts', $CustomerObj->Name, $mode, $response1, $single_customer['id'], 'success', 'quickbooks');
                        if($return)
                                return $response1;
                	                }
	                                else	{
						$errormsg = $CustomerService->errorDetail();
	                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
        	                                $log_id = $this->addFailureQueue($single_customer['id'], 'Contacts', 'sugarcrm', $mode, $errormsg);
						$this->message['content'] .= $helper->generateMessageContent('Contacts',$CustomerObj->Name, $mode, $response1, $single_customer['id'], 'fail', 'quickbooks', $log_id);
                	                }
				}
			}
		return $this->message;
	}

	/**
   	 * update customer to quickbooks
	 * @param array $Customers
	 * @param array $QuickbooksConfig
	 **/
	public function updateCustomerToQB($Customers, $QuickbooksConfig)	{
		$this->message['success']['edit'] = 0;
                $this->message['failure']['edit'] = 0;

		global $db;
		$mode = 'edit';
        $response = $this->init($QuickbooksConfig);

        if(!$response)
            $this->handle_error('unable_to_load_context');

        $this->message['success']['edit'] = 0;
        $this->message['failure']['edit'] = 0;


			if($Customers)	{
				$helper = new qbtigerHelper();
				foreach($Customers as $single_customer)	{
					$QBId = $db->getOne("select qbid from sugar_qbids where module = 'Contacts' and crmid = '{$single_customer['id']}'");
					$CustomerObj = smackCustomerObj($single_customer, 'update');
				        $CustomerService = new QuickBooks_IPP_Service_Customer();
					$trim_QBId = substr($QBId ,1); $trim_QBId = substr($trim_QBId, 0, -1);
					$trim_QBId = abs($trim_QBId);

					$query = "SELECT * FROM Customer WHERE Id = '$trim_QBId' and Active IN (true, false)";
					$getCustomerQB = $CustomerService->query($response['context'], $response['realm'], $query);

					if($getCustomerQB)
					{
						$getTime = $getCustomerQB[0]->get('MetaData');
						$modifiedTime = $getTime->get('LastUpdatedTime');
					}
				        $Customer = new QuickBooks_IPP_Object_Customer();

					$Customer->setId($QBId);
				        $Customer->setGivenName($CustomerObj->GivenName);
				        $Customer->setFamilyName($CustomerObj->FamilyName);

					$Customer->setShowAs($CustomerObj->ShowAs);
					$Customer->setFullyQualifiedName($CustomerObj->Name);
					$Customer->setDisplayName($CustomerObj->Name);
					$Customer->setCompanyName($CustomerObj->CompanyName);

					$Customer->setPrimaryEmailAddr($CustomerObj->Email);
					$Customer->setWebAddr($CustomerObj->WebSite);

					$Customer->setBillAddr($CustomerObj->billAddress);	
					$Customer->setShipAddr($CustomerObj->shipAddress);	

					$Customer->setPrimaryPhone($CustomerObj->Phone);
					$Customer->setMobile($CustomerObj->Mobile);
					$Customer->setFax($CustomerObj->Fax);

					$Customer->setNotes($CustomerObj->Notes);
					$Customer->setBalance($CustomerObj->Balance);

					$Customer->setSyncToken($getCustomerQB[0]->get('SyncToken'));
				        $response1 = $CustomerService->update($response['context'], $response['realm'], $QBId, $Customer);

					if($response1)	{
                                                $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
						createLog('Suite => Quickbooks', 'edit', 'pass', "",  $single_customer['id'], $response1, 'Contacts');
						$this->deleteFailureQueue($single_customer['id']);
                                                $smackHelper = new Quickbooks_vtigerHelper();
                                                $smackHelper->updateQBExtravalues('contacts', $QBId, $single_customer['id'], $mode, 'id');

                                                $this->message['content'] .= $helper->generateMessageContent('Contacts', $CustomerObj->Name, $mode, $QBId, $single_customer['id'], 'success', 'quickbooks');

                                        }
                                        else	{
                                                $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
						$errormsg = $CustomerService->errorDetail();
                                                $log_id = $this->addFailureQueue($single_customer['id'], 'Contacts', 'sugarcrm', 'edit', $errormsg);
						$this->message['content'] .= $helper->generateMessageContent('Contacts', $CustomerObj->Name, $mode, $QBId, $single_customer['id'], 'fail', 'quickbooks', $log_id);
                                        }
				}
			}
		return $this->message;
	}

    /**
     * Prepare Customer Object
     * @param $CustomerObj
     * @return QuickBooks_IPP_Object_Customer
     */
    public function setCustomerObject($CustomerObj)
    {
        $Customer = new QuickBooks_IPP_Object_Customer();

        $Customer->setGivenName($CustomerObj->GivenName);
        $Customer->setFamilyName($CustomerObj->FamilyName);
        $Customer->setMiddleName($CustomerObj->MiddleName);

        $Customer->setShowAs($CustomerObj->ShowAs);
        $Customer->setFullyQualifiedName($CustomerObj->Name);
        $Customer->setDisplayName($CustomerObj->Name);
        $Customer->setCompanyName($CustomerObj->CompanyName);

        $Customer->setPrimaryEmailAddr($CustomerObj->Email);
        $Customer->setWebAddr($CustomerObj->WebSite);

        $Customer->setBillAddr($CustomerObj->billAddress);
        $Customer->setShipAddr($CustomerObj->shipAddress);

        $Customer->setPrimaryPhone($CustomerObj->Phone);
        $Customer->setMobile($CustomerObj->Mobile);
        $Customer->setFax($CustomerObj->Fax);

        $Customer->setNotes($CustomerObj->Notes);
        $Customer->setBalance($CustomerObj->Balance);

        return $Customer;
    }
    /**
     * Return QuickBooks Id for given crm id
     * @param $module
     * @param $crm_id
     * @return bool|mixed|string
     * @throws Exception
     */
    public function checkQBID($module, $crm_id)
    {
        global $adb;
        $getUserID = $adb->pquery("select * from vtiger_qbids where module = ? and crmid = ?", array($module, $crm_id));
        $count = $adb->num_rows($getUserID);
        if($count > 0)
        {
            $id = $adb->query_result($getUserID, 0, 'qbid');
            $quickbooks_id = abs(substr($id, 1, -1));
            return $quickbooks_id;
        }
        return false;
    }

    /**
     * @param $config
     * @return bool|QuickBooks_IPP
     */
    public function init($config)
    {
        // Set up the IPP instance
        $IPP = new QuickBooks_IPP($config->dsn);

        // Get our OAuth credentials from the database
        $creds = $config->IntuitAnywhere->load($config->username, $config->tenant);

        // Tell the framework to load some data from the OAuth store
        $IPP->authMode(QuickBooks_IPP::AUTHMODE_OAUTH, $config->username, $creds);

        // Current realm
        $realm = $creds['qb_realm'];

        // Load the OAuth information from the database
        if ($Context = $IPP->context())
        {
            $IPP->version(QuickBooks_IPP_IDS::VERSION_3);

            // Set the DBID
            $IPP->dbid($Context, 'dbid');

            // Set the IPP flavor
            $IPP->flavor($creds['qb_flavor']);

            // Get the base URL if it's QBO
            if ($creds['qb_flavor'] == QuickBooks_IPP_IDS::FLAVOR_ONLINE)
            {
//                $IPP->baseURL($IPP->getBaseURL($Context, $realm));
            }

            $response = array( 'context' => $Context, 'realm' => $realm );

            return $response;
        }
        else
        {
            return false;
        }
    }

    /**
     * After save handler
     * @param $crm_id
     * @param $quickbooks_id
     * @param $mode
     * @param $module
     * @param $service
     * @return bool
     */
/*    public function after_save($crm_id, $quickbooks_id, $mode, $module, $service)
    {
        global $adb;
        $helper = new qbtigerHelper();
        $getEntityInfo = $adb->pquery('select tablename, entityidfield from vtiger_entityname where modulename = ?', array($module));
        $table_name = $adb->query_result($getEntityInfo, 0, 'tablename');
        $entity_id = $adb->query_result($getEntityInfo, 0, 'entityidfield');

                $error_details = $service->errorDetail();
        if($quickbooks_id && empty($error_details))
        {
            $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
            $this->updateQBExtravalues($table_name, $quickbooks_id, $crm_id, $mode, $entity_id);
            if($mode == 'create')
                $adb->pquery("insert into vtiger_qbids (crmid, qbid, module) values (?, ?, ?)",array($crm_id, $quickbooks_id, $module));

            createLog('VTtoQB', $mode, 'pass', "", $crm_id, $quickbooks_id, $module);
            $this->deleteFailureQueue($crm_id);
            $this->message['content'] .= $helper->generateMessageContent($mode, $quickbooks_id, $crm_id, 'success', 'quickbooks');
        }
        else
        {
            $error_detail = $service->errorDetail();
            $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
            $log_id = $this->addFailureQueue($quickbooks_id, $module, 'vtiger', $mode, $error_detail);
            $this->message['content'] .= $helper->generateMessageContent($mode, $quickbooks_id, $crm_id, 'fail', 'quickbooks', $log_id);
        }
        return true;
    }
*/
    /**
     * Update QB Block information of given Id
     * @param $tablename
     * @param $qbid
     * @param $id
     * @param $mode
     * @param $fieldid
     */
/*    public function updateQBExtravalues($tablename, $qbid, $id, $mode, $fieldid)
    {
        global $adb;
        if($mode == 'create')
            $adb->pquery("update $tablename set qbcreatedtime = ?, qbid = ? where $fieldid = ?",array($this->createdTime, $qbid, $id));
        else if($mode == 'edit')
            $adb->pquery("update $tablename set qbmodifiedtime = ?, qbid = ? where $fieldid = ?",array($this->modifiedTime, $qbid, $id));

    }
*/
    /**
     * Get Record from QuickBooks using Module and Record Id
     * @param $module
     * @param $quickbooks_id
     * @param $ippService
     * @param $response
     * @return mixed
     */
/*    public function getRecordFromQuickBooks($module, $quickbooks_id, $ippService, $response)
    {
        // Inventory module don't have 'Active' field. Patch Query should append only to non inventory modules
        if(in_array($module, $this->_non_inventory_modules))
            $patch_query = ' and Active IN (true, false)';

        $query = "SELECT * FROM {$module} WHERE Id = '{$quickbooks_id}' {$patch_query}";
        $record_info = $ippService->query($response['context'], $response['realm'], $query);
        return $record_info[0];
    }
*/
    /**
     * @param $error
     */
    public function handle_error($error)
    {
        // TODO need to handle the error
        die($error);
    }

}
