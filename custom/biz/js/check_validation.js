/**
 * The file used check contacts fields are validated properly or not. 
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */


$(document).ready(function () {
   
    var current_theme = $("#current_theme").val();
    if (current_theme == 'SuiteP') {
       $('[value=Save]').removeAttr('onclick');
    } else {
$('#SAVE_HEADER').removeAttr('onclick');
$('#SAVE_FOOTER').removeAttr('onclick');
    }
    $('#email123').blur(function () {

    });
    $('#SAVE_HEADER, #SAVE_FOOTER,#SAVE').click(function (e) {
       
        var _form = document.getElementById('EditView');
        _form.action.value = 'Save';
        var flag = false;
        $('#email_exists').remove();
        $('#email_portal_exists').remove();
        if (check_form('EditView')) {
            var flag = true;
            var myEmaildiv = document.getElementById('email_validation');
            var email_address = $('#Contacts0emailAddress0').val();
            var record = $('input[name=record]').val();
            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var username = $("#username_c").val();
            var password = $("#password_c").val();
            var enable_portal_c = $('input[name=enable_portal_c]').is(':checked');
            if (enable_portal_c) {
                if (username.length == 0) {
                    var user_txt_length = $('#usrename_required').length;
                    if (user_txt_length == 0) {
                        if (current_theme == 'SuiteP') {
                            $('#username_c').after('<div class="required validation-message" id="usrename_required" class="username_exist">UserName is Reuired</div>');
                        } else {
                        $('#username_c').closest('td').append('<div class="required validation-message" id="usrename_required" class="username_exist">UserName is Reuired</div>');
                        }
                        e.preventDefault();

                    }
                    flag = false;
                } else {
                    $('#usrename_required').remove();
                  
                }
                if (!$("#password_c").prop('disabled') && password.length == 0) {
                    var pass_txt_length = $('#password_required').length;
                    if (pass_txt_length == 0) {
                        if (current_theme == 'SuiteP') {
                            $('#password_c').after('<div class="required validation-message" id="password_required">Password is required</div>');
                        } else {
                        $('#password_c').closest('td').append('<div class="required validation-message" id="password_required">Password is required</div>');
                        }

                        e.preventDefault();

                    }

                    flag = false;
                }
                else {
                    $('#password_required').remove();
              
                }
            }
            if (flag) {
                $('#usrename_required').remove();
                $('#password_required').remove();
            if ($('#Contacts0emailAddress0').val() != '') {
                $.ajax({
                    url: 'index.php?sugar_body_only=true',
                    type: 'POST',
                    data: {'module': 'Administration', 'action': 'portalHandler', 'method': 'checkExistEmail', email_address: email_address, contact_id: record, username: username, enable_portal_c: enable_portal_c, password: password},
                    async: false,
                    success: function (result) {
                            
                            flag = false;
                        var messages = $.parseJSON(result);
                            if (messages['Email_exist']) {
                                var email_txt_length = $('#email_exists').length;
                                if (email_txt_length == 0) {
                                    if (current_theme == 'SuiteP') {
                                        $('#Contacts0emailAddressRow0').after('<div class="required validation-message" id="email_exists" class="email_exist">' + messages['Email_exist'] + '</div>');
                                    } else {
                                    $('#Contacts0emailAddressRow0').closest('td').append('<div class="required validation-message" id="email_exists" class="email_exist">' + messages['Email_exist'] + '</div>');
                                    }

                            e.preventDefault();
                        }
                            }
                            if (messages['UserName_exist']) {
                                var user_txt_length = $('#username_exists').length;
                                if (user_txt_length == 0) {
                                    if (current_theme == 'SuiteP') {
                                       $('#username_c').after('<div class="required validation-message" id="username_exists" class="username_exist">' + messages['UserName_exist'] + '</div>');
                                    } else {
                                    $('#username_c').closest('td').append('<div class="required validation-message" id="username_exists" class="username_exist">' + messages['UserName_exist'] + '</div>');
                                    }
                                    
                            e.preventDefault();
                        }
                            }
                            if ((messages['Email_exist'] == '' && messages['UserName_exist'] == '')) {
                                $('#email_exists').remove();
                                $('#username_exists').remove();
                            flag = true;
                        }
                        if (flag && enable_portal_c) {

                                $("#password_required").html('');
                                $.ajax({
                                    url: 'index.php?sugar_body_only=true',
                                    type: 'POST',
                                    data: {'module': 'Administration', 'action': 'portalHandler', 'method': 'check_portaluser', email_address: email_address, contact_id: record, username: username, enable_portal_c: enable_portal_c, password: password, last_name: last_name, first_name: first_name},
                                    async: false,
                                    success: function (result) {
                                       
                                        flag = false;
                                        var messages = $.parseJSON(result);
                                        if (messages['Email_portal_exists']) {
                                            var email_txt_length = $('#email_portal_exists').length;
                                            if (email_txt_length == 0) {
                                                if (current_theme == 'SuiteP') {
                                                   $('#Contacts0emailAddressRow0').after('<div class="required validation-message" id="email_portal_exists" class="email_exist">' + messages['Email_portal_exists'] + '</div>');  
                                                }else{
                                                $('#Contacts0emailAddressRow0').closest('td').append('<div class="required validation-message" id="email_portal_exists" class="email_exist">' + messages['Email_portal_exists'] + '</div>');
                                                }
                                               
                                                e.preventDefault();
                        }
                    }
                                        if (messages['Username_portal_exists']) {
                                            var user_txt_length = $('#username_portal_exists').length;
                                            if (user_txt_length == 0) {
                                                if (current_theme == 'SuiteP') {
                                                  $('#username_c').after('<div class="required validation-message" id="username_portal_exists" class="username_exist">' + messages['Username_portal_exists'] + '</div>');  
                                                }else{
                                                $('#username_c').closest('td').append('<div class="required validation-message" id="username_portal_exists" class="username_exist">' + messages['Username_portal_exists'] + '</div>');
                                                }


                                                e.preventDefault();
                                            }
                                        }
                                        if ((messages['Email_portal_exists'] == '' && messages['Username_portal_exists'] == '') || (messages.length == '0')) {
                                            $('#email_portal_exists').remove();
                                            $('#username_portal_exists').remove();
                                            flag = true;
                                        }
                                        else if ((messages['Username_portal_exists'] == '' && messages['Email_portal_exists'] != '')) {
                                            $('#username_portal_exists').remove();
                                        }
                                        else if ((messages['Username_portal_exists'] != '' && messages['Email_portal_exists'] == '')) {
                                            $('#email_portal_exists').remove();
                                        }

                                    }
                });
                            }

            }


                    });
        }
            }



        }
        if (flag) {
            return SUGAR.ajaxUI.submitForm(_form);
        }
        else {
            return flag;
        }


    });
});
