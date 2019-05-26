/**
  Created By : Urdhva Tech Pvt. Ltd.
  Created date : 02/20/2017
  Contact at : contact@urdhva-tech.com
  Web : www.urdhva-tech.com
  Skype : urdhvatech
*/
var count = 0;
$(document).ready(function () {
    $("#sending_type").change(function() {
        selectedVal = $(this).val();
        $("#browsefiles_block").hide();
        $("#rs_template_block_1").hide();
        if(selectedVal =='select_template')
        {
            $("#rs_template_block_1").show();
            $("#signer_cc_btn_row").hide();
            $("#rs_template_id").prop('required',true);
        }
        else if(selectedVal =='external_file'){
            $("#browsefiles_block").show();
            $("#rs_template_id").val('');
            $("#signer_cc_btn_row").show();
            $("#rs_template_id").prop('required',null);
        }
        for(i=0;i<=count;i++){
            $("#emlRow_"+i).remove();
        }
        $(".mergefield_block").remove();
    });
    $("#sending_type").change();
    rsGetTemplateCallback = function(o){
        try {
            var response = eval("(" + o.responseText + ")");
            if(response.status == "success"){
                if(response.message != '')
                    alert(response.message);
                for(i=0;i<=count;i++){
                    $("#emlRow_"+i).remove();
                }
                $(".mergefield_block").remove();
                if(response.data.roles_html !=''){
                    $("#appendRowsDiv").append(response.data.roles_html);
                }
                if(response.data.mergefields_html !=''){
                    $("#appendMergeFieldsRow").append(response.data.mergefields_html);
                }
                $("#subject").val(response.data.subject);
                $("#message").val(response.data.description);
                count= response.count;
                $.unblockUI();
            }
            else{
                if(response.message != '')
                    alert(response.message);
                else
                    alert(SUGAR.language.get("UT_RightSignature", "LBL_ERRMSG_4"));
                $.unblockUI();
            }
        }
        catch(e) {
            alert(SUGAR.language.get("UT_RightSignature", "LBL_ERRMSG_4"));
            $.unblockUI();
        }
    }
    $("#rs_template_id").change(function() {
        selectedVal = $(this).val();
        sTemplateId = $("#rs_template_id").val();
        if(sTemplateId != '')
        {
            $.blockUI({ message: '<h1>'+SUGAR.language.get("UT_RightSignature", "LBL_LOADING_TEMPLATE")+'</h1>' });
            postData = "sugar_body_only=1&to_pdf=1&module=UT_RightSignature&action=GetRSTemplateDetails&rs_template_id="+sTemplateId+"&count="+count;
            if(prePopFromDetailViewModule!='' && prePopFromDetailViewId !='' && prePopFromDetailViewName !=''){
                postData+="&prePopFromDetailViewModule="+prePopFromDetailViewModule+"&prePopFromDetailViewId="+prePopFromDetailViewId+"&prePopFromDetailViewName="+prePopFromDetailViewName+"&prePopFromDetailViewEmail="+prePopFromDetailViewEmail;
            }
            var cObj = YAHOO.util.Connect.asyncRequest('POST','index.php', {success: rsGetTemplateCallback, failure: rsGetTemplateCallback}, postData);
        }
        else{
            alert(SUGAR.language.get("UT_RightSignature", "LBL_NO_TEMPLATED_SELECTED"));
        }
    });
    $("body").delegate( ".removeRow", "click", function() {
        iRowIndex = $(this).attr('rowIndex');
        sSugarId = $("#sugarid---"+iRowIndex).val();
        if(sSugarId != ""){
            if(confirm(SUGAR.language.get("UT_RightSignature", "LBL_CONFIRM_DELETE_KEYWORD"))){
                // Make ajax call to delete the keyword
                $("#emlRow_"+iRowIndex).remove();
            }
        }
        else
            $("#emlRow_"+iRowIndex).remove();
    });
    
    $("#btnaddsigner").click(function(eventData) {
        addKeyword(eventData,'signer');
    });
    $("#btnaddcc").click(function(eventData) {
        addKeyword(eventData,'cc');
    });
    $("#signer_from_crm").click(function(eventData) {
        addSignerCCFromCRM(eventData,'signer');
    });
    $("#cc_from_crm").click(function(eventData) {
        addSignerCCFromCRM(eventData,'cc');
    });
    $( document ).delegate(".btnSelectSignerFromTemplate", "click", function(eventData) {
        clickedCount = $(this).attr('clickedCount');
        addSignerCCFromCRM(eventData,'',true,clickedCount);
    });
    $("#btnSelectSigner").click(function(eventData) {
        SelectedModuleName=$("#parent_type").val();
        SelectedRecordName=$("#parent_name").val();
        SelectedRecordID=$("#parent_id").val();
        SelectedRecordEmail=$("#email_address").val();
        sForTemplate = $("#for_template").val();
        clickedCount = $("#clickedCount_txt").val();
        if(SelectedRecordID != '')
        {
            if(SelectedRecordEmail==''){
                alert(SUGAR.language.get("UT_RightSignature", "LBL_ERRMSG_1"));
            }
            else
            {
                if(sForTemplate=='1') {
                    $("#receipient_name___"+clickedCount).val(SelectedRecordName);
                    $("#receipient_email___"+clickedCount).val(SelectedRecordEmail);
                    $("#sugar_module___"+clickedCount).val(SelectedModuleName);
                    $("#sugar_module_id___"+clickedCount).val(SelectedRecordID);
                    
                    $("#receipient_name___"+clickedCount).prop('readonly', true);
                    $("#receipient_email___"+clickedCount).prop('readonly', true);
                }
                else {
                    actionbtn = $("#txtclickedaction").val();
                    if(actionbtn == 'signer'){
                        addKeyword(eventData,'signer');
                    }
                    else{
                        addKeyword(eventData,'cc');
                    }
                    $("#receipient_name___"+count).val(SelectedRecordName);
                    $("#receipient_email___"+count).val(SelectedRecordEmail);
                    $("#sugar_module___"+count).val(SelectedModuleName);
                    $("#sugar_module_id___"+count).val(SelectedRecordID);
                    
                    $("#receipient_name___"+count).prop('readonly', true);
                    $("#receipient_email___"+count).prop('readonly', true);
                }
                $("#parent_type").val('');
                $("#parent_name").val('');
                $("#parent_id").val('');
                $("#email_address").val('');
                $("#for_template").val('0');
                $("#clickedCount_txt").val('-1');
                
                $('#rs_signerfromcrm_modal').modal('hide');
            }
        }
        else{
            alert(SUGAR.language.get("UT_RightSignature", "LBL_SELECT_RECORD"));
        }
    });
    if(prePopulateAction != ''){
        comesFromModuleDetailView(prePopulateAction);
    }
});

