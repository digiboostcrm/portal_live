/* 
 * Created by Richlode Solutions
 * Author: Andrii Vasylchenko
 */

$(document).ready(function () {

    $('input[name=nodejshost]').prop('disabled', !$('input#switch_mode').prop("checked"));

    $('input#switch_mode').on('change', function () {
        $('input[name=nodejshost]').prop('disabled', !$('input#switch_mode').prop("checked"));
    });
    
});