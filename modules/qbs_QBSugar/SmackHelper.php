<?php
/**
 *      Author: Rajkumar Mohan
 *      Date:   5-1-2013
 **/
class Quickbooks_vtigerHelper
{
	public $createdTime;
	public $modifiedTime;
	public $message = array();

	public function  __construct()
	{
		$this->groupName = 'Item Group';
		$this->qbShippingId = '{-SHIPPING_ITEM_ID}';
		$this->createdTime  = date("Y-m-d H:i:s"); 
		$this->modifiedTime = date("Y-m-d H:i:s");
		$this->message['content'] = null;
		$this->message['success']['create'] = 0; 
		$this->message['success']['edit'] = 0;
		$this->message['failure']['create'] = 0;	
		$this->message['failure']['edit'] = 0;
	}

	/**
	 * get the mapping field array
	 * @param string module
	 **/
	public function getQBTigerMapping($module)	{
		global $db;
		$mapfields = null;
		$getMapping = $db->getOne('select mapping from sugar_qbfieldmapping where module = "' .$module .'"');
                if(trim($getMapping) != '')      {
 	               $mapfields = unserialize(base64_decode($getMapping));
                }
		return $mapfields;
	}

	/**
	 * return sugar table name for provided module
	 * @param string $module
	 * @param string $tableName
	 */
	public function getSugarTableName($module)	{
		if($module == 'Contacts')
			$tableName = 'contacts';
		else if($module == 'Products')
			$tableName = 'aos_products';
		else if($module == 'Quotes' || $module == 'Estimate')
			$tableName = 'aos_quotes';
		else if($module == 'Invoice')
			$tableName = 'aos_invoices';


		return $tableName;
	}

	/**
	 * check wheather quickbooks id present in sugar_qbids table
	 * @param string module
	 * @param string QBId
	 * @return mixed boolean/char
	 **/
	public function checkQBID($module, $QBId, $service = false)	{
		global $db;
		$count = 0;
		$query = "select crmid from sugar_qbids where module = '$module' and qbid = '$QBId'";
		$getUserID = $db->query($query);
		$count = $db->getRowCount($getUserID);
		if($count <= 0)	{
			return false;
		}
		else	{
			$id = $db->getOne($query);
			if($service)
				$tableName = 'aos_products_quotes';
			else
	                        $tableName = $this->getSugarTableName($module);
			$query = "select id from $tableName where id = '$id' and deleted = 0";
			// check whether record deleted or not
			$check = $db->query($query);
			if($db->getRowCount($check) > 0)
				return $id;

			return false;
		}
	}

