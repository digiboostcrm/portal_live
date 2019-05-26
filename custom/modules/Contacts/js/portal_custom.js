$(document).ready(function () {
	
	YAHOO.util.Event.addListener($('#account_name')[0], 'change', togglePortalLogin);
	
	function togglePortalLogin(){
		if($.trim($(this).val()) === ""){
			$('#detailpanel_1').closest('.panel').hide();
		}else{
			$('#detailpanel_1').closest('.panel').show();
		}
	}
	
	$('#account_name').on('change', togglePortalLogin).trigger('change');
	
	$('#password_c').parent('div').parent('div').hide();
	
	var record_id = $('input[name=record]').val();
	
	if(record_id)
		
    var enable_portal_c = $('input[name=enable_portal_c]').is(':checked');
    $("#enable_portal_c").click(function () {
        if ($("#enable_portal_c").is(":checked")) {
            $("#username_c").prop('disabled', false);
            $("#password_c").prop('disabled', true);
        }
        else {
            $("#username_c").prop('disabled', true);
            $("#password_c").prop('disabled', true);
           
        }
    });
    if (enable_portal_c) {
        $("#username_c").prop('disabled', false);
        $("#password_c").prop('disabled', true);
    }
    else {
        $("#username_c").prop('disabled', true);
        $("#password_c").prop('disabled', true);
    }
  $("#register_from_c").prop('disabled', true);
});

function converttoportalcontact(element) {
    var record_ids = '';
    var allChecked = $('input[name="mass[]"]:checked');
    var sendPeopleCount = $('#selectCountTop').val();
    var red_id = new Array();
    for (var i = 0; i < sendPeopleCount; i++) {
        var checkedBox = $("input[name='mass[" + i + "]']").val(); // get other pages selected ids
        if (checkedBox != null) {
            red_id.push(checkedBox);
        }
    }
    for (i = 0; i < $(allChecked).length; i++) {
        var checkedBox = allChecked[i]; // get current pages selected ids
        if (red_id.indexOf($(checkedBox).val()) == -1) { // if already exists then don't push
            red_id.push($(checkedBox).val());
        }
    }
    record_ids = JSON.stringify(red_id);
    var ids = encodeURIComponent(record_ids);
    window.location = 'index.php?module=Contacts&action=convertToPortalContacts&view=convertToPortalContacts&ids=' + ids;
}

function viewContactRecords(ele) {
    var record = $("[name=record_to_convert]").val();
    var data = JSON.parse(record);
    var recordIDS = data.join();
   if (ele.id == 'export_contacts') {
        window.location = 'index.php?entryPoint=customexport&uid=' + recordIDS + '&module=Contacts';

    }
}

function changePagination(recordId, pageId) {
    $.ajax({
        type: "POST",
        url: "index.php?module=Contacts&action=loadpaginationdata",
        data: {'recordId': recordId, 'pageId': pageId},
        success: function (result) {
            $("#not_converted_records_view").show();
            $("#not_converted_records_view").html(result);
        }
    });
}
