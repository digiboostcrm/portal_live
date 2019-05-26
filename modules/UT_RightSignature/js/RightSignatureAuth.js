/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
$(document).ready(function () {
    $("a[href^='#demo']").click(function (evt){
        evt.preventDefault();
        var scroll_to = $($(this).attr("href")).offset().top;
        $("html,body").animate({ scrollTop: scroll_to - 80 }, 600);
    });
    $("a[href='#']").click(function (evt){
        evt.preventDefault();
    });
    $(".actionsociallogout").click(function (evt){
        social = $(this).attr('social');
        SUGAR.ajaxUI.showLoadingPanel();
        url = "index.php?module=UT_RightSignature&action=logoutFromApplication&to_pdf=1&sugar_body_only=1";
        $.ajax({
            type: "POST",
            url: url,
            data: {social: social},
            dataType: 'json',
            async : true,
            success: function(e) {
                if(e.status == "success"){
                    sSocialDiv = e.data.social;
                    $("#"+sSocialDiv+"_divreplace").html(e.data.html);
                }
                SUGAR.ajaxUI.hideLoadingPanel();
            },
            failure: function(e) {
                SUGAR.ajaxUI.hideLoadingPanel();
            }
        });
    });
    $(".actionsociallogin").click(function (evt){
        social = $(this).attr('social');
    });
    $('#btnYesRightSignature').click(function() {
        $("#oauth2_rightsignature").submit();
    });
});