	/**
	 * add quotes to sugar
	 * @param object $ServiceObj
	 **/
	public function addQuotes($ServiceObj)	{
		global $db, $current_user;
                $userId = $current_user->id;
		if(empty($userId))
			$userId = getSugarAdminUserId();

                $mapfields = $this->getQBTigerMapping('Quotes');
                require_once('modules/AOS_Quotes/AOS_Quotes.php');
		if(!empty($ServiceObj) && $ServiceObj != 'No Estimate present in Quickbooks')	{
			foreach($ServiceObj as $Service)	{
				$QuickBooksConfigObj = new Quickbooks_GokuConfig();
				$QuickBooksHelper = new Quickbooks_GokuHelper();

				$QBId = $Service->get('Id');
                                $billAddr = $Service->get('BillAddr');

                                $shipAddr = $Service->get('ShipAddr');

                                $totalamount = $Service->get('TotalAmt');
                                $balance     = $Service->get('Balance');

                                $QBCustomerId = $Service->get('CustomerRef');
                                $SubAmount = $Service->get('SubTotalAmt');
				$totalAmount = $Service->get('TotalAmt');
                                $SubAmount = $totalAmount;
                                $txndate = $Service->get('TxnDate');
                                $docnumber = $Service->get('DocNumber');
                                $duedate = $Service->get('DueDate');
				$msg = $Service->get('CustomerMemo');
                                $note = $Service->get('PrivateNote');
				$taxAmountObj = $Service->get('TxnTaxDetail');
                                if($taxAmountObj)
                                        $taxAmount = $taxAmountObj->get('TotalTax');

                                $QBId = $Service->get('Id');
                                $VTInvoiceId = $this->checkQBID("Estimate", $QBId);
                                if($VTInvoiceId == false || empty($VTInvoiceId))        {
                                        $mode = "create";
                                        $id = "";
                                }
                                else    {
                                        $mode = "edit";
                                        $checkDeleted = new AOS_Quotes();
                                        $checkDeleted->retrieve($VTInvoiceId);
                                        if($checkDeleted->deleted != 0)  {
                                                $id = null;
                                                $mode = "create";
                                                $this->removeRecordFromQBIDS($VTInvoiceId);
                                        }
                                }

				$checkCustomer = $this->checkQBID("Contacts", $QBCustomerId);
                                // getting customer info
                                if(empty($checkCustomer) || $checkCustomer == false)    {
                                        $response = $QuickBooksHelper->getQBValuesById($QuickBooksConfigObj, 'Customer', $QBCustomerId);
                                        $CustomerId = $this->addContacts($response, true);
                                        $CustomerName = $response[0]->get('Name');
                                }
                                else    {
                                        $getCustomerNameQuery = $db->pquery("select first_name, last_name from contacts where id = '?' and deleted = 0", array($checkCustomer));
                                        $customerName = $db->fetchByAssoc($getCustomerNameQuery);
                                        $fname = $customerName['first_name'];
                                        $lname = $customerName['last_name'];

                                        $CustomerId   = $checkCustomer;
                                        $CustomerName = $fname." ".$lname;

                                        // getting group information
                                        $getGroupInfoQuery = $db->pquery("select id, name, parent_id from aos_line_item_groups where parent_id = '?' and deleted = 0", array($VTInvoiceId));
                                        while($getGroupInfo = $db->fetchByAssoc($getGroupInfoQuery))    {
                                                $groupName = $getGroupInfo['name'];
                                                if($groupName == $this->groupName)
                                                        $groupId = $getGroupInfo['id'];

                                        }
                                }
                                // getting customer info ends here

                                if($billAddr)	{
                                        $billstreet = $billAddr->get('Line1')."\n";
                                        $billstreet .= $billAddr->get('Line2')."\n";
                                        $billstreet .= $billAddr->get('Line3')."\n";
                                        $billcity = $billAddr->get('City');
                                        $billcountry = $billAddr->get('Country');
                                        $billstate   = $billAddr->get('CountrySubDivisionCode');
                                        $billzip = $billAddr->get('PostalCode');
                                }

				if($shipAddr)	{
                                        $shipstreet = $shipAddr->get('Line1')."\n";
                                        $shipstreet .= $shipAddr->get('Line2')."\n";
                                        $shipstreet .= $shipAddr->get('Line3')."\n";
                                        $shipcity = $shipAddr->get('City');
                                        $shipcountry = $shipAddr->get('Country');
                                        $shipstate   = $shipAddr->get('CountrySubDivisionCode');
                                        $shipzip = $shipAddr->get('PostalCode');
                                }

				// product line count
                                $LineCount = $Service->countLine();
                                $productCount = $discountAmount = $shippingAmount = 0;
                                $_POST = array();
                                for($i = 0; $i < $LineCount; $i ++)     {
                                        $Line = "";
                                        $j = $i + 1;
                                        $Line = $Service->getLine($i);
                                        if($Line)       {
                                                $itemInfo = array();
                                                // getting quickbooks item information
                                                $qbItemInfo = $Line->get('SalesItemLineDetail');
                                                if(empty($qbItemInfo))  {
                                                        // quickbooks provide total amount without tax in SalesItemLineDetail
                                                        // use this amount to show the total amount without tax
                                                        $detailType = null;
                                                        $detailType = $Line->get('DetailType');
                                                        if(!empty($detailType)) {
                                                                if($detailType == 'SubTotalLineDetail')
                                                                        $totalAmountWithOutTax = $Line->get('Amount');
                                                                else if($detailType == 'DiscountLineDetail')
                                                                        $discountAmount = $Line->get('Amount');

                                                        }
                                                        // should be discount/tax value. so stop the process here.
                                                        // if continue removed, error will occured below -> trying to get value from non object
                                                        continue;
                                                }
						else    {
                                                        // check whether shipping details
                                                        $itemRef = $qbItemInfo->get('ItemRef');
                                                        if(!empty($itemRef))    {
                                                                if($itemRef == $this->qbShippingId)     {
                                                                        // if shipping section, then get the amount and skip the below process
                                                                        $shippingAmount = $Line->get('Amount');
                                                                        continue;
                                                                }
                                                        }
                                                        else    {
                                                                continue;
                                                        }
                                                }

                                                $itemInfo['qbId'] = $qbItemInfo->get('ItemRef');
                                                $itemInfo['unitPrice'] = $qbItemInfo->get('UnitPrice');
                                                $itemInfo['qty'] = $qbItemInfo->get('Qty');
                                                $itemInfo['totalAmount'] = $Line->get('Amount');
                                                $itemInfo['description'] = $Line->get('Description');

						// getting iteminfo
                                                if(!empty($itemInfo['qbId']))   {
                                                        $getItem = $QuickBooksHelper->getQBValuesById($QuickBooksConfigObj, 'Item', $itemInfo['qbId']);
                                                        $itemName = $getItem[0]->get('Name');
          if($getItem[0]->get('Type') != 'Service'){
							// get sugar id using quickbooks item id
								$VTItemid = $this->checkQBID('Products', $itemInfo['qbId']);
							}
							else
								$VTItemid = $this->checkQBID('Products', $itemInfo['qbId'], true);



							if(empty($VTItemid) || $VTItemid == false){
                                                        if($getItem[0]->get('Type') != 'Service')
                                                                $VTItemid = $this->addProducts($getItem, true);
							else{
		//Add service from quickbooks
                if($getItem && $getItem != 'No Estimate present in Quickbooks')     {
                        foreach($getItem as $Item)      {
                                $ServiceQBId = $Item->get('Id');
                                $name = $Item->get('Name');
                                $price = $Item->get('UnitPrice');
                                $desc = $Item->get('Description');
                                $lastModifiedTime = $Item->get('MetaData')->get('LastUpdatedTime');
              
				$getUserIdQuery = "select crmid from sugar_qbids where module = 'Products' and qbid = '$ServiceQBId'";
                                $getUserID = $db->query($getUserIdQuery);
                                $contactCount = $db->getRowCount($getUserID);

                                if($id == false || empty($id))  {
                                        $service_mode = "create";
                                        $id = "";
                                }
                                else    {
                                        $service_mode = "edit";
                                        $checkDeleted = new AOS_Products_Quotes();
                                        $checkDeleted->retrieve($id);
                                        if($checkDeleted->deleted != 0)  {
                                                $id = null;
                                                $service_mode = "create";
                                                $this->removeRecordFromQBIDS($id);
                                        }
                                }

                                require_once("modules/AOS_Products_Quotes/AOS_Products_Quotes.php");
                                $focus = new AOS_Products_Quotes();
                                $focus->id = $id;
                                $focus->mode = $service_mode;
                                foreach($mapfields as $qbfield => $vtigerfield) {
                                        if( trim($vtigerfield) != "" && !empty($vtigerfield) )  {
                                                $focus->$vtigerfield = $data[$qbfield];
                                        }
                                }
                                $focus->name = $name;
                                $focus->product_unit_price = $price;
				$focus->product_total_price = $price;
				$focus->product_list_price = $price;
				$focus->product_id = 0;
                                $focus->cost = $price;
                                $focus->assigned_user_id = $userId;

                                $focus->save();
                                if($focus->id)  {
                                        $this->message['success'][$service_mode] = $this->message['success'][$service_mode] + 1;
                                        if($service_mode == 'create')
                                                $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($focus->id, $ServiceQBId, 'Products'));

//                                        $this->updateQBExtravalues('aos_products', $ServiceQBId, $focus->id, $mode, 'id');
                                        createLog('Quickbooks => Suite', $service_mode, 'pass', "", $focus->id , $ServiceQBId, 'Products');
                                        $this->message['content'] .= $this->generateMessageContent('AOS_Products', $name, $service_mode, $ServiceQBId, $focus->id, 'success', 'suite');
                                }

}
}

$VTItemid = 0;
}							
}
/*
                                                        // get sugar id using quickbooks item id
                                                        $VTItemid = $this->checkQBID('Products', $itemInfo['qbId']);
                                                        // if sugar id is null then add product to sugar
                                                        if(empty($VTItemid) || $VTItemid == false)
                                                                $VTItemid = $this->addProducts($getItem, true);
*/

                                                        $_POST['product_name'][] = $itemName;
                                                        $_POST['product_product_id'][] = $VTItemid;
                                                        $_POST['product_product_list_price'][] = $itemInfo['unitPrice'];
                                                        $_POST['product_product_unit_price'][] = $itemInfo['unitPrice'];
                                                        $_POST['product_product_qty'][] = $itemInfo['qty'];
                                                        $_POST['product_item_description'][] = $itemInfo['description'];
                                                        $_POST['product_product_total_price'][] = $itemInfo['totalAmount'];
                                                        $_POST['product_vat'][] = 0;
                                                        $_POST['product_vat_amt'][] = 0;
                                                        $_POST['product_group_number'][] = 0;
                                                        # item info ends here
                                                        $productCount = $productCount + 1;
                                                }
                                        }
                                }

				// get product id
                                $getProductIdQuery = "select * from aos_products_quotes where parent_id = '$VTInvoiceId' and group_id = '$groupId' and deleted = 0";
                                $getProductId = $db->query($getProductIdQuery);
                                while($productIds = $db->fetchByAssoc($getProductId))
                                        $_POST['product_id'][] = $productIds['id'];

                                // group name
                                $_POST['group_name'][0] = $this->groupName;
                                // group subtotal
                                $_POST['group_subtotal_amount'][0] = $totalAmountWithOutTax;
                                // group total
                                $_POST['group_total_amt'][0] = $totalAmountWithOutTax;
                                // add group tax here
                                $_POST['group_tax_amount'][0] = 0;
                                $_POST['group_id'][0] = $groupId;
                                $_POST['group_group_number'][0] = 0;
                                $_POST['group_deleted'][] = 0;

				$focus = new AOS_Quotes();
                                if(!empty($VTInvoiceId))
                                        $focus->retrieve($VTInvoiceId);

                                $focus->id = $VTInvoiceId;
                                $focus->mode = $mode;
                                $focus->description = $memo;
                                $focus->assigned_user_id = $userId;
                                // field mapping
                                foreach($mapfields as $qbfield => $vtigerfield) {
                                        if( trim($vtigerfield) != "" && !empty($vtigerfield) )  {
                                                $focus->$vtigerfield = $$qbfield;
                                        }
                                }

				if(!empty($CustomerId)) {
                                    $contactsBean = BeanFactory::getBean('Contacts', $CustomerId);
                                    $account_id = $contactsBean->account_id;
                                }

                                // default mapped fields starts here
                                $focus->name = $docnumber;
                                // contact related to invoice
                                $focus->billing_contact = $CustomerName;
                                $focus->billing_contact_id = $CustomerId;
				if($account_id) {
                                    $focus->billing_account_id = $account_id;
                                }
                                // total and subtotal
                                $focus->subtotal_amount = $totalAmountWithOutTax;
                                $focus->total_amt = $totalAmountWithOutTax;
                                // grand total
                                $focus->total_amount = $totalAmount;
                                $focus->lineItems = $productCount;

                                $focus->tax_amount = $taxAmount;
                                $focus->discount_amount = $discountAmount;
                                $focus->shipping_amount = $shippingAmount;
                                // default mapped fields ends here
				// removing groups in invoice item line
                                if(!empty($VTInvoiceId))        {
                                        $db->query("update aos_line_item_groups set deleted = 1 where parent_id = '{$VTInvoiceId}' and name != '{$this->groupName}'");
                                        if($groupId)
                                                $db->query("update aos_products_quotes set deleted = 1 where parent_id = '{$VTInvoiceId}' and group_id != '{$groupId}'");

                                }

                                $focus->save();
				if($focus->id)  {
                                        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
                                        $this->updateQBExtravalues('aos_invoices', $QBId, $focus->id, $mode, 'id');
                                        if($mode == 'create')
                                                $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($focus->id, $QBId, 'Estimate'));

                                        createLog('Quickbooks => Suite', $mode, 'pass', "", $focus->id , $QBId, 'Estimate');
                                        $this->message['content'] .= $this->generateMessageContent('AOS_Quotes', $docnumber, $mode, $QBId, $focus->id, 'success', 'suite');
                                }
                                else	{
                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
                                        $this->addFailureQueue($QBId, 'Estimate', 'quickbooks', $mode, $focus->id);
                                        $this->message['content'] .= $this->generateMessageContent('AOS_Quotes', $docnumber, $mode, $QBId, $focus->id, 'fail', 'suite');
                                }
                        }
                }
                return $this->message;
	}

	/**
	 * delete record from sugar_qbids table
	 * param string $id
	 **/
	public function removeRecordFromQBIDS($id)
	{
		global $db;
		$db->pquery("delete from sugar_qbids where crmid = '?'", array($id));
	}

	/**
	 * add invoice to sugar
	 * @param object $ServiceObj
	 **/
	public function addInvoice($ServiceObj,$type)	{
		global $db, $current_user;
		$userId = $current_user->id;
		if(empty($userId))
                        $userId = getSugarAdminUserId();

		$mapfields = $this->getQBTigerMapping('Invoice');
		require_once('modules/AOS_Invoices/AOS_Invoices.php');
		if(!empty($ServiceObj) && $ServiceObj != 'No Invoice present in Quickbooks')	{
			foreach($ServiceObj as $Service)	{
				$QuickBooksConfigObj = new Quickbooks_GokuConfig();
				$QuickBooksHelper = new Quickbooks_GokuHelper();
		
				# Company Info Ends here
				$billAddr = $Service->get('BillAddr');
				$shipAddr = $Service->get('ShipAddr');

				$totalamount = $Service->get('TotalAmt');
				$balance     = $Service->get('Balance');
 
				$QBCustomerId = $Service->get('CustomerRef');
				$SubAmount = $Service->get('SubTotalAmt');
				$totalAmount = $Service->get('TotalAmt');
				$SubAmount = $totalAmount;
				$txndate = $Service->get('TxnDate');
				$docnumber = $Service->get('DocNumber');
				$duedate = $Service->get('DueDate');
				$msg = $Service->get('CustomerMemo');
                                $note = $Service->get('PrivateNote');
				$taxAmountObj = $Service->get('TxnTaxDetail');
//			        $taxCodeRef = $Service->get('TaxCodeRef');
				$ship_via = $Service->get('ShipMethodRef');
				$shipping_date = $Service->get('ShipDate');
				$tracking_no = $Service->get('TrackingNum');
				if($taxAmountObj)
					$taxAmount = $taxAmountObj->get('TotalTax');

				$QBId = $Service->get('Id');
				$VTInvoiceId = $this->checkQBID("Invoice", $QBId);
				if($VTInvoiceId == false || empty($VTInvoiceId))	{
					$mode = "create";
					$id = "";
				}
				else	{
					$mode = "edit";
                                        $checkDeleted = new AOS_Invoices();
                                        $checkDeleted->retrieve($VTInvoiceId);
                                        if($checkDeleted->deleted != 0)  {
                                                $id = null;
                                                $mode = "create";
                                                $this->removeRecordFromQBIDS($VTInvoiceId);
                                        }
				}

				$checkCustomer = $this->checkQBID("Contacts", $QBCustomerId);
				// getting customer info
				if(empty($checkCustomer) || $checkCustomer == false)	{
					$response = $QuickBooksHelper->getQBValuesById($QuickBooksConfigObj, 'Customer', $QBCustomerId);
                                        $CustomerId = $this->addContacts($response, true);
					$CustomerName = $response[0]->get('Name');
				}
				else	{
					$getCustomerNameQuery = $db->pquery("select first_name, last_name from contacts where id = '?' and deleted = 0", array($checkCustomer));
					$customerName = $db->fetchByAssoc($getCustomerNameQuery);
					$fname = $customerName['first_name'];
					$lname = $customerName['last_name'];

					$CustomerId   = $checkCustomer;
					$CustomerName = $fname." ".$lname;

					// getting group information
					$getGroupInfoQuery = $db->pquery("select id, name, parent_id from aos_line_item_groups where parent_id = '?' and deleted = 0", array($VTInvoiceId));
					while($getGroupInfo = $db->fetchByAssoc($getGroupInfoQuery))	{
						$groupName = $getGroupInfo['name'];
						if($groupName == $this->groupName)
							$groupId = $getGroupInfo['id'];

					}
				}
				// getting customer info ends here

				if($billAddr)	{
					$billstreet = $billAddr->get('Line1')."\n";
					$billstreet .= $billAddr->get('Line2')."\n";
					$billstreet .= $billAddr->get('Line3')."\n";
					$billcity = $billAddr->get('City');
					$billcountry = $billAddr->get('Country');
					$billstate   = $billAddr->get('CountrySubDivisionCode');
					$billzip = $billAddr->get('PostalCode');
				}

				if($shipAddr)	{
					$shipstreet = $shipAddr->get('Line1')."\n";
					$shipstreet .= $shipAddr->get('Line2')."\n";
					$shipstreet .= $shipAddr->get('Line3')."\n";
					$shipcity = $shipAddr->get('City');
					$shipcountry = $shipAddr->get('Country');
					$shipstate   = $shipAddr->get('CountrySubDivisionCode');
					$shipzip = $shipAddr->get('PostalCode');
				}

				
				// product line count
				$LineCount = $Service->countLine();
				$productCount = $discountAmount = $shippingAmount = 0;
				$_POST = array();
				for($i = 0; $i < $LineCount; $i ++)	{
					$Line = "";
					$j = $i + 1;
					$Line = $Service->getLine($i);
					if($Line)	{
						$itemInfo = array();
						// getting quickbooks item information
						$qbItemInfo = $Line->get('SalesItemLineDetail');
						if(empty($qbItemInfo))	{
							// quickbooks provide total amount without tax in SalesItemLineDetail 
							// use this amount to show the total amount without tax
							$detailType = null;
							$detailType = $Line->get('DetailType');
							if(!empty($detailType))	{
								if($detailType == 'SubTotalLineDetail')
									$totalAmountWithOutTax = $Line->get('Amount');
								else if($detailType == 'DiscountLineDetail')
									$discountAmount = $Line->get('Amount');

							}
							// should be discount/tax value. so stop the process here. 
							// if continue removed, error will occured below -> trying to get value from non object
							continue;
						}
						else	{
							// check whether shipping details
							$itemRef = $qbItemInfo->get('ItemRef');
							if(!empty($itemRef))	{
								if($itemRef == $this->qbShippingId)	{
									// if shipping section, then get the amount and skip the below process
									$shippingAmount = $Line->get('Amount');
									continue;
								}
							}
							else	{
								continue;
							}
						}

						$itemInfo['qbId'] = $qbItemInfo->get('ItemRef');
						$itemInfo['unitPrice'] = $qbItemInfo->get('UnitPrice');
						$itemInfo['qty'] = $qbItemInfo->get('Qty');
						$itemInfo['totalAmount'] = $Line->get('Amount');
						$itemInfo['description'] = $Line->get('Description');

						// getting iteminfo
						if(!empty($itemInfo['qbId']))	{
							$getItem = $QuickBooksHelper->getQBValuesById($QuickBooksConfigObj, 'Item', $itemInfo['qbId']);
							$itemName = $getItem[0]->get('Name');

	                                                 if($getItem[0]->get('Type') != 'Service'){
							// get sugar id using quickbooks item id
								$VTItemid = $this->checkQBID('Products', $itemInfo['qbId']);
							}
							else
								$VTItemid = $this->checkQBID('Products', $itemInfo['qbId'], true);



							if(empty($VTItemid) || $VTItemid == false){
                                                        if($getItem[0]->get('Type') != 'Service')
                                                                $VTItemid = $this->addProducts($getItem, true);
							else{

                if($getItem && $getItem != 'No Item present in Quickbooks')     {
                        foreach($getItem as $Item)      {
                                $ServiceQBId = $Item->get('Id');
                                $name = $Item->get('Name');
                                $price = $Item->get('UnitPrice');
                                $desc = $Item->get('Description');
                                $lastModifiedTime = $Item->get('MetaData')->get('LastUpdatedTime');
              
				$getUserIdQuery = "select crmid from sugar_qbids where module = 'Products' and qbid = '$ServiceQBId'";
                                $getUserID = $db->query($getUserIdQuery);
                                $contactCount = $db->getRowCount($getUserID);

                                if($id == false || empty($id))  {
                                        $service_mode = "create";
                                        $id = "";
                                }
                                else    {
                                        $service_mode = "edit";
                                        $checkDeleted = new AOS_Products_Quotes();
                                        $checkDeleted->retrieve($id);
                                        if($checkDeleted->deleted != 0)  {
                                                $id = null;
                                                $service_mode = "create";
                                                $this->removeRecordFromQBIDS($id);
                                        }
                                }

                                require_once("modules/AOS_Products_Quotes/AOS_Products_Quotes.php");
                                $focus = new AOS_Products_Quotes();
                                $focus->id = $id;
                                $focus->mode = $service_mode;
                                foreach($mapfields as $qbfield => $vtigerfield) {
                                        if( trim($vtigerfield) != "" && !empty($vtigerfield) )  {
                                                $focus->$vtigerfield = $data[$qbfield];
                                        }
                                }
                                $focus->name = $name;
//                                $vat = $db->getOne("select value from sugar_qbtaxcodemapping where qbid = '$taxCodeRef'");
//                                $focus->vat = $vat;
//                                $focus->product_unit_price = $price;
				$focus->product_total_price = $price;
				$focus->product_list_price = $price;
				$focus->product_id = 0;
                                $focus->cost = $price;
                                $focus->assigned_user_id = $userId;

                                $focus->save();
                                if($focus->id)  {
                                        $this->message['success'][$service_mode] = $this->message['success'][$service_mode] + 1;
                                        if($service_mode == 'create')
                                                $db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($focus->id, $ServiceQBId, 'Products'));

//                                        $this->updateQBExtravalues('aos_products', $ServiceQBId, $focus->id, $mode, 'id');
                                        createLog('Quickbooks => Suite', $service_mode, 'pass', "", $focus->id , $ServiceQBId, 'Products');
                                        $this->message['content'] .= $this->generateMessageContent('AOS_Products', $name, $service_mode, $ServiceQBId, $focus->id, 'success', 'suite');
                                }

}
}

$VTItemid = 0;
}							
}

							$_POST['product_name'][] = $itemName;
							$_POST['product_product_id'][] = $VTItemid;
							$_POST['product_product_list_price'][] = $itemInfo['unitPrice'];
							$_POST['product_product_unit_price'][] = $itemInfo['unitPrice'];
							$_POST['product_product_qty'][] = $itemInfo['qty'];
							$_POST['product_item_description'][] = $itemInfo['description'];
							$_POST['product_product_total_price'][] = $itemInfo['totalAmount'];
							$_POST['product_vat'][] = 0;						
							$_POST['product_vat_amt'][] = 0;
							$_POST['product_group_number'][] = 0;
							# item info ends here
							$productCount = $productCount + 1;
						}
					}
				}

				// get product id
				$getProductIdQuery = "select * from aos_products_quotes where parent_id = '$VTInvoiceId' and group_id = '$groupId' and deleted = 0";
				$getProductId = $db->query($getProductIdQuery);
				while($productIds = $db->fetchByAssoc($getProductId))
					$_POST['product_id'][] = $productIds['id'];

				// group name
				$_POST['group_name'][0] = $this->groupName;	
				// group subtotal
				$_POST['group_subtotal_amount'][0] = $totalAmountWithOutTax;
				// group total
				$_POST['group_total_amt'][0] = $totalAmountWithOutTax;
				// add group tax here
				$_POST['group_tax_amount'][0] = 0;
				$_POST['group_id'][0] = $groupId;
				$_POST['group_group_number'][0] = 0;
				$_POST['group_deleted'][0] = 0;
				
				$focus = new AOS_Invoices();
				if(!empty($VTInvoiceId))
					$focus->retrieve($VTInvoiceId);

				$focus->id = $VTInvoiceId;
				$focus->mode = $mode;	
				$focus->description = $memo;
				if($balance == 0)
					$focus->status = 'Paid';
				elseif($balance > 0)
                                        $focus->status = 'Unpaid';

				$focus->assigned_user_id = $userId;
				// field mapping
				foreach($mapfields as $qbfield => $vtigerfield) {
	                                if( trim($vtigerfield) != "" && !empty($vtigerfield) )  {
        	                                $focus->$vtigerfield = $$qbfield;
                	                }
                        	}

				if(!empty($CustomerId)) {
                    			$contactsBean = BeanFactory::getBean('Contacts', $CustomerId);
                    			$account_id = $contactsBean->account_id;
		                }

				// default mapped fields starts here
				$focus->name = $docnumber;
				$focus->invoice_date = $txndate;
				// contact related to invoice
				$focus->billing_contact = $CustomerName;
				$focus->billing_contact_id = $CustomerId;
				$focus->billing_account_id = $account_id;
				// total and subtotal 
				$focus->subtotal_amount = $totalAmountWithOutTax;
				$focus->total_amt = $totalAmountWithOutTax;
				// grand total
				$focus->total_amount = $totalAmount;
				$focus->lineItems = $productCount;
				$focus->tax_amount = $taxAmount;
				$focus->discount_amount = $discountAmount;
				$focus->shipping_amount = $shippingAmount;
                		$focus->type_c = $type;
				// default mapped fields ends here
				// removing groups in invoice item line
				if(!empty($VTInvoiceId))	{
					$db->query("update aos_line_item_groups set deleted = 1 where parent_id = '{$VTInvoiceId}' and name != '{$this->groupName}'");
					if($groupId)
						$db->query("update aos_products_quotes set deleted = 1 where parent_id = '{$VTInvoiceId}' and group_id != '{$groupId}'");

				}
				$focus->save();
				if($focus->id)	{
                        	        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
	                                $this->updateQBExtravalues('aos_invoices', $QBId, $focus->id, $mode, 'id');
        	                        if($mode == 'create')	
					 	$db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($focus->id, $QBId, 'Invoice'));	

					createLog('Quickbooks => Suite', $mode, 'pass', "", $focus->id , $QBId, 'Invoice');
					$this->message['content'] .= $this->generateMessageContent('AOS_Invoices', $docnumber, $mode, $QBId, $focus->id, 'success', 'suite');
                	        }
                        	else
	                        {
        	                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
                	                $this->addFailureQueue($QBId, 'Invoice', 'quickbooks', $mode, $focus->id);
					$this->message['content'] .= $this->generateMessageContent('AOS_Invoices', $docnumber, $mode, $QBId, $focus->id, 'fail', 'suite');
                        	}
			}
		}
		return $this->message;
	}


	/**
	 * check record deleted for given id
	 * @param string $id
	 * @param string $module
	 * @return boolean $deleted
	 */
	public function checkRecordDeleted($id, $module)	{
		global $db;
		if($module == 'Contacts')
			$query = "select deleted from contact where id = '$id'";
		else if($module == 'Products')
			$query = "select deleted from aos_products where id = '$id'";
		else if($module == 'Quotes')
			$query = "select deleted from aos_quotes where id = '$id'";
		else if($module == 'Invoice')
			$query = "select deleted from aos_invoices where id = '$id'";

		$deleted = $db->getOne($query);
		return $deleted;
	}

	/**
	 * add products to sugarcrm
	 * @param object ItemObj
	 **/
	public function addProducts($ItemObj, $return = false)	{
		global $db, $current_user;
		$userId = $current_user->id;
		if(empty($userId))
                        $userId = getSugarAdminUserId();

		$mapfields = $this->getQBTigerMapping('Products');
		$qbconfig = new Quickbooks_GokuConfig();
		$quickBooksHelper = new Quickbooks_GokuHelper();
	
		if($ItemObj && $ItemObj != 'No Item present in Quickbooks')	{
			foreach($ItemObj as $Item)	{
				$QBId = $Item->get('Id');
				$name = $Item->get('Name');
				$price = $Item->get('UnitPrice');
				$desc = $Item->get('Description');
				$lastModifiedTime = $Item->get('MetaData')->get('LastUpdatedTime');
	
				$qbincomeaccountId = $Item->get('IncomeAccountRef');
				$qbincomeaccount = $quickBooksHelper->getAccountName($qbconfig, $qbincomeaccountId);
				$qbexpenseaccountId = $Item->get('ExpenseAccountRef');
				$qbexpenseaccount = $quickBooksHelper->getAccountName($qbconfig, $qbexpenseaccountId);
                                $qbassetaccountId = $Item->get('AssetAccountRef');
                                $qbassetaccount = $quickBooksHelper->getAccountName($qbconfig, $qbassetaccountId);
				$purchasecost = $Item->get('PurchaseCost');
				$purchasedescription = $Item->get('PurchaseDesc');
				$data = array();
				if($Item->get('Type') == 'Inventory' || $Item->get('Type') == 'Stock'){
			            $data['qbassetaccount_c'] = $qbassetaccount;
			            $data['qbqtyinhand_c'] = $Item->get('QtyOnHand');
			            $data['qbstartdate_c'] = $Item->get('InvStartDate');

				}
				elseif($Item->get('Type') == 'Service')
					continue;
				$data['qbincomeaccount_c'] = $qbincomeaccount;
				$data['qbexpenseaccount_c'] = $qbexpenseaccount;
				$id = $this->checkQBID("Products", $QBId);

				$getUserIdQuery = "select crmid from sugar_qbids where module = 'Products' and qbid = '$QBId'";
                                $getUserID = $db->query($getUserIdQuery);
                                $contactCount = $db->getRowCount($getUserID);

				if($id == false || empty($id))	{
					$mode = "create";
					$id = "";
				}
				else	{
					$mode = "edit";
                                        $checkDeleted = new AOS_Products();
                                        $checkDeleted->retrieve($id);
                                        if($checkDeleted->deleted != 0)  {
                                                $id = null;
                                                $mode = "create";
                                                $this->removeRecordFromQBIDS($VTInvoiceId);
                                        }
				}

				require_once("modules/AOS_Products/AOS_Products.php");
				$focus = new AOS_Products();
				$focus->id = $id;
				$focus->mode = $mode;
				$focus->qbtype_c = $Item->get('Type');
				foreach($mapfields as $qbfield => $vtigerfield) {
                                	if( trim($vtigerfield) != "" && !empty($vtigerfield) )  {
                                        	$focus->$vtigerfield = $data[$qbfield];
	                                }
        	                }
				$focus->name = $name;
				$focus->price = $price;
	                        $focus->cost = $purchasecost;
	                        $focus->assigned_user_id = $userId;

				$focus->save();

				if($focus->id)	{
                                        $this->message['success'][$mode] = $this->message['success'][$mode] + 1;
					if($mode == 'create')
						$db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($focus->id, $QBId, 'Products'));

					$this->updateQBExtravalues('aos_products', $QBId, $focus->id, $mode, 'id');
					createLog('Quickbooks => Suite', $mode, 'pass', "", $focus->id , $QBId, 'Products');
					$this->message['content'] .= $this->generateMessageContent('AOS_Products', $name, $mode, $QBId, $focus->id, 'success', 'suite');
                    if($return)
                            return $focus->id;
                                }
                                else	{
                                        $this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
					$this->addFailureQueue($QBId, 'Products', 'quickbooks', $mode, $focus->id);
					$this->message['content'] .= $this->generateMessageContent('AOS_Products', $name, $mode, $QBId, $focus->id, 'fail', 'suite');
                                }
				$allids[] = $QBId;
                        }
                        $this->updateLastInsertedIds('Item', $allids, $lastModifiedTime);
		}
		return $this->message;
	}

	/**
	 * add contacts to sugar
	 * @param object $ContactsObj
	 */
	public function addContacts($ContactsObj, $return = false)	{
		global $db, $current_user;
		$userId = $current_user->id;
		if(empty($userId))
                        $userId = getSugarAdminUserId();

		$mapfields = $this->getQBTigerMapping('Contacts');
		if($ContactsObj && $ContactsObj != "No Customer present in Quickbooks")
		{
			foreach($ContactsObj as $Contact)
			{
				$billaddress = ""; $shipaddress = ""; $emailaddress = ""; $getWebAddr = "";
				$phoneno = ""; $faxno = ""; $mobileno = ""; $companyname = "";$lastModifiedTime = "";

				$lastModifiedTime = $Contact->get('MetaData')->get('LastUpdatedTime');
				$billaddress    = $Contact->get('BillAddr');
				$shipaddress    = $Contact->get('ShipAddr');
				$emailaddress   = $Contact->get('PrimaryEmailAddr');
				if($emailaddress)
					$email = $emailaddress->get('Address');

				$QBId      	= $Contact->get('Id');
				$getWebAddr	= $Contact->get('WebAddr');
				if($getWebAddr)
					$website = $getWebAddr->get('URI');

				$getUserIdQuery = "select crmid from sugar_qbids where module = 'Contacts' and qbid = '$QBId'";
				$getUserID = $db->query($getUserIdQuery);
				$contactCount = $db->getRowCount($getUserID);

                                $id = $this->checkQBID("Contacts", $QBId);

				if($contactCount == 0)	{
					$mode = "create";
					$id = "";
				}
				else	{
					$mode = "edit";
					$checkDeleted = new Contact();
			                $checkDeleted->retrieve($id);
					if($checkDeleted->deleted != 0)  {
						$id = null;
						$mode = "create";
						$this->removeRecordFromQBIDS($id);
					}
				}

				if($billaddress) 
				{
					$billstreet = $billaddress->get('Line1')."\n";
					$billstreet .= $billaddress->get('Line2')."\n";
					$billstreet .= $billaddress->get('Line3')."\n";
					$billcity = $billaddress->get('City');
					$billcountry = $billaddress->get('Country');
					$billzip = $billaddress->get('PostalCode');
					$billstate = $billaddress->get('CountrySubDivisionCode');
				}

				if($shipaddress) 
				{
					$shipstreet = $shipaddress->get('Line1')."\n";
					$shipstreet .= $shipaddress->get('Line2')."\n";
					$shipstreet .= $shipaddress->get('Line3')."\n";
					$shipcity = $shipaddress->get('City');
					$shipcountry = $shipaddress->get('Country');
					$shipzip = $shipaddress->get('PostalCode');
					$shipstate = $shipaddress->get('CountrySubDivisionCode');
				}

				$companyname = $Contact->get('CompanyName');
		                if(!empty($companyname)){
					$account_id = $db->getOne("select account_id from accounts_contacts where contact_id = '$id'");
                                        $_companyname = mysql_real_escape_string($companyname);
                                        
                		        $organisation_id = $this->syncOrganisation($_companyname, $account_id);
		                }

				$phoneno = $Contact->get('PrimaryPhone');
				if($phoneno) { $phone = $phoneno->get('FreeFormNumber'); }

				$mobileno = $Contact->get('Mobile');
				if($mobileno) { $mobile = $mobileno->get('FreeFormNumber'); }

				$faxno = $Contact->get('Fax');
				if($faxno) { $fax = $faxno->get('FreeFormNumber'); }

				$name = $Contact->get('FullyQualifiedName');

				$firstname = $Contact->get('GivenName');
				$lastname  = $Contact->get('FamilyName');
				$active = $Contact->get('Active');

				if(empty($lastname))
				{
					if(!empty($name))	
					{
						$split_name = explode(" ", $name); 	
						if(count($split_name) == 1)
						{
							$lastname = $split_name[0];
						}
						elseif(count($split_name) == 2)
						{
							if(empty($firstname))	{ $firstname = $split_name[0];	}
							$lastname = $split_name[1];
						}
						elseif(count($split_name) == 3)
						{
							if(empty($firstname))  {  $firstname = $split_name[0]." ".$split_name[1]; }
							$lastname = $split_name[1];
						}
					}
				}

				$focus = null;
				$focus = new Contact();
				$focus->id = $id;
				$focus->mode = $mode;
				$focus->last_name = $lastname;
				$focus->first_name = $firstname;
				$focus->addtoqb = 1;
				foreach($mapfields as $qbfield => $vtigerfield) {
                                        if(trim($vtigerfield) != "" && !empty($vtigerfield))    {
                                                if($qbfield == 'email') {
                                                        $emailAddress = $$qbfield;
                                                }
                                                $focus->$vtigerfield = $$qbfield;
                                        }
                                }
				$focus->assigned_user_id = $userId;
				$focus->save(); 
				if($focus->id)	{
                                        $account_id = $db->getOne("select account_id from accounts_contacts where contact_id = '$focus->id'");
					if(empty($account_id)){
				               require_once("include/utils.php");
				               $custom_id = create_guid();
				               $datetime = date('Y-m-d h:i:s');
					       $db->pquery("insert into accounts_contacts (id, contact_id, account_id, date_modified)values ('?', '?', '?', '?')", array($custom_id, $focus->id, $organisation_id, $datetime));
					}

					// adding email address
                                        // inorder to add emailaddres, need unique crmid. thats why added this code after save
                                        $emailObj = new SugarEmailAddress();
                                        $emailObj->addAddress($emailAddress, true);
                                        $emailObj->save($focus->id, 'Contacts');

					$this->message['success'][$mode] = $this->message['success'][$mode] + 1;
					$this->updateQBExtravalues('contacts', $QBId, $focus->id, $mode, 'id');
					if($mode == 'create')
						$db->pquery("insert into sugar_qbids (crmid, qbid, module) values ('?', '?', '?')",array($focus->id, $QBId, 'Contacts'));

					createLog('Quickbooks => Suite', $mode, 'pass', "", $focus->id , $QBId, 'Contacts');
					$this->message['content'] .= $this->generateMessageContent('Contacts', $name,$mode, $QBId, $focus->id, 'success', 'suite');
                    if($return)
                            return $focus->id;
				}
				else	{
					$this->message['failure'][$mode] = $this->message['failure'][$mode] + 1;
					$this->addFailureQueue($QBId, $module, 'quickbooks', $mode, $focus->id);
					$this->message['content'] .= $this->generateMessageContent('Contacts',$name,$mode, $QBId, $focus->id, 'fail', 'suite');
				}
			}
		}
		return $this->message;
	}

	/**
 * Sync Organisation to suite
 * @param $name
 * @param $crm_id
 * @return $account_id integer
 */
