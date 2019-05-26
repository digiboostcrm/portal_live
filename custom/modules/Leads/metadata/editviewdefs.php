<?php
$viewdefs ['Leads'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          0 => '<input type="hidden" name="prospect_id" value="{if isset($smarty.request.prospect_id)}{$smarty.request.prospect_id}{else}{$bean->prospect_id}{/if}">',
          1 => '<input type="hidden" name="account_id" value="{if isset($smarty.request.account_id)}{$smarty.request.account_id}{else}{$bean->account_id}{/if}">',
          2 => '<input type="hidden" name="contact_id" value="{if isset($smarty.request.contact_id)}{$smarty.request.contact_id}{else}{$bean->contact_id}{/if}">',
          3 => '<input type="hidden" name="opportunity_id" value="{if isset($smarty.request.opportunity_id)}{$smarty.request.opportunity_id}{else}{$bean->opportunity_id}{/if}">',
        ),
        'buttons' => 
        array (
          0 => 
          array (
            'customCode' => '<input title="Save" accesskey="a" class="button primary" onclick="var _form = document.getElementById(\'EditView\'); _form.action.value=\'Save\'; if( check_form(\'EditView\') , validate_source())SUGAR.ajaxUI.submitForm(_form);return false;" type="submit" name="button" value="Save" id="SAVE">',
          ),
          1 => 'CANCEL',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'javascript' => '<script type="text/javascript" language="Javascript">function copyAddressRight(form)  {ldelim} form.alt_address_street.value = form.primary_address_street.value;form.alt_address_city.value = form.primary_address_city.value;form.alt_address_state.value = form.primary_address_state.value;form.alt_address_postalcode.value = form.primary_address_postalcode.value;form.alt_address_country.value = form.primary_address_country.value;return true; {rdelim} function copyAddressLeft(form)  {ldelim} form.primary_address_street.value =form.alt_address_street.value;form.primary_address_city.value = form.alt_address_city.value;form.primary_address_state.value = form.alt_address_state.value;form.primary_address_postalcode.value =form.alt_address_postalcode.value;form.primary_address_country.value = form.alt_address_country.value;return true; {rdelim} </script>',
      'useTabs' => false,
      'tabDefs' => 
      array (
        'LBL_CONTACT_INFORMATION' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ADVANCED' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => true,
    ),
    'panels' => 
    array (
      'LBL_CONTACT_INFORMATION' => 
      array (
        0 => 
        array (
          0 => 'company_name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
          ),
          1 => 'last_name',
        ),
        2 => 
        array (
          0 => 'title',
        ),
        3 => 
        array (
          0 => 'phone_work',
          1 => 'phone_mobile',
        ),
        4 => 
        array (
          0 => 'email1',
          1 => 'website',
        ),
        5 => 
        array (
          0 => 'description',
        ),
      ),
      'LBL_PANEL_ADVANCED' => 
      array (
        0 => 
        array (
          0 => 'status',
        ),
        1 => 
        array (
          0 => 'lead_source',
          1 => 
          array (
            'name' => 'lead_source_1_c',
            'studio' => 'visible',
            'label' => 'LBL_LEAD_SOURCE_1',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'lead_type',
            'studio' => true,
            'label' => 'LBL_LEAD_TYPE',
          ),
          1 => 'refered_by',
        ),
        3 => 
        array (
          0 =>
		  array (
            'name' => 'tracking_number',
            'label' => 'LBL_TRACKING_NUMBER',
          ),
          1 => 'campaign_name',
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
        ),
      ),
    ),
  ),
);
;
?>
