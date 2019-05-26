$(document).ready(function () {

    var current_theme = $("#current_theme").val();
    if (current_theme == 'SuiteP') {
        var html = $("#htmlcontent").html();

        $.each($('.panel-content').find('.panel-default'), function (key, value) {

            var panel_div = $(value).find('.panel-heading').html();
            var panel_name = $(panel_div).find('.col-xs-10').html().trim();

            if (panel_name == 'Portal Enable Access Module List') {
                $(value).find('.detail-view-field').width('74%');
               $(value).find('.label').remove();
            }
        });

    }

});

function enableModuleCheckbox(el) {
 
    var ID = $(el).attr('id'); //retrive id of that module 
    var modeName = ID.replace('status_', ''); //Find module name in id's name 
    var selectedVal = $(el).val();

        var value = $('.checked_class_' + modeName).val();
        var deleteaccessnonArray =  $("#deleteaccessnonArray").val();
    var fullaccessnonArray = $("#fullaccessnonArray").val();
    var createaccessnonArray = $("#createaccessnonArray").val();
    var editaccessnonArray = $("#editaccessnonArray").val();


    if (selectedVal == 'Enable' && (fullaccessnonArray.indexOf(modeName) !== -1)) {
            $('.checked_class_' + modeName).prop('disabled', true);
    } else if (selectedVal == 'Enable') {
        $.each($('.checked_class_' + modeName), function () {
            var selectedDDVal = this.value;
                var selectedDDModule = this.id ;
                if(selectedDDModule.indexOf('edit') !== -1 && (editaccessnonArray.indexOf(modeName) !== -1)){
                     $('#'+selectedDDModule).prop('disabled', true);
                }else if(selectedDDModule.indexOf('create') !== -1 && (createaccessnonArray.indexOf(modeName) !== -1)){
                     $('#'+selectedDDModule).prop('disabled', true);
            }
                else if(selectedDDModule.indexOf('delete') !== -1 && (deleteaccessnonArray.indexOf(modeName) !== -1)){
                     $('#'+selectedDDModule).prop('disabled', true);
                }else{
                      $('#'+selectedDDModule).prop('disabled', false);
            }
        });

    }
        else {
            $('.checked_class_' + modeName).prop('checked', false);
            $('.checked_class_' + modeName).prop('disabled', true);
            }

}

function savemoduleaccesslevel() {
  
    var alertMsg = "Please select at least one checkbox for access level on enable module";
    var action_actionArray = {};
    var status_check = [];
    var userID = $("#userID").val();
    var fullaccessnonArray = $("#fullaccessnonArray").val();
    $.each($('.status_Enable_Disable'), function () {

        var selectedDDVal = this.value;
        var selectedDDModule = this.id
        var modulename = selectedDDModule.substring(7);
        if (selectedDDVal == 'Enable') {
            $('input:checkbox[name=action]:checked').each(function ()
            {
                var checkedValue = this.value;
                if (checkedValue.indexOf(modulename) >= 0) {
                    var action_access = checkedValue.replace('_' + modulename, '')
                    status_check.push(action_access);
                }
            });
            action_actionArray[modulename] = status_check;
            status_check = [];
        }
    });
    var count = new Object();
    $.each(action_actionArray, function (key, value) {
        count[key] = (Object.keys(value).length);
    });
    var flag = true;
    $.each(count, function (key, info) {
        if (info == "0" && (fullaccessnonArray.indexOf(key) == -1)) {
            flag = false;
        }
    });
    var proceedToSave = true;
    if (!flag) {
        proceedToSave = false;
        $('#Msg').css({color: 'red'});
        $("#Msg").text('Error: ' + alertMsg);

    }
    var selectedModulesString = JSON.stringify(action_actionArray);
    if(flag){
        $.ajax({
            url: 'index.php?module=Administration&action=portalHandler&method=moduleAccessLevel',
            type: 'POST',
        data: {setting: selectedModulesString,userID:userID},
            success: function (data) {
             $('#Msg').css({color: 'green'});
             $("#Msg").text('Module(s) updated successfully.');
            }
        });
    }
}