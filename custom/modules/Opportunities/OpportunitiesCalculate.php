<?php
Class OpportunitiesCalculate{


	function calculateLogic($bean, $event, $arguments){

		$sum = $bean->fetched_row['website_design_amount_c'] + $bean->fetched_row['social_engagement_c'] + $bean->fetched_row['email_mrr_c'] + $bean->fetched_row['hosting_mrr_c'];

		$bean->fetched_row['amount'] = $sum;
		$sumValue = $bean ->website_design_amount_c + $bean ->social_engagement_c + $bean ->email_mrr_c + $bean ->hosting_mrr_c;
		
		$bean->amount_usdollar = $sumValue;
		$bean->amount = $sumValue;

	}
	/*
	function accountCreate($bean, $event, $arguments){
				
		if(!isset($bean->fetched_row['id']) && $bean->lead_account_name != ''){
	
			$beanAccount = BeanFactory::newBean('Accounts');
			$beanAccount->name = $bean->lead_account_name;
			$beanAccount->type = $bean->account_type;
			$beanAccount->email1 = $bean->account_email;
			$beanAccount->phone_office = $bean->account_phone;
			$beanAccount->industry = $bean->account_industary;
			$beanAccount->description = $bean->account_description;
			$beanAccount->save();
			$bean->account_id = $beanAccount->id;
			$bean->account_name = $beanAccount->name;
		
		}
		if($bean->custom_lead_id){		
			$beanLead = BeanFactory::getBean('Leads',$bean->custom_lead_id);
			$beanLead->status = 'Converted';
			$beanLead->save();
			
		}
		

	}
	*/
		function accountCreate($bean, $event, $arguments){
			global $db;
			global $timedate;
			$CurrenrDateTime = $timedate->getInstance()->nowDb(); //This will give database datetime ...
			$oppID = $bean->id;
			if($bean->account_id){
				$accID = $bean->account_id;
				
			}
			
		if(!isset($bean->fetched_row['id']) && $bean->lead_account_name != ''){
	
			$beanAccount = BeanFactory::newBean('Accounts');
			$beanAccount->name = $bean->lead_account_name;
			$beanAccount->type = $bean->account_type;
			$beanAccount->email1 = $bean->account_email;
			$beanAccount->phone_office = $bean->account_phone;
			$beanAccount->industry = $bean->account_industary;
			$beanAccount->description = $bean->account_description;
			$beanAccount->save();
			
			
			$accID = $beanAccount->id;
			$query = "INSERT INTO accounts_opportunities (`id`, `opportunity_id`, `account_id`, `date_modified`, `deleted`) VALUES  ('".create_guid()."','$oppID','$accID','$CurrenrDateTime',0)";
			$db->query($query);
			
		}
		if($bean->custom_lead_id){		
			$beanLead = BeanFactory::getBean('Leads',$bean->custom_lead_id);
			$beanLead->status = 'Converted';
			$beanLead->save();
			
		}
		if($bean->fetched_row['sales_stage'] != 'Closed Won' && $bean->sales_stage == 'Closed Won' && $bean->contact_id_c != '' && $accID != ''){
			$querySel = "SELECT id FROM accounts_contacts_1_c WHERE  accounts_contacts_1contacts_idb = '$bean->contact_id_c' AND accounts_contacts_1accounts_ida = '$accID'";
			$QueryResult = $db->query($querySel);
			$record = $db->fetchByAssoc($QueryResult);
			if(!$record){				
				$query = "INSERT INTO accounts_contacts_1_c (`id`, `accounts_contacts_1contacts_idb`, `accounts_contacts_1accounts_ida`, `date_modified`, `deleted`) VALUES  ('".create_guid()."','$bean->contact_id_c','$accID','$CurrenrDateTime',0)";
				$res = $db->query($query);
		
			}
			
		}
		

	}
}
