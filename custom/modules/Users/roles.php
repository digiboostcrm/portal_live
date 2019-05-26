<?php

Class Rolesassign{
	
	function delete_role($bean, $event, $arguments){
		$bean->custom_user_type != 'Administrator';
		$bean->is_admin = 0;
		$bean->save();
	}
	function custom_user_type($bean, $event, $arguments){
				
		global $db;
		$user_id = $bean->id;
		$id = create_guid();
		$date = date("Y-m-d H:i:s");
		if(!$bean->fetched_row['custom_user_type'] || $bean->fetched_row['custom_user_type'] != $bean->custom_user_type){
			if($bean->custom_user_type == 'Administrator'){
				$bean->is_admin = 1;
			}
			else{	
			$bean->is_admin = 0;
				$role_id = $bean->custom_user_type;
				//$sel_sql = "SELECT id, role_id FROM acl_roles_users WHERE role_id = '$role_id' AND user_id = '$user_id' ";
				$sel_sql = "SELECT id, role_id FROM acl_roles_users WHERE user_id = '$user_id' AND deleted = 0 ";
				$result = $db->query($sel_sql);
				$data_result = $db->fetchByAssoc($result);
				$rel_id = $data_result['id'];

				if($result->num_rows == 0){
					
					$sql = "INSERT INTO acl_roles_users (id, role_id, user_id, date_modified, deleted) values ('$id' , '$role_id' , '$user_id' , '$date' , 0)";
					$db->query($sql);
					$bean->is_admin = 0;
				}
				else if($result->num_rows != 0 && $data_result['role_id'] != $role_id){
					$upSQL = "UPDATE acl_roles_users SET role_id = '$role_id' WHERE id = '$rel_id'";
					$db->query($upSQL);
					
				}
			}
			
		}	
			
		if(!$bean->fetched_row['is_admin'] || $bean->fetched_row['is_admin'] != $bean->is_admin){
			//
		}
		
	}
	
}

?>