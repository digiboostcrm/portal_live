<?php

/**
 * The file used to add custom portal logic for portal.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
class custom_Portallogic {

    /**
     * Function : includeJSFile
     * include js file as per requiredment
     *
     * @param String $bean -- Bean name
     * @param String $event -- action when to perform
     * @param String $args -- parameters passed
     * 
     */
    function includeJSFile($bean, $event, $args=array()) {
        global $sugar_version,$current_user;
        // check sugar version and include js file if required
        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="custom/biz/js/jquery/jquery-1.11.0.min.js"></script>';
        }
        if ($_REQUEST['action'] == 'EditView') {
            echo "<script type='text/javascript' src='custom/modules/Contacts/js/portal_custom.js?v2'></script>";
        }
        $current_theme = $current_user->getPreference('user_theme');
        echo "<div><input type='hidden' id='current_theme' name='current_theme' value='{$current_theme}'></div>";
        echo "<script type='text/javascript' src='custom/biz/js/check_validation.js?v1'></script>";
        echo "<script type='text/javascript'>
                 $(document).ready(function(){
                 var beanID = $('input[name=record]').val();
                 var userNameVal = $('#username_c').val();
                    if(beanID != '' && userNameVal != ''){
                        $('#username_c').prop('readonly','readonly');
                        $('#password_c').prop('readonly','readonly');
                        $('#register_from_c').prop('readonly','readonly');
                        $('#Contacts0emailAddress0').attr('readonly','true');
                    }

                });
                </script>";
    }

    /**
     * Function : createWPUser
     * create wp user after successful saving of contact record
     *
     * @param String $bean -- Bean name
     * @param String $event -- action when to perform
     * @param String $args -- parameters passed
     * 
     */
    /*function createWPUser($bean, $event, $args) {

        require_once('custom/biz/function/default_portal_module.php');


        if ($bean->enable_portal_c == '1') {
            // Relate with user_accessible_group

            $user_accessible_modules = get_modules();
            foreach ($user_accessible_modules as $key => $value) {
                $user_accessible_modules[$key] = '^' . $value . '^';
            }
            $modules = implode(',', $user_accessible_modules);
            if (empty($bean->bc_user_group_contacts_name)) { // if user group is not provided
                //get Default record 
                $group_bean = new bc_user_group();
                $group_bean->retrieve_by_string_fields(array('name' => 'Default'));
                $group_id = $group_bean->id;
                if (empty($group_id)) { //if Default record doesn't exists create Default record
                    $group_bean->name = 'Default';
                    $group_bean->accessible_modules = $modules;
                    $group_bean->save();
                    $group_bean->retrieve_by_string_fields(array('name' => 'Default'));
                    $group_id = $group_bean->id;
                }
                $group_bean->retrieve($group_id);
                $group_bean->load_relationship('bc_user_group_contacts', 'Contact');
                $group_bean->bc_user_group_contacts->add($bean->id);
            }
        }
    }*/
    
    function createWPUser($bean, $event, $args) {
        require_once('custom/biz/function/default_portal_module.php');
        if($bean->enable_portal_c && ($bean->fetched_row['enable_portal_c'] != $bean->enable_portal_c)){
if(empty($bean->register_from_c)){
            $bean->register_from_c = 'CRM';
}
        }
        if ($bean->enable_portal_c == '1' && empty($bean->bc_user_group_contacts_name)) {
        $group_bean = new bc_user_group();
            $group_bean->retrieve_by_string_fields(array("is_portal_accessible_group" => 1));

            if (empty($group_bean->id)) {
                $group_bean->retrieve_by_string_fields(array('name' => 'Default'));

                if (empty($group_bean->id)) { //if Default record doesn't exists create Default record
                    $group_bean->name = 'Default';
                }
                $group_bean->is_portal_accessible_group = 1;
                    $group_bean->save();
                }

                $group_bean->load_relationship('bc_user_group_contacts', 'Contact');
                $group_bean->bc_user_group_contacts->add($bean->id);
            }
        }

    function uploadPhoto(&$bean, $event, $arguments) {
		global $sugar_config;
        require_once('modules/Contacts/Contact.php');
        require_once('include/utils.php');
        $GLOBALS['log']->debug("ContactPhoto->uploadPhoto");
        //we need to manually set the id if it is not already set
        //so that we can name the file appropriately
        if (empty($bean->id)) {
            $bean->id = create_guid();
            $bean->new_with_id = true;
}
        //this is the name of the field that is created in studio for the photo
        $field_name = 'contact_photo';
        if ($_REQUEST['remove_' . $field_name] == '1') {

            $old_file_name = $_REQUEST['old_' . $field_name];
            $old_photo = $sugar_config['cache_dir'] . 'images/' . $bean->id . '_' . $old_file_name;
            $GLOBALS['log']->debug("ContactPhoto->uploadPhoto: Deleting old photo: " . $old_photo);
            $bean->contact_photo = '';
            unlink($old_photo);
        }
        if (!empty($_FILES[$field_name]['name'])) {
            //if a previous photo has been uploaded then remove it now
            if (!empty($_REQUEST['old_' . $field_name])) {
                // create a non UTF-8 name encoding
                // 176 + 36 char guid = windows' maximum filename length
                $old_file_name = $_REQUEST['old_' . $field_name];
                $end = (strlen($old_file_name) > 176) ? 176 : strlen($old_file_name);
                $stored_file_name = substr($old_file_name, 0, $end);

                $old_photo = $sugar_config['cache_dir'] . 'images/' . $bean->id . '_' . $old_file_name;
                $GLOBALS['log']->debug("ContactPhoto->uploadPhoto: Deleting old photo: " . $old_photo);
                unlink($old_photo);
            }

            $file_name = $bean->id . '_' . $_FILES[$field_name]['name'];
            //save the file name to the database
            $bean->contact_photo = $_FILES[$field_name]['name'];

            if (!is_uploaded_file($_FILES[$field_name]['tmp_name'])) {
                die("ERROR: file did not upload");
                //return false;
            } elseif ($_FILES[$this->field_name]['size'] > $sugar_config['upload_maxsize']) {
                die("ERROR: uploaded file was too big: max filesize: {$sugar_config['upload_maxsize']}");
            }

            // create a non UTF-8 name encoding
            // 176 + 36 char guid = windows' maximum filename length
            $end = (strlen($file_name) > 176) ? 176 : strlen($file_name);
            $stored_file_name = substr($file_name, 0, $end);

            $destination = $sugar_config['cache_dir'] . 'images/' . $stored_file_name;

            if (!is_writable($sugar_config['upload_dir'])) {
                die("ERROR: cannot write to directory: {$sugar_config['upload_dir']} for uploads");
            }

            //$destination = clean_path($this->get_upload_path($bean_id));
            if (!move_uploaded_file($_FILES[$field_name]['tmp_name'], $destination)) {
                die("ERROR: can't move_uploaded_file to $destination. You should try making the directory writable by the webserver");
            }
        }
    }

    function sendMailtoUser(&$bean, $event, $arguments) {
		if((empty($bean->fetched_row['is_mail_sent']) && !empty($bean->account_id) && !empty($bean->enable_portal_c) && !empty($bean->username_c) && empty($bean->password_c)) || $_REQUEST['frm_convert']) {
			$bean->is_mail_sent = 1;
			$sPassword = $this->generatePassword();
			/*password convert into sh512*/
			$bean->password_c = hash('sha512', $sPassword);
			
			$objAdministration = new Administration();
			$objAdministration->retrieveSettings('PortalPlugin');
			$newregisteruserEmail = (!empty($objAdministration->settings['PortalPlugin_NewRegisterUserEmail'])) ? $objAdministration->settings['PortalPlugin_NewRegisterUserEmail'] : "0";
			
			// Send Email to newly cretaed contact
			$emailtemplateObj = new EmailTemplate();
			$emailtemplateObj->retrieve($newregisteruserEmail);
			if (!empty($emailtemplateObj->id)) {
				$macro_nv = array();
				$emailtemplateObj->parsed_entities = null;
				$emailSubjectName = $emailtemplateObj->subject;

				$email_module = 'Contacts';
				$recip_prefix = '$contact';

				// Email Template Body

				$emailtemplateObj->body_html = str_replace($search_prefix, $recip_prefix, $emailtemplateObj->body_html);
				/**
				 * Added by Mathi on 09/10/2018 - We can't sent encrypted password to enduser it is useless, we need to send original password
				 **/
				$emailtemplateObj->body_html = str_replace('$contact_password_c', $sPassword, $emailtemplateObj->body_html);
				$template_data = $emailtemplateObj->parse_email_template(array(
					"subject" => $emailSubjectName,
					"body_html" => $emailtemplateObj->body_html,
					"body" => $emailtemplateObj->body), $email_module, $bean, $macro_nv);
				$emailBody = $template_data["body_html"];
				$mailSubject = $template_data["subject"];
				$GLOBALS['log']->fatal('This is the $emailBody : --- ', print_r($emailBody, 1));
				$emailSubject = $mailSubject;

				$to_Email = $bean->email1;
				require_once 'custom/biz/function/default_portal_module.php';
				$sendMail = CustomSendEmailPortal($to_Email, $emailSubject, $emailBody, $bean->id, $email_module); // send username password email
				$GLOBALS['log']->fatal("Username and Password sent mail status : " . print_r($sendMail, 1));
				return $sendMail;
			} else {
				$GLOBALS['log']->fatal('EMAIL TEMPLATE NOT FOUND FOR Username and Password mail SENDING');
			}
		}
	}
	
	
	
	function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }

}