public function syncOrganisation($name, $crm_id = null)
{
    global $current_user;
    $module = 'Accounts';
    $userId = $current_user->id;
    if(empty($userId)) {
        $userId = getSugarAdminUserId();
    }

  /*  if(!empty($crm_id)) {
        $accountsBean = BeanFactory::getBean($module, $crm_id);
        $accoutsBean->name = $name;
        $accoutsBean->save();
        return $accoutsBean->id;
    }
    else { */
        // Check whether Organisation name already exists
        $accoutsBean = BeanFactory::getBean($module);
        $response = $accoutsBean->get_list('name', "accounts.name = '{$name}'");
        if($response['row_count'] > 0) {
            return $response['list'][0]->id;
        }

        // Create a new account and return
        $accountsBean = BeanFactory::getBean($module);
        $accountsBean->name = $name;
        $accountsBean->assigned_user_id = $userId;
        $accountsBean->save();
        return $accountsBean->id;
   // }
}

	/**
	 * update last addedids 
	 * @param string $service
	 * @param array $allids
	 * @param datetime $modifiedtime
	 */
	public function updateLastInsertedIds($service, $allids, $modifiedtime)	{
		global $db; $ids = null;
                if(sizeof($allids) > 0 && !empty($allids))      {
			$query = "select lastsync from sugar_qbsyncdetails where module = '$service'";
                        $lastModifiedTime = $db->getOne($query);
                        // concat lastaddedids if time is same
                        if($lastModifiedTime == $modifiedtime)  {
				$lastAddedId = $db->getOne("select lastaddedids from sugar_qbsyncdetails where module = '$service'");
                                if(!empty($lastAddedId))        {
                                        $getpreviousids = unserialize(base64_decode($lastAddedId));
                                        $allids = array_merge($allids, $getpreviousids);
                                }
                        }
                        $ids = base64_encode(serialize($allids));
                        $check = $db->pquery("update sugar_qbsyncdetails set lastaddedids = '?' where module = '?'", array($ids, $service));
                }
	}

	/**
	 * generate message content for live result
	 * @param string $mode
	 * @param string $qbId
	 * @param integer $vtigerId
	 * @param string $source
	 * @return string $content
	 */
	public function generateMessageContent($module, $entity_name, $mode, $qbId, $vtigerId, $result, $source)	{
		if($result == 'success')	{
			$mode_text = 'Created';
			if($mode == 'edit')
				$mode_text = 'Updated';

			$content = "<div class = 'row sync-result-row'> Record Successfully {$mode_text} in {$source} - <a target='_blank' href = 'index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3D{$module}%26offset%3D1%26stamp%3D1465201600044559800%26return_module%3Dqbs_QBLog%26action%3DDetailView%26record%3D{$vtigerId}'> {$entity_name} ({$module}) </a> </div>";


		}
		else	{
			$mode_text = 'Create';
			if($mode == 'edit')
				$mode_text = 'Update';

			$content = "<div class = 'row sync-result-row'> Failed while trying to {$mode_text} record in {$source}. QuickBooks Id - {$qbId} </div>";


		}
		return $content;
	}

	public function addFailureQueue($qbid, $mod, $source, $mode, $vtid = "")	{
		global $db;
		$query = "select id, failcount from qbs_qbqueue where qborvtid = '$qbid' and source = '$source' and qbqueuemode = '$queuemode'";
		// check whether id already inserted into queue. If so update it
		$getQueue = $db->query($query);
		if($db->getRowCount($getQueue) >= 1)	{
			while($row = $db->fetchByAssoc($getQueue))   {
                                $queueid = $row['id'];
                                $failcount = $row['failcount'];
                        }
			$failcount = $failcount + 1;
			$db->pquery("update qbs_qbqueue set failcount = '?' where id = '?'",array($failcount, $queueid));
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
                        $QBQueue->qborvtid = $qbid;
                        $QBQueue->modulename = $mod;
                        $QBQueue->source = $source;
                        $QBQueue->qbqueuemode = $mode;
                        $QBQueue->message = $errormsg;
                        $QBQueue->assigned_user_id = $userId;
                        $QBQueue->save();
		}
		createLog('Quickbooks => Suite', $mode, 'fail', $errormsg, $vtid , $qbid, $mod);
	}

	function updateQBExtravalues($tablename, $qbid, $id, $mode, $fieldid)
	{
		global $db;
		if($mode == 'create')	{
			$db->pquery("update $tablename set qbcreatedtime_c = '?', qbid_c = '?' where id = '?'",array($this->createdTime, $qbid, $id));
		}
		else if($mode == 'edit')	{
			$db->pquery("update $tablename set qbmodifiedtime_c = '?', qbid_c = '?' where id = '?'",array($this->modifiedTime, $qbid, $id));
		}
	}
}

