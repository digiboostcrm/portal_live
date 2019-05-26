<?php
/**
 * The file used to store label of custom modules Portal Layout and Portal User Group. 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */


$app_list_strings['moduleList']['bc_user_group'] = 'Portal User Groups';

$app_list_strings['moduleListSingular']['bc_user_group'] = 'Portal User Group';
$app_list_strings['moduleList']['bc_case_updates'] = 'Case Updates';
global $sugar_version, $sugar_config, $sugar_flavor;
$re_sugar_versionCE = '/(6\.5\.[0-9])/';
if (in_array($sugar_flavor, array('CE')) && preg_match($re_sugar_versionCE, $sugar_version) && (!is_null($sugar_config['suitecrm_version']) || $sugar_config['suitecrm_version'] != '')) {
    $app_list_strings['moduleList']['p_payment'] = 'payment';
    $app_list_strings['moduleList']['p_Payment_transaction'] = 'Payment transaction';
    $app_list_strings['p_payment_transaction_type_dom'][''] = '';
    $app_list_strings['p_payment_transaction_type_dom']['Analyst'] = 'Analyst';
    $app_list_strings['p_payment_transaction_type_dom']['Competitor'] = 'Competitor';
    $app_list_strings['p_payment_transaction_type_dom']['Customer'] = 'Customer';
    $app_list_strings['p_payment_transaction_type_dom']['Integrator'] = 'Integrator';
    $app_list_strings['p_payment_transaction_type_dom']['Investor'] = 'Investor';
    $app_list_strings['p_payment_transaction_type_dom']['Partner'] = 'Partner';
    $app_list_strings['p_payment_transaction_type_dom']['Press'] = 'Press';
    $app_list_strings['p_payment_transaction_type_dom']['Prospect'] = 'Prospect';
    $app_list_strings['p_payment_transaction_type_dom']['Reseller'] = 'Reseller';
    $app_list_strings['p_payment_transaction_type_dom']['Other'] = 'Other';
    $app_list_strings['payment_status_list']['Pending_payment'] = 'Pending payment';
    $app_list_strings['payment_status_list']['Processing'] = 'Processing';
    $app_list_strings['payment_status_list']['On_hold'] = 'On Hold';
    $app_list_strings['payment_status_list']['Completed'] = 'Completed';
    $app_list_strings['payment_status_list']['Cancelled'] = 'Cancelled';
    $app_list_strings['payment_status_list']['Refunded'] = 'Refunded';
    $app_list_strings['payment_status_list']['Failed'] = 'Failed';
}

$app_list_strings['portal_message_list']=array (
  'confirm_delete' => 'Are you sure you want to delete the record?',
  'delete_success' => 'Deleted Successfully',
  'update_success' => 'Updated Successfully',
  'added_success' => 'Added Successfully',
  'added_success_in' => 'Added Successfully In',
  'attach_error' => 'Attachment file size is not proper, Maximum file size can be 2 MB.',
  'module_no_records' => 'No Record(s) Found.',
  'email_exists_error' => 'This email is not updated as it is already registered in CRM, please choose another one.',
  'log_invalid_user_pass' => 'Invalid Username Or Password.',
  'log_user_disabled' => 'Portal is currently disabled for you.',
  'profile_image_error' => 'Your profile details updated successfully, but your image can',
  'profile_update_success' => 'Your profile updated successfully.',
  'search_error' => 'There is some error, please try again.',
);

$app_list_strings['portal_connection_message_list']=array (
  'combination_mismatch' => 'Combination Mismatch: Please verify the combination you choose.',
  'conn_problem' => 'There is problem while connecting, please try again.',
  'conn_failed' => 'Connection is not successful. Please check SugarCRM Version, URL, Username and Password.',
  'conn_success' => 'Connection is Successful!',
  'portal_disabled' => 'Portal is disabled. Please contact your administrator.',
  'setting_updated' => 'Your Settings Updated Successfully!',
  'signup_invalid_username' => 'This username is invalid because it uses illegal characters. Please enter a valid username.',
  'signup_invalid_email' => 'Invalid Email-id, it uses illegal characters. Please enter a valid Email-id.',
  'signup_invalid_image_size' => 'Max image upload size 2MB',
  'signup_invalid_image_format' => 'Only jpg, jpeg, png image format accepted.',
  'signup_invalid_image_type' => 'Choose image file only.',
  'signup_exists_username_email' => 'Username and Email Address already exists.',
  'signup_exists_username' => 'Username already exists.',
  'signup_exists_email' => 'Email Address already exists.',
  'signup_error_profile_upload' => 'Your Registration is Successful, but there is error while adding your profile picture.',
  'signup_error' => 'We are facing error while signup, please try again later.',
  'signup_success' => 'Registration Successful.',
  'forgot_invalid' => 'Please Enter Valid Username and Email.',
  'forgot_error' => 'Failed to send your password. Please try later or contact the administrator by another method.',
  'forgot_success' => 'Your password has been sent successfully.',
  'from_crm_not_update' => 'User data not updated.',
  'from_crm_update' => 'Your data successfully updated.',
);