function addKeyword(event,actionbtn){
    count++;
    var $clone = $("#closerHidden").clone();
    $clone.attr({
        id: "emlRow_" + count,
        name: "emlRow_" + count,
        style: "" // remove "display:none"
    });
    $clone.find("input,select,textarea").each(function(){
        sValue = '';
        isCheckbox = false;
        isTextArea = false;
        if($(this).attr("id") == "must_sign|||" && actionbtn == 'signer'){
            sValue = 1;
        }
        $(this).attr({
            id: $(this).attr("id") + count,
            name: $(this).attr("name") + count,
            value: sValue,
            required: 'required'
        });
    });
    $clone.find("#divCollapse---").each(function(){
        sdivCollapse = "divCollapse---"+count;
        $(this).attr({
            id: sdivCollapse,
            name: sdivCollapse
        });
    });
    $clone.find("a").each(function(){
        if($(this).attr("data-target") ){
            if($(this).attr("data-target") == "#divCollapse---") {
                sString1 = "#divCollapse---"+count;
                $(this).attr({
                    "data-target": sString1
                });
            }
        }
    });
    $clone.find("span").each(function(){
        if($(this).hasClass("removeRow")) {
            $(this).attr({
                rowindex: count
            });
        }
    });
    $clone.find("img").each(function(){
        if($(this).attr("id") == "img_signer_cc---"){
            imgsrc = '';
            if(actionbtn == 'signer')
                imgsrc = 'modules/UT_RightSignature/images/rightsignature_32x32.png';
            else
                imgsrc = 'modules/UT_RightSignature/images/cc.png';
            $(this).attr({
                src: imgsrc,
            });
        }
    });
    //if count == 1 then do not add the first horizontal line
    if(count==1){
        $clone.find("#dividerRow---").each(function(){
            sdivDividerRow = "dividerRow---"+count;
            $(this).attr({
                id: sdivDividerRow,
                style: 'padding-bottom:10px',
            });
        });
    }
    else
    {
        $clone.find("#dividerRow---").each(function(){
            sdivDividerRow = "dividerRow---"+count;
            $(this).attr({
                id: sdivDividerRow,
            });
        });
    }
    $("#appendRowsDiv").append($clone);
}
function addSignerCCFromCRM(event,actionbtn,sForTemplate,clickedCount) {
    $("#for_template").val('0');
    $("#clickedCount_txt").val('-1');
    if(actionbtn == 'signer'){
        $("#txtclickedaction").val('signer');
    }
    else if(actionbtn == 'cc'){
        $("#txtclickedaction").val('cc');
    }
    if(sForTemplate==true){
        $("#for_template").val('1');
        $("#clickedCount_txt").val(clickedCount);
    }
    $('#rs_signerfromcrm_modal').modal('show',{backdrop: 'static'});
    return false;
}
function comesFromModuleDetailView(sending_type)
{
    $("#sending_type").val(sending_type);
    $('#sending_type').trigger('change');
    if(sending_type == 'external_file'){
        addSignerCCFromCRM('','signer');
        $("#parent_type").val(prePopFromDetailViewModule);
        $("#parent_name").val(prePopFromDetailViewName);
        $("#parent_id").val(prePopFromDetailViewId);
        $("#email_address").val(prePopFromDetailViewEmail);
        $("#for_template").val('0');
        $("#clickedCount_txt").val('-1');
        $('#btnSelectSigner').trigger('click');
    }
    else if(sending_type == 'select_template'){
    }
}
function showAjaxLoading(){
    $.blockUI({ message: '<h1>'+SUGAR.language.get("UT_RightSignature", "LBL_PROCESSING")+'</h1>' });
    return false;
}