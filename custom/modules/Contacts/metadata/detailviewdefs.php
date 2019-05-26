<?php
$viewdefs ['Contacts'] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
          4 => 
          array (
            'customCode' => '<input type="submit" class="button" title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" onclick="this.form.return_module.value=\'Contacts\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Contacts\';" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}"/>',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
              'htmlOptions' => 
              array (
                'class' => 'button',
                'id' => 'manage_subscriptions_button',
                'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
                'onclick' => 'this.form.return_module.value=\'Contacts\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Contacts\';',
                'name' => 'Manage Subscriptions',
              ),
            ),
          ),
          'AOS_GENLET' => 
          array (
            'customCode' => '<input type="button" class="button" onClick="showPopup();" value="{$APP.LBL_PRINT_AS_PDF}">',
          ),
          'AOP_CREATE' => 
          array (
            'customCode' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED}<input type="submit" class="button" onClick="this.form.action.value=\'createPortalUser\';" value="{$MOD.LBL_CREATE_PORTAL_USER}"> {/if}',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => '{$fields.username_c.value}TEST{$MOD.LBL_CREATE_PORTAL_USER}',
              'htmlOptions' => 
              array (
                'title' => '{$fields.username_c.value}TEST{$MOD.LBL_CREATE_PORTAL_USER}',
                'class' => 'button',
                'onclick' => 'this.form.action.value=\'createPortalUser\';',
                'name' => 'buttonCreatePortalUser',
                'id' => 'createPortalUser_button',
              ),
              'template' => '{if !$fields.joomla_account_id.value && $AOP_PORTAL_ENABLED}[CONTENT]{/if}',
            ),
          ),
          'AOP_DISABLE' => 
          array (
            'customCode' => '{if $fields.joomla_account_id.value && !$fields.portal_account_disabled.value && $AOP_PORTAL_ENABLED}<input type="submit" class="button" onClick="this.form.action.value=\'disablePortalUser\';" value="{$MOD.LBL_DISABLE_PORTAL_USER}"> {/if}',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => '{$MOD.LBL_DISABLE_PORTAL_USER}',
              'htmlOptions' => 
              array (
                'title' => '{$MOD.LBL_DISABLE_PORTAL_USER}',
                'class' => 'button',
                'onclick' => 'this.form.action.value=\'disablePortalUser\';',
                'name' => 'buttonDisablePortalUser',
                'id' => 'disablePortalUser_button',
              ),
              'template' => '{if $fields.joomla_account_id.value && !$fields.portal_account_disabled.value && $AOP_PORTAL_ENABLED}[CONTENT]{/if}',
            ),
          ),
          'AOP_ENABLE' => 
          array (
            'customCode' => '{if $fields.joomla_account_id.value && $fields.portal_account_disabled.value && $AOP_PORTAL_ENABLED}<input type="submit" class="button" onClick="this.form.action.value=\'enablePortalUser\';" value="{$MOD.LBL_ENABLE_PORTAL_USER}"> {/if}',
            'sugar_html' => 
            array (
              'type' => 'submit',
              'value' => '{$MOD.LBL_ENABLE_PORTAL_USER}',
              'htmlOptions' => 
              array (
                'title' => '{$MOD.LBL_ENABLE_PORTAL_USER}',
                'class' => 'button',
                'onclick' => 'this.form.action.value=\'enablePortalUser\';',
                'name' => 'buttonENablePortalUser',
                'id' => 'enablePortalUser_button',
              ),
              'template' => '{if $fields.joomla_account_id.value && $fields.portal_account_disabled.value && $AOP_PORTAL_ENABLED}[CONTENT]{/if}',
            ),
          ),
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
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/Leads/Lead.js',
        ),
      ),
      'useTabs' => true,
      'tabDefs' => 
      array (
        'LBL_CONTACT_INFORMATION' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ADVANCED' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
        'LBL_PANEL_ASSIGNMENT' => 
        array (
          'newTab' => true,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_contact_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
            'comment' => 'First name of the contact',
            'label' => 'LBL_FIRST_NAME',
          ),
          1 => 
          array (
            'name' => 'last_name',
            'comment' => 'Last name of the contact',
            'label' => 'LBL_LAST_NAME',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'phone_work',
            'label' => 'LBL_OFFICE_PHONE',
          ),
          1 => 
          array (
            'name' => 'phone_mobile',
            'label' => 'LBL_MOBILE_PHONE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'title',
            'comment' => 'The title of the contact',
            'label' => 'LBL_TITLE',
          ),
          1 => 
          array (
            'name' => 'department',
            'label' => 'LBL_DEPARTMENT',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'account_name',
            'label' => 'LBL_ACCOUNT_NAME',
          ),
          1 => 
          array (
            'name' => 'phone_fax',
            'label' => 'LBL_FAX_PHONE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'email1',
            'studio' => 'false',
            'label' => 'LBL_EMAIL_ADDRESS',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'label' => 'LBL_PRIMARY_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
            ),
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'label' => 'LBL_ALTERNATE_ADDRESS',
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'alt',
            ),
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'comment' => 'Full text of the note',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
          1 => 
          array (
            'name' => 'bc_user_group_contacts_name',
          ),
        ),
      ),
      'LBL_PANEL_ADVANCED' => 
      array (
        1 => 
        array (
          0 => 
          array (
            'name' => 'report_to_name',
            'label' => 'LBL_REPORTS_TO',
          ),
          1 => 
          array (
            'name' => 'campaign_name',
            'label' => 'LBL_CAMPAIGN',
          ),
        ),
      ),
      'LBL_PANEL_ASSIGNMENT' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'date_entered',
            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
            'label' => 'LBL_DATE_ENTERED',
          ),
          1 => 
          array (
            'name' => 'date_modified',
            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            'label' => 'LBL_DATE_MODIFIED',
          ),
        ),
      ),
      'LBL_SUGARQUICKBOOKS' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'qbcreatedtime_c',
            'label' => 'LBL_QB_CREATED_TIME',
            'type' => 'datetime',
          ),
          1 => 
          array (
            'name' => 'qbmodifiedtime_c',
            'label' => 'LBL_QB_MODIFIED_TIME',
            'type' => 'datetime',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'qbid_c',
            'label' => 'LBL_QBID',
            'type' => 'varchar',
            'len' => '50',
          ),
        ),
      ),
    ),
  ),
);
$viewdefs['Contacts']['DetailView']['templateMeta'] = array (
  'form' => 
  array (
    'buttons' => 
    array (
      0 => 'EDIT',
      1 => 'DUPLICATE',
      2 => 'DELETE',
      3 => 'FIND_DUPLICATES',
      4 => 
      array (
        'customCode' => '<input type="submit" class="button" title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" onclick="this.form.return_module.value=\'Contacts\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Contacts\';" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}"/>',
        'sugar_html' => 
        array (
          'type' => 'submit',
          'value' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
          'htmlOptions' => 
          array (
            'class' => 'button',
            'id' => 'manage_subscriptions_button',
            'title' => '{$APP.LBL_MANAGE_SUBSCRIPTIONS}',
            'onclick' => 'this.form.return_module.value=\'Contacts\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Contacts\';',
            'name' => 'Manage Subscriptions',
          ),
        ),
      ),
      'AOS_GENLET' => 
      array (
        'customCode' => '<input type="button" class="button" onClick="showPopup();" value="{$APP.LBL_PRINT_AS_PDF}">',
      ),
      'AOP_CREATE' => 
      array (
        'customCode' => '{if empty($fields.username_c.value) && $AOP_PORTAL_ENABLED}<input type="submit" class="button" onClick="this.form.action.value=\'createPortalUser\';" value="{$MOD.LBL_CREATE_PORTAL_USER}"> {/if}',
        'sugar_html' => 
        array (
          'type' => 'submit',
          'value' => '{$MOD.LBL_CREATE_PORTAL_USER}',
          'htmlOptions' => 
          array (
            'title' => '{$MOD.LBL_CREATE_PORTAL_USER}',
            'class' => 'button',
            'onclick' => 'this.form.action.value=\'createPortalUser\';',
            'name' => 'buttonCreatePortalUser',
            'id' => 'createPortalUser_button',
          ),
          'template' => '{if empty($fields.username_c.value) && $AOP_PORTAL_ENABLED}[CONTENT]{/if}',
        ),
      ),
	  'AOP_RESET' => 
      array (
        'customCode' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED && $fields.enable_portal_c.value == 1}<input type="submit" class="button" onClick="this.form.action.value=\'resetPortalUser\';" value="{$MOD.LBL_RESET_PORTAL_USER}"> {/if}',
        'sugar_html' => 
        array (
          'type' => 'submit',
          'value' => '{$MOD.LBL_RESET_PORTAL_USER}',
          'htmlOptions' => 
          array (
            'title' => '{$MOD.LBL_RESET_PORTAL_USER}',
            'class' => 'button',
            'onclick' => 'this.form.action.value=\'resetPortalUser\';',
            'name' => 'buttonResetPortalUser',
            'id' => 'resetPortalUser_button',
          ),
          'template' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED && $fields.enable_portal_c.value == 1}[CONTENT]{/if}',
        ),
      ),
	  'AOP_AUTO_LOGIN' => 
      array (
        'customCode' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED && $fields.enable_portal_c.value == 1}<input type="button" class="button" onClick="autoLogin(\'{$fields.id.value}\')" value="Portal Auto Login"> {/if}',
        'sugar_html' => 
        array (
          'type' => 'button',
          'value' => 'Portal Auto Login',
          'htmlOptions' => 
          array (
            'title' => 'Portal Auto Login',
            'class' => 'button',
            'onclick' => 'autoLogin(\'{$fields.id.value}\')',
            'name' => 'buttonPortalAutoLogin',
            'id' => 'portalAutoLogin_button',
          ),
          'template' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED && $fields.enable_portal_c.value == 1}[CONTENT]{/if}',
        ),
      ),
	  'AOP_ENABLE_PORTAL' => 
      array (
        'customCode' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED && $fields.enable_portal_c.value == 0}<input type="submit" class="button" onClick="this.form.action.value=\'enablePortalUser\';" value="Enable Portal Login"> {/if}',
        'sugar_html' => 
        array (
          'type' => 'submit',
          'value' => 'Enable Portal Login',
          'htmlOptions' => 
          array (
            'title' => 'Enable Portal Login',
            'class' => 'button',
            'onclick' => 'this.form.action.value=\'enablePortalUser\';',
            'name' => 'buttonPortalEnableUser',
            'id' => 'portalEnableUser_button',
          ),
          'template' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED && $fields.enable_portal_c.value == 0}[CONTENT]{/if}',
        ),
      ),
	  'AOP_DISABLE_PORTAL' => 
      array (
        'customCode' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED && $fields.enable_portal_c.value == 1}<input type="submit" class="button" onClick="this.form.action.value=\'disablePortalUser\';" value="Disable Portal Login"> {/if}',
        'sugar_html' => 
        array (
          'type' => 'submit',
          'value' => 'Disable Portal Login',
          'htmlOptions' => 
          array (
            'title' => 'Disable Portal Login',
            'class' => 'button',
            'onclick' => 'this.form.action.value=\'disablePortalUser\';',
            'name' => 'buttonPortalDisableUser',
            'id' => 'portalDisableUser_button',
          ),
          'template' => '{if !empty($fields.username_c.value) && $AOP_PORTAL_ENABLED && $fields.enable_portal_c.value == 1}[CONTENT]{/if}',
        ),
      ),
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
  'includes' => 
  array (
    0 => 
    array (
      'file' => 'modules/Leads/Lead.js',
    ),
  ),
  'useTabs' => true,
  'tabDefs' => 
  array (
    'LBL_CONTACT_INFORMATION' => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
    ),
    'LBL_PANEL_ADVANCED' => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
    ),
    'LBL_PANEL_ASSIGNMENT' => 
    array (
      'newTab' => true,
      'panelDefault' => 'expanded',
    ),
  ),
);
?>
