$(document).ready(function () {
    var group_name = $("#name").val();
    if (group_name == 'Default') {
        $("#name").prop('disabled', true);
        $("#accessible_modules").prop('disabled', true);
    }
});

function portal_accessible(view) {
    var _form = document.getElementById(view);
    _form.return_id.value = '';
    _form.action.value = 'Save';
    var flag = check_form('EditView');
    var current_theme = $("#current_theme").val();
    var portal_accessible_group = $('input[name=is_portal_accessible_group]').is(':checked');
    var group_name = $('#name').text();
    if (!group_name) {
        group_name = $('#name').val();
    }
    var group_id = $('input[name=record]').val();
    if (flag) {
        $.ajax({
            url: 'index.php?module=Administration&action=portalHandler&method=CheckPortalAccesibleGroup',
            type: 'POST',
            data: {'portal_accessible_group': portal_accessible_group, 'group_name': group_name, group_id: group_id},
            success: function (data) {
                var messages = JSON.parse(data);
                $("#portal_group").remove();
                $("#group_name").remove();
                if (messages.Portal_group) {
                    if (current_theme == 'SuiteP') {
                      $('#is_portal_accessible_group').parent('div').append('<div class="required validation-message" id="portal_group">' + messages.Portal_group + '</div>');
                    }else{
                      $('#is_portal_accessible_group').closest('td').append('<div class="required validation-message" id="portal_group">' + messages.Portal_group + '</div>');  
                    }
                    
                    $('#is_portal_accessible_group').prop("checked", 'checked');
                    flag = false;
                }
                if (messages.group_name) {
                     if (current_theme == 'SuiteP') {
                         $('#name').parent('div').append('<div class="required validation-message" id="group_name">' + messages.group_name + ' </div>');
                     }else{
                        $('#name').closest('td').append('<div class="required validation-message" id="group_name">' + messages.group_name + ' </div>'); 
                     }
                    
                    flag = false;
                }
                if (messages == '') {
                    flag = true;
                }
                if (flag) {
                    SUGAR.ajaxUI.submitForm(_form);
                }
            }
        });
    }

    return flag;
}