$(document).ready(function () {
    $('.list').find('tr').find('input:checkbox:first').remove();
 /*   $('.checkbox').change(function () {
        $('.SugarActionMenu').find('#delete_listview_top').attr('onclick', "return sListView.send_mass_update('selected', 'Please select at least 1 record to proceed.', 1);");
        $('.SugarActionMenu').find('#delete_listview_bottom').attr('onclick', "return sListView.send_mass_update('selected', 'Please select at least 1 record to proceed.', 1);");
        $('.checkbox:checked').each(function () {
           if($(this).closest('tr').find('[type=name]').length != '0'){
            var name=$(this).closest('tr').find('[type=name]').find('a').html().trim();
            if (name == 'Default') {
                if ($(this).is(":checked")) {
                    var name=$(this).closest('tr').find('[type=name]').find('a').html().trim();
                    $('.SugarActionMenu').find('#delete_listview_top').attr('onclick', "overide_delete_fun('" + name + "');");
                    $('.SugarActionMenu').find('#delete_listview_bottom').attr('onclick', "overide_delete_fun('" + name + "');");
                }
            }}
        });
    });*/
  


});
/*
function overide_delete_fun(dGroupName) {
    alert('Please remove "' + dGroupName + '" from your selection list. This is a default group and not allowd to delete this.');
}
*/
