<?php

Class AccountsNumber_LogicHook{
	
	function accountNumber($bean, $event, $arguments){
		if(!$bean->fetched_row['id']){
			
			global $db;
			$sql = "SELECT MAX(auto_account_number) as number FROM accounts";
			$result = $db->query($sql);
			$row=  $db->fetchByAssoc($result); 
			
			$autoNumber = $row['number'];
			$autoNumber += 55705;
			$bean->account_number_c = $autoNumber;
			
			
		
		}
	}
	
	function autoEmailSent($bean, $event, $arguments){
		if($bean->fetched_row['on_boarding'] == 0 && $bean->on_boarding == 1){
			$html = 'Dear Client,<br/>
							I hope you are doing well,i just wanna inform to you 
						<br/>Please on boarding complete bill for <a href=\'http://digiboost.com/portal/index.php?module=Accounts&action=DetailView&record='.$bean->id.'\'>'.$bean->name.'</a>
						<br/>Best Regards, 
						<br/>Joshua Hatfield';
			require_once('include/SugarPHPMailer.php'); 
			$emailObj = new Email(); 
			$defaults = $emailObj->getSystemDefaultEmail(); 
			$mail = new SugarPHPMailer(); 
			$mail->setMailerForSystem(); 
			$mail->From = 'digiboostcrm@gmail.com'; 
			$mail->FromName = 'Digiboost Accounts Departments'; 
			$mail->Subject = "$bean->name Accounts" ;
			$mail->Body_html = from_html($html);
			$mail->Body = wordwrap($html,90000);
			$mail->isHTML(true); // set to true if content has html tags
			
			$mail->prepForOutbound(); 
			//$mail->AddAddress('digiboostcrm@gmail.com'); 
			$mail->AddAddress('billing@digiboost.com'); 
			
			@$mail->Send();

			
		}
	}
	
}