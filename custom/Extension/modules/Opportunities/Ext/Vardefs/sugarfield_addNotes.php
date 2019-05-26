<?php
$dictionary['Opportunity']['fields']['se_manager'] = array(

      'required' => false,
      'name' => 'se_manager',
      'vname' => 'LBL_AOP_CASE_UPDATES_THREADED',
      'type' => 'function',
      'source' => 'non-db',
      'massupdate' => 0,
      'studio' => 'visible',
      'importable' => 'false',
      'duplicate_merge' => 'disabled',
      'duplicate_merge_dom_value' => 0,
      'audited' => false,
      'reportable' => false,
      'inline_edit' => 0,
      'function' => 
      array (
        'name' => 'custom_notes',
        'returns' => 'html',
        'include' => 'custom/modules/Opportunities/Case_Updates.php',
      ),

);
?>