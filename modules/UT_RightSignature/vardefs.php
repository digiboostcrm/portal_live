<?php
/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/

$dictionary['UT_RightSignature'] = array(
	'table'=>'ut_rightsignature',
	'audited'=>true,
    'inline_edit'=>true,
		'fields'=>array (
  'number' =>
    array(
        'required' => true,
        'name' => 'number',
        'vname' => 'LBL_RS_NUMBER',
        'type' => 'int',
        'len' => 11,
        'isnull' => 'false',
        'unified_search' => true,
        'comments' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'reportable' => true,
        'disable_num_format' => true,
    ),
  'parent_name' => 
  array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'parent_name',
    'vname' => 'LBL_FLEX_RELATE',
    'type' => 'parent',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 25,
    'size' => '20',
    'options' => 'parent_type_display',
    'studio' => 'visible',
    'type_name' => 'parent_type',
    'id_name' => 'parent_id',
    'parent_type' => 'record_type_display',
  ),
  'parent_type' => 
  array (
    'required' => false,
    'name' => 'parent_type',
    'vname' => 'LBL_PARENT_TYPE',
    'type' => 'parent_type',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 255,
    'size' => '20',
    'dbType' => 'varchar',
    'studio' => 'hidden',
  ),
  'parent_id' => 
  array (
    'required' => false,
    'name' => 'parent_id',
    'vname' => 'LBL_PARENT_ID',
    'type' => 'id',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => true,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'email_address' => 
  array (
    'required' => false,
    'name' => 'email_address',
    'vname' => 'LBL_EMAIL_ADDRESS',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '150',
    'size' => '20',
  ),
  'rightsignature_templates' => 
  array (
    'required' => false,
    'name' => 'rightsignature_templates',
    'vname' => 'LBL_RIGHTSIGNATURE_TEMPLATES',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'rightsignature_templates_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'sending_type' => 
  array (
    'required' => true,
    'name' => 'sending_type',
    'vname' => 'LBL_SENDING_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'sending_type_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'recipient_name' => 
  array (
    'required' => false,
    'name' => 'recipient_name',
    'vname' => 'LBL_RECIPIENT_NAME',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '150',
    'size' => '20',
  ),
  'rs_doc_id' => 
  array (
    'required' => false,
    'name' => 'rs_doc_id',
    'vname' => 'LBL_RS_DOC_ID',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'sending_request_id' => 
  array (
    'required' => false,
    'name' => 'sending_request_id',
    'vname' => 'LBL_RS_SENDING_REQ_ID',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'subject' => 
  array (
    'required' => false,
    'name' => 'subject',
    'vname' => 'LBL_SUBJECT',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
  ),
  'message' => 
  array (
    'required' => false,
    'name' => 'message',
    'vname' => 'LBL_MESSAGE',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'studio' => 'visible',
    'rows' => '4',
    'cols' => '20',
  ),
  'state' => 
  array (
    'required' => false,
    'name' => 'state',
    'vname' => 'LBL_STATE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'rs_state_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'rs_created_at' => 
  array (
    'required' => false,
    'name' => 'rs_created_at',
    'vname' => 'LBL_RS_CREATED_AT',
    'type' => 'datetimecombo',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'enable_range_search' => false,
    'dbType' => 'datetime',
  ),
  'rs_completed_at' => 
  array (
    'required' => false,
    'name' => 'rs_completed_at',
    'vname' => 'LBL_RS_COMPLETED_AT',
    'type' => 'datetimecombo',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'enable_range_search' => false,
    'dbType' => 'datetime',
  ),
  'note_id_c' => 
  array (
    'required' => false,
    'name' => 'note_id_c',
    'vname' => 'LBL_ORIGINAL_FILE_NOTE_ID',
    'type' => 'id',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => true,
    'reportable' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'original_file' => 
  array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'original_file',
    'vname' => 'LBL_ORIGINAL_FILE',
    'type' => 'relate',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
    'id_name' => 'note_id_c',
    'ext2' => 'Notes',
    'module' => 'Notes',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
  ),
  'note_id1_c' => 
  array (
    'required' => false,
    'name' => 'note_id1_c',
    'vname' => 'LBL_SIGNED_FILE_NOTE_ID',
    'type' => 'id',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => 0,
    'audited' => false,
    'inline_edit' => true,
    'reportable' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 36,
    'size' => '20',
  ),
  'signed_file' => 
  array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'signed_file',
    'vname' => 'LBL_SIGNED_FILE',
    'type' => 'relate',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '255',
    'size' => '20',
    'id_name' => 'note_id1_c',
    'ext2' => 'Notes',
    'module' => 'Notes',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
  ),
  'rs_expires_on' => 
  array (
    'required' => false,
    'name' => 'rs_expires_on',
    'vname' => 'LBL_RS_EXPIRES_ON',
    'type' => 'datetimecombo',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'enable_range_search' => false,
    'dbType' => 'datetime',
  ),
  'processing_state' => 
  array (
    'required' => false,
    'name' => 'processing_state',
    'vname' => 'LBL_PROCESSING_STATE',
    'type' => 'enum',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 100,
    'size' => '20',
    'options' => 'rs_processing_state_list',
    'studio' => 'visible',
    'dependency' => false,
  ),
  'original_url' => 
  array (
    'required' => false,
    'name' => 'original_url',
    'vname' => 'LBL_ORIGINAL_URL',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'rows' => '4',
    'cols' => '20',
  ),
  'pdf_url' => 
  array (
    'required' => false,
    'name' => 'pdf_url',
    'vname' => 'LBL_PDF_URL',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'rows' => '4',
    'cols' => '20',
  ),
  'thumbnail_url' => 
  array (
    'required' => false,
    'name' => 'thumbnail_url',
    'vname' => 'LBL_THUMBNAIL_URL',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'rows' => '4',
    'cols' => '20',
  ),
  'signed_pdf_url' => 
  array (
    'required' => false,
    'name' => 'signed_pdf_url',
    'vname' => 'LBL_SIGNED_PDF_URL',
    'type' => 'text',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'size' => '20',
    'rows' => '4',
    'cols' => '20',
  ),
  'size' => 
  array (
    'required' => false,
    'name' => 'size',
    'vname' => 'LBL_SIZE',
    'type' => 'float',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'inline_edit' => '',
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => '18',
    'size' => '20',
    'enable_range_search' => false,
    'precision' => '2',
  ),
  'ut_rightsignature_ut_rsactivities' => 
      array (
          'name' => 'ut_rightsignature_ut_rsactivities',
          'type' => 'link',
          'relationship' => 'ut_rightsignature_ut_rsactivities',
          'source' => 'non-db',
          'module' => 'UT_RSActivities',
          'bean_name' => false,
          'side' => 'right',
          'vname' => 'LBL_UT_RIGHTSIGNATURE_UT_RSACTIVITIES_FROM_UT_RSACTIVITIES_TITLE',
      ),
  'ut_signers_ut_rightsignature' => 
      array (
          'name' => 'ut_signers_ut_rightsignature',
          'type' => 'link',
          'relationship' => 'ut_signers_ut_rightsignature',
          'source' => 'non-db',
          'module' => 'UT_Signers',
          'bean_name' => false,
          'side' => 'right',
          'vname' => 'LBL_UT_SIGNERS_UT_RIGHTSIGNATURE_FROM_UT_SIGNERS_TITLE',
      ),
  'ut_rightsignature_accounts_1' => 
  array (
      'name' => 'ut_rightsignature_accounts_1',
      'type' => 'link',
      'relationship' => 'ut_rightsignature_accounts_1',
      'source' => 'non-db',
      'module' => 'Accounts',
      'bean_name' => 'Account',
      'vname' => 'LBL_UT_RIGHTSIGNATURE_ACCOUNTS_1_FROM_ACCOUNTS_TITLE',
  ),
  'ut_rightsignature_contacts_1' => 
  array (
      'name' => 'ut_rightsignature_contacts_1',
      'type' => 'link',
      'relationship' => 'ut_rightsignature_contacts_1',
      'source' => 'non-db',
      'module' => 'Contacts',
      'bean_name' => 'Contact',
      'vname' => 'LBL_UT_RIGHTSIGNATURE_CONTACTS_1_FROM_CONTACTS_TITLE',
  ),
  'ut_rightsignature_leads_1' => 
  array (
      'name' => 'ut_rightsignature_leads_1',
      'type' => 'link',
      'relationship' => 'ut_rightsignature_leads_1',
      'source' => 'non-db',
      'module' => 'Leads',
      'bean_name' => 'Lead',
      'vname' => 'LBL_UT_RIGHTSIGNATURE_LEADS_1_FROM_LEADS_TITLE',
  ),
  'ut_rightsignature_prospects_1' => 
  array (
      'name' => 'ut_rightsignature_prospects_1',
      'type' => 'link',
      'relationship' => 'ut_rightsignature_prospects_1',
      'source' => 'non-db',
      'module' => 'Prospects',
      'bean_name' => 'Prospect',
      'vname' => 'LBL_UT_RIGHTSIGNATURE_PROSPECTS_1_FROM_PROSPECTS_TITLE',
  ),
  'ut_rightsignature_aos_contracts_1' => 
  array (
      'name' => 'ut_rightsignature_aos_contracts_1',
      'type' => 'link',
      'relationship' => 'ut_rightsignature_aos_contracts_1',
      'source' => 'non-db',
      'module' => 'AOS_Contracts',
      'bean_name' => 'AOS_Contracts',
      'vname' => 'LBL_UT_RIGHTSIGNATURE_AOS_CONTRACTS_1_FROM_AOS_CONTRACTS_TITLE',
  ),
  'ut_rightsignature_aos_quotes_1' => 
  array (
      'name' => 'ut_rightsignature_aos_quotes_1',
      'type' => 'link',
      'relationship' => 'ut_rightsignature_aos_quotes_1',
      'source' => 'non-db',
      'module' => 'AOS_Quotes',
      'bean_name' => 'AOS_Quotes',
      'vname' => 'LBL_UT_RIGHTSIGNATURE_AOS_QUOTES_1_FROM_AOS_QUOTES_TITLE',
  ),
),
	'relationships'=>array (
),
	'optimistic_locking'=>true,
		'unified_search'=>true,
	);
if (!class_exists('VardefManager')){
        require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('UT_RightSignature','UT_RightSignature', array('basic','assignable','security_groups','file'));