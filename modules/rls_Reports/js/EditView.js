/**
 * After load action.
 * */
$(document).ready(function() {
//    hide_empty_label = $('#hide_empty_label').html();
//    setVisibility();
//    $('#chart_type').bind('change', function() {
//        setVisibility();
//    });
});


/**
 * Set visibility for fields.
 *
 * */
function setVisibility()
{
    $('#hide_empty').show();
    $('#hide_empty_label').html(hide_empty_label);
    // hide checkbox for Columns Chart
    if ($('#chart_type').val() == 'Columns') {
        $('#hide_empty').hide();
        $('#hide_empty_label').html('');
    }
}


