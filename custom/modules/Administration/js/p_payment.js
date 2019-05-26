$(document).ready(function () {
   // onchange event for status and display div 
    var status = $("[name=status]").val();
    if (status == 0)
    {
        $("#payment").hide();
    }
    $("[name=status]").on('change', function () {
         var status = $("[name=status]").val();
        if (status == 0)
        {
            $("#payment").hide();
        }
        else
        {
            $("#payment").show();
        }

    });
});

// after click on submit this function call 

function validfile()
{
    $('#amount_module1').html(" ");
    $('#amount_fields1').html(" ");
    $('#payment_module1').html(" ");
    $('#payment_feilds1').html(" ");

    var status = $("[name=status]").val();
    var amount_module = $("[name=amount_module]").val();
    var amount_fields = $("[name=amount_fields]").val();
    var payment_module = $("[name=payment_module]").val();
    var payment_feilds = $("[name=payment_feilds]").val();

    // if status is equal to disable then execute this if all feild value is null 
    if (status == 0)
    {
        $.ajax({
            url: "index.php?module=Administration&action=portalHandler&method=validfile",
            data: {'status': status},
            method: "post",
            success: function (data) {

                window.location.href = "index.php?module=Administration&action=index";
            }
        });
    }
    // check validation for that feilds
    else {
         var i = 0;
        if (amount_module.length == 0) {
            $('#amount_module1').html("please select one module");
            i = 1;
           
        }
         else if (amount_fields.length == 0) {
                $('#amount_fields1').html("please select one fields");

                i = 1;
            }
        if (payment_module.length == 0) {
            $('#payment_module1').html("please select one module");

            i = 1;
        }
        else if (payment_feilds.length == 0) {
            $('#payment_feilds1').html("please select one fields");

            i = 1;
        }

        if (i == 1)
        {
            return false;
        }
        else {

            $.ajax({
                url: "index.php?module=Administration&action=portalHandler&method=validfile",
                data: { 'status': status, 'amount_module': amount_module, 'amount_fields': amount_fields, 'payment_module': payment_module, 'payment_feilds': payment_feilds},
                method: "post",
                success: function (data) {

                    window.location.href = "index.php?module=Administration&action=index";
                }
            });
        }
    }
}

 function redirectToindex(){
                        location.href = 'index.php?module=Administration&action=index';
                    }
                    
// onchange event for amount_module and show feilds
function AddModuleFeilds()
{
    var amount_module = $("[name=amount_module]").val();
    var currency_fields = $("#currency_fields").val();
    $.ajax({
        url: "index.php?module=Administration&action=portalHandler&method=getpaymentmoduleFields",
        data: {'module_fileds': amount_module,'currency_fields':currency_fields},
        method: "post",
        success: function (data) {

            if (data == "false")
            {
                $("#amount_fields").html("<option value=''></option>");
            }
            else {
                var data1 = JSON.parse(data);
                var select = $("#amount_fields"), options = '';
                options += "<option value=''>select Fields</option>";
                for (var i = 0; i < data1.length; i++)
                {

                    options += "<option value='" + data1[i].name + "'>" + data1[i].value + "</option>";
                    // window.location.href="http://localhost/SuiteCRM-7.5.3/index.php?module=Administration&action=index";
                }

                $("#amount_fields").html(options);
                // select.append(options);
            }
        }
    });
}
// onchange event for payment_module and show feilds
function AddStatusFeilds()
{
    var payment_module = $("[name=payment_module]").val();
     var status_fields = $("#status_fields").val();
    $.ajax({
        url: "index.php?module=Administration&action=portalHandler&method=getpaymentmoduleFields",
        data: {'module_fileds': payment_module,'status_fields':status_fields},
        method: "post",
        success: function (data) {
            if (data == "false")
            {
                $("#payment_feilds").html("<option value=''></option>");
            }
            else {
                var data1 = JSON.parse(data);
                var select = $("#payment_feilds"), options = '';
                options += "<option value=''>select Fields</option>";
                for (var i = 0; i < data1.length; i++)
                {

                    options += "<option value='" + data1[i].name + "'>" + data1[i].value + "</option>";

                }
                $("#payment_feilds").html(options);

            }
        }
    });
}

