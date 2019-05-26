$(function () {
    
    // RLS Vic 29.03.2016
    var ui_tabs_active_class = '.ui-tabs-active';
    var jqueryUI_version = $.ui.version.split('.');
    if (Number(jqueryUI_version[0]) == 1 && Number(jqueryUI_version[1]) < 10) {
        ui_tabs_active_class = '.ui-tabs-selected';
    }
    
    // Initializing Tabs
    Dashboard.Tabs.init();

    // Binds the action for Add Tab button
    $('#dashboard_add_tab a')
        .click(function (e) {
            if (!$(this).parent().hasClass('ui-state-disabled')) {
                Dashboard.Tabs.addEmpty();
            } else {
                alert('Maximal count of tabs has been reached.');
            }
        });

    // Binds the action for Add Dashlet button
    $(document)
        .on('click', '.dashboard_add_dashlet', function () {
            Dashboard.Dashlets.addEmptyDashlet($(ui_tabs_active_class).attr('alt'));
        });

    // Binds dropdown for changing layout
    $(document)
        .on('change', '#tabs .dashboard_page_layout', function () {
            Dashboard.Layout.set({
                tab_guid: $(ui_tabs_active_class).attr('alt'),
                layout_type: $(this).val()
            });
        });

    // bind changing the name of the tab
    $(document)
        .on('dblclick', '#tabs ul li a', function () {
            Dashboard.Tabs.setInputForName(
                $(this).parent().attr('alt'),
                $(this).text()
            );
        });

    // close icon: removing the tab on click
    // note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
    $(document)
        .on('click', "#tabs span.ui-icon-close", function (e) {
            Dashboard.Tabs.removeTab($(this).parent());
        });
});

/*function set_return_rls(popup_reply_data) {
    from_popup_return = true;
    var form_name = popup_reply_data.form_name;
    var name_to_value_array = popup_reply_data.name_to_value_array;
    name_to_value_array['title'] = name_to_value_array['report_c'];
    if (typeof name_to_value_array != 'undefined' && name_to_value_array['account_id']) {
        var label_str = '';
        var label_data_str = '';
        var current_label_data_str = '';
        var popupConfirm = popup_reply_data.popupConfirm;
        for (var the_key in name_to_value_array) {
            if (the_key == 'toJSON') {
            }
            else {
                var displayValue = replaceHTMLChars(name_to_value_array[the_key]);
                if (window.document.forms[form_name] && document.getElementById(the_key + '_label') && !the_key.match(/account/)) {
                    var data_label = document.getElementById(the_key + '_label').innerHTML.replace(/\n/gi, '').replace(/<\/?[^>]+(>|$)/g, "");
                    label_str += data_label + ' \n';
                    label_data_str += data_label + ' ' + displayValue + '\n';
                    if (window.document.forms[form_name].elements[the_key]) {
                        current_label_data_str += data_label + ' ' + window.document.forms[form_name].elements[the_key].value + '\n';
                    }
                }
            }
        }
        if (label_data_str != label_str && current_label_data_str != label_str) {
            if (typeof popupConfirm != 'undefined') {
                if (popupConfirm > -1) {
                    set_return_basic(popup_reply_data, /\S/);
                } else {
                    set_return_basic(popup_reply_data, /account/);
                }
            }
            else if (confirm(SUGAR.language.get('app_strings', 'NTC_OVERWRITE_ADDRESS_PHONE_CONFIRM') + '\n\n' + label_data_str)) {
                set_return_basic(popup_reply_data, /\S/);
            }
            else {
                set_return_basic(popup_reply_data, /account/);
            }
        } else if (label_data_str != label_str && current_label_data_str == label_str) {
            set_return_basic(popup_reply_data, /\S/);
        } else if (label_data_str == label_str) {
            set_return_basic(popup_reply_data, /account/);
        }
    } else {
        set_return_basic(popup_reply_data, /\S/);
    }
}*/










