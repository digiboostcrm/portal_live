<?php

Class ActivityLog{
	
	function save_activity_data($bean, $event, $arguments){
		global $db, $current_user;
		$action = 'DELETE';
		if($event == 'before_save' ){
			if($bean->fetched_row['id']){
				$action = 'UPDATE';
			}else{
				$action = 'CREATE';
			}
		}

		if($bean->module_name == 'Accounts'){
			$aData = [
				'NAME' => $bean->name,
				'ACCOUNT_NUMBER' => $bean->account_number_c,
				'ACCOUNT_STATUS' => $bean->account_status_c,
				'ACCOUNT_MANAGER' => $bean->account_manager_c,
				'PHONE' => $bean->phone_office,
				'WEBSITE' => $bean->website
			];
			$module = 'ACCOUNTS';
			
		}elseif($bean->module_name == 'Contacts'){
			$module = 'CONTACTS';
			$aData = array(
				'NAME' => "$bean->first_name $bean->last_name",
				'TITLE' => $bean->title,
				'PHONE' => $bean->phone_work,
				'PHONE_MOBILE' => $bean->phone_mobile,
				'LEAD_SOURCE' => $bean->lead_source,
				'REPORTS_TO' => $bean->report_to_name
			);
		}
		$aData['RECORD_ID'] = $bean->id;
		$aData['EMAIL'] = $bean->email1;
		$data_encoded =  json_encode($aData);
		$ip = getenv('HTTP_CLIENT_IP')?:
				getenv('HTTP_X_FORWARDED_FOR')?:
				getenv('HTTP_X_FORWARDED')?:
				getenv('HTTP_FORWARDED_FOR')?:
				getenv('HTTP_FORWARDED')?:
				getenv('REMOTE_ADDR');
		$date = date("Y-m-d H:i:s");
		$sql = "INSERT INTO RECORD_ACTIVITY_LOG (USER_NAME , USER_ID , ACTIVITY_TYPE , MODULE_NAME , USER_IP , RECORD_ID , ACTIVITY_ON,  RECORD_DATA) VALUES ('$current_user->name', '$current_user->id' , '$action' , '$module' , '$ip' , '$bean->id' , '$date' , '$data_encoded')";
		$db->query($sql);

	}
	
}

?>


