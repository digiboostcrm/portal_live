<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class Cases_sent_email {

    function sentEmail(&$bean, $event, $arguments) {
		
		if(empty($bean->subject)){
			$bean->subject = $bean->name;
			$bean->save();
			return;
		}
		
		if(empty($bean->account_id)){
			return;
		}
		global $db, $current_user;
		require_once('custom/DigiboostMailer/DigiboostMailer.php');
		$oAccBean = BeanFactory::getBean('Accounts', $bean->account_id);
		$oDigiMailer = new \DigiboostMailer();
		
		if(empty($bean->fetched_row['id'])){
			//Seems ticket is newly added
			$sDql = "SELECT case_number FROM cases WHERE id = '{$bean->id}'";
			$aResult = $db->query($sDql);
			$aRow = $db->fetchByAssoc($aResult);
			$sSubject = "Digiboost - Ticket #{$aRow['case_number']}"; 
			
			if(empty($current_user->id)){
				//Seems ticket created from portal
				$sTemplate = 'tickets/new/customer.txt';
				$aUserTemplateVars = array(
					'customer_name' => $oAccBean->name,
					'case_number' => $aRow['case_number']
				);
			}else{
				//Seems ticket created from CRM
				$sTemplate = 'tickets/new/crm/customer.txt';
				$aUserTemplateVars = array(
					'customer_name' => $oAccBean->name,
					'case_number' => $aRow['case_number'],
					'subject' => $bean->subject,
					'category' => $bean->category,
					'priority' => $oDigiMailer->getTicketPriorityText($bean->priority),
					'description' => html_entity_decode($bean->description)
				);
			}
			//Send email to user
			$sSubject = "Digiboost - Ticket #{$aRow['case_number']}"; 
			$GLOBALS['log']->fatal("05 email sent ");
			$GLOBALS['log']->fatal($oAccBean->email1);
			$oDigiMailer->sendEmail($sTemplate, $aUserTemplateVars, $sSubject, array(
				$oAccBean->email1
			));
			
			/**
			 * If no one assigned to ticket then send email to curresponding group
			 * If the ticket is assigned to the user(not the once created by) then send the email to the user
			 **/
			$userID = $current_user->id;
			if(empty($bean->assigned_user_id) ||($userID != $bean->assigned_user_id)){
				if(empty($bean->assigned_user_id)){
					$aEmailConfig = $oDigiMailer->getTicketReceiverByCategory($bean->category);
				}else{
					$oUserBean = BeanFactory::getBean('Users', $bean->assigned_user_id);	
					$aEmailConfig = array(
						'name' => "{$oUserBean->first_name} {$oUserBean->last_name}",
						'email' => $oUserBean->email1
					);
				}
			$GLOBALS['log']->fatal("06 email sent ");
			$GLOBALS['log']->fatal($oAccBean->email1);
				$oDigiMailer->sendEmail('tickets/new/digiboost.txt', array(
					'name' => $aEmailConfig['name'],
					'customer_name' => $oAccBean->name,
					'case_number' => $aRow['case_number'],
					'subject' => $bean->subject,
					'category' => $bean->category,
					'id' => $bean->id,
					'priority' => $oDigiMailer->getTicketPriorityText($bean->priority),
					'description' => html_entity_decode($bean->description)
				), $sSubject, array(
					$aEmailConfig['email']
				));
			}
			return;
		}
		
		$sSubject = "Digiboost - Ticket #{$bean->case_number}"; 
		
		if(!empty($bean->assigned_user_id) && $bean->fetched_row['assigned_user_id'] != $bean->assigned_user_id){
			//Seems ticket is assigned to a CRM user
			$oUserBean = BeanFactory::getBean('Users', $bean->assigned_user_id);
			$GLOBALS['log']->fatal("05 email sent ");
			$GLOBALS['log']->fatal($oAccBean->email1);
			$oDigiMailer->sendEmail('tickets/assigned/digiboost.txt', array(
				'name' => "{$oUserBean->first_name} {$oUserBean->last_name}",
				'customer_name' => $oAccBean->name,
				'case_number' => $bean->case_number,
				'subject' => $bean->subject,
				'category' => $bean->category,
				'id' => $bean->id,
				'priority' => $oDigiMailer->getTicketPriorityText($bean->priority),
				'description' => html_entity_decode($bean->description)
			), $sSubject, array(
				$oAccBean->email1
			));
		}
		
		if($bean->fetched_row['status'] != $bean->status){
			//Seems the status is changed
			if($bean->status == 'Open_Assigned'){
				//Seems the ticket is assigned
				if(!empty($bean->assigned_user_id)){
					$oUserBean = BeanFactory::getBean('Users', $bean->assigned_user_id);
$GLOBALS['log']->fatal("01 email sent ");
$GLOBALS['log']->fatal($oAccBean->email1);
					$oDigiMailer->sendEmail('tickets/assigned/customer.txt', array(
						'customer_name' => $oAccBean->name,
						'case_number' => $bean->case_number,
						'name' => "{$oUserBean->first_name} {$oUserBean->last_name}",
					), $sSubject, array(
						$oAccBean->email1
					));
				}
				return;
			}elseif($bean->status == 'Closed_Closed'){
				if(!empty($current_user->id) && $current_user->id !== $bean->account_id){
					//Seems the ticket is closed
			$GLOBALS['log']->fatal("02 email sent ");
			$GLOBALS['log']->fatal($oAccBean->email1);
					$oDigiMailer->sendEmail('tickets/close/customer.txt', array(
						'customer_name' => $oAccBean->name,
						'case_number' => $bean->case_number,
						'resolution' => $bean->resolution
					), $sSubject, array(
						$oAccBean->email1
					));
				}
				return;
			}
		$GLOBALS['log']->fatal("03 email sent ");
		$GLOBALS['log']->fatal($oAccBean->email1);
			
			$oDigiMailer->sendEmail('tickets/status/customer.txt', array(
				'customer_name' => $oAccBean->name,
				'case_number' => $bean->case_number,
				'prev_status' => $this->getStatusText($bean->fetched_row['status']),
				'current_status' => $this->getStatusText($bean->status),
			), $sSubject, array(
				$oAccBean->email1
			));
			return;
		}		
		
    }
	
	private function getStatusText($status){
		if($status == 'Open_Require_Feedback'){
			return 'Require Feedback';
		}
		elseif($status == 'Open_New'){
			return 'New';
		}
		elseif($status == 'Open_Assigned'){
			return 'Assigned';
		}
		elseif($status == 'Closed_Closed'){
			return 'Closed';
		}
		elseif($status == 'Open_Pending Input'){
			return 'Pending Input';
		}
		elseif($status == 'Open_Feedback_Received'){
			return 'Feedback Received';
		}
		elseif($status == 'Open_Feedback_Pending'){
			return 'Pending Feedback';
		}
		return $status;
	}

}
