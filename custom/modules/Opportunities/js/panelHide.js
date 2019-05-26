
	$( document ).ready(function() {
		//logic hide show for project status
			console.log('new data ');
		$( "#sales_stage" )
		  .change(function () {
			var str = "";
			str = this.value;
			if(str == 'Closed Won'){
				
				$("#project_status").parent("div").parent("div").show();
			}
			else{
				
				$("#project_status").parent("div").parent("div").hide();
				$("#project_status").val("");
			}
			/*probiblty logic */
			console.log(str);
			if(str == 'Discovery')
				$("#probability").val("5");
			else if(str == 'Educate')
				$("#probability").val("30");
			else if(str == 'Validate')
				$("#probability").val("50");
			else if(str == 'Justify')
				$("#probability").val("75");
			else if(str == 'Closed Won')
				$("#probability").val("95");
			else
				$("#probability").val("0");
			
		  })
		  .change();
		
		$('#SAVE_HEADER, #SAVE_FOOTER,#SAVE').removeAttr('onclick');
		$('#SAVE_HEADER, #SAVE_FOOTER,#SAVE').attr('onclick',"var _form = document.getElementById('EditView'); _form.action.value='Save'; if(getRowData() && check_form('EditView'))SUGAR.ajaxUI.submitForm(_form);return false;");
		
		var return_mod = $("#return_mod").val();
		var sdr = $("#custom_lead_account").val();
		
		$("#custom_lead_id").parent("div").parent("div").hide();
		var recID = $("input[name=record]").val();
		
		if(sdr == '' || recID){
			$("#account_panel_show").parent("div").parent("div").hide();
			$("#detailpanel_0").parent("div").hide();
		}else{
			var lead_id = $("#parent_lead_id").val();
			$("#custom_lead_id").val(lead_id);
			$("#custom_lead_id").parent("div").parent("div").hide();
			$('#account_panel_show').prop('checked', true);
			$("#account_name").parent("div").parent("div").hide();
			//$("#detailpanel_0").parent("div").hide();
		}
		
		/* our field logic 01-FEB-2018*/
		
		var detailView = $("#detailView").val();
		var addButton = '<button type="button" class="btn btn-danger email-address-remove-button" title="Add Product " id="Leads0_email_widget_add" onclick="addRow()">'
		+'<span class="glyphicon glyphicon-plus"></span><span></span>'
		+'</button>';
		if(detailView){
			addButton = '';
		}
			
			customHtmlTable = "<table id='product_table' border='0' class='customTable' width='100%' height='50%'>\n\
                  <tr style='background: #cc181e;height:50px;text-align: center;'>\n\
                    <th style='color:white;text-align: center;'>Product Name</th>\n\
                    <th style='color:white;text-align: center;'>Product Amount</th>\n\
					<th style='color:white;text-align: center;'>Post QTY</th>\n\
                    <th style='color:white;text-align: center;'>Social QTY</th>\n\
                    <th style='color:white;text-align: center;'>Product Out Sourced</th>\n\
                    <th style='color:white;text-align: center;'>Product Description</th>\n\
                    <th style='color:white;text-align: center;'></th>" + addButton + "\n\
                  </tr>\n\
                </table> <br/>";
		
		if(detailView){
			
			$("#top-panel-1").html(customHtmlTable);
			
		}else{
			
			$("#detailpanel_2").html(customHtmlTable);
		}
		
		var rowEditCount = $("#totalCountRow").val();
		//check if record edit and adding row
		if(!rowEditCount){
			addRow(0, '',detailView);
			$("#totalCountRow").val(1);
		}else{
			for (j = 0; j < rowEditCount; j++) {
				addRow(j,'',detailView);
			}
			//var rec_id = $("input[name='record']").val(); //current record id
			var jsonData = $("#rowData").val();
			var json_array = jQuery.parseJSON(jsonData);
			var rowCount = 0;
			var mrrPrice = 0;
			var qtyShow = -1;
			$.each(json_array, function (i, dataArray) {
				var name = dataArray.name;
				var fieldName = name.split('_')[0];
				if (i % 6 == 0 && i != 0) {
					rowCount++;
				}
				/*
				if(fieldName == 'productAmount'){
					mrrPrice += parseInt(dataArray.value);
					if(~dataArray.value.indexOf( '0000' ))
						dataArray.value = dataArray.value.slice(0,-4);
				}*/
				if(fieldName == 'productAmount'){
					mrrPrice += parseInt(dataArray.value);
					
					productPrice = dataArray.value.split('.');  
					var actProductPrice = productPrice[0];
					var zeroPrice = productPrice[1];
					
					if(~zeroPrice.indexOf( '0000' ))
						dataArray.value = actProductPrice+'.'+zeroPrice.slice(0,-4);
				}
				/* 06-FEB-2017*/
				if(fieldName == 'productName'){
					var productNameLower = dataArray.value.toLowerCase();
					   qtyShow = productNameLower.indexOf('social media engagement');
					   
					   
				}
				if(fieldName == 'socialQty' || fieldName == 'postQty'){
					
					  /*field hide Show Logic */
					  if(qtyShow < 0){
						 $("#socialQty_"+rowCount).hide();
							$("#postQty_"+rowCount).hide();
  
					  }
					
				}
				$("#"+fieldName+'_'+rowCount).val(dataArray.value);
				
			});	
			
		if($.isNumeric($("#logo_design").html())){
			var logoPrice = $("#logo_design").html();
		}
		else{
			var logoPrice = 0;
		}
		if($.isNumeric($("#website_design_amount_c").html())){
			var webPrice = $("#website_design_amount_c").html();
		}
		else{
			var webPrice = 0;
		}
		
		var oneTimeFee = parseInt(logoPrice)+parseInt(webPrice);
		var header = "<tr style='background: #cc181e;height:50px;text-align: center;'>\n\
                    <th style='color:white;text-align: center;'>One Time Fee: &nbsp; &nbsp; &nbsp; &nbsp;"+oneTimeFee+'.00'+"</th>\n\
                    <th style='color:white;text-align: center;'>MRR Fee:&nbsp; &nbsp; &nbsp; &nbsp;"+mrrPrice+'.00'+"</th>\n\
                    <th style='color:white;text-align: center;'></th>\n\
                    <th style='color:white;text-align: center;'></th>\n\
                    <th style='color:white;text-align: center;'></th>\n\
                    <th style='color:white;text-align: center;'></th>\n\
                  </tr>\n\
                ";
			if(detailView)
				$("#product_table").append(header);
			
		}
		
		
	});

	function showHide(data){
		var check = $("#"+data).is(":checked")
		if(check){
			$("#detailpanel_0").parent("div").show();
			$("#account_name").parent("div").parent("div").hide();
			$("#account_id").val('');
			$("#account_name").val('');
		}
		else{
			$("#detailpanel_0").parent("div").hide();
			$("#lead_account_id").val('');
			$("#account_email").val('');
			$("#account_name").parent("div").parent("div").show();
		}	
		
	}
	
/* 01-02-2018*/	
		function addRow(totalCountRow, editView,detailView) {
		
		if(totalCountRow != 0 && editView != ''){
			var totalCountRow = $('#totalCountRow').val();
			$('#totalCountRow').val(+totalCountRow + 1);
		}
		readOnly = '';
		disabled = '';
		var addProduct = '<button class="btn btn-danger email-address-remove-button" title="Add Product" onclick= "return dataPop('+totalCountRow+')" ><span class="glyphicon glyphicon-move"></span></button>';
		
		if(detailView){
			
			readOnly = 'Readonly';
			disabled = 'disabled';
			addProduct = '';
		}
	    //console.log(totalCountRow);
		var fieldTD = '';
		var removeButton = '<td style="padding:0px"></td>';
		var outSourcedDom = $("#outSourceDom").val();;
		
		
		fieldTD = '<td style="text-align: center;">'+addProduct+'<input type="text" class="productData" '+readOnly+' id="productName_'+totalCountRow+'" name="productName_'+totalCountRow+'"></td>';
		fieldTD += '<td style="text-align: center;"><input type="text" class="productData" '+readOnly+' id="productAmount_'+totalCountRow+'" name="productAmount_'+totalCountRow+'"></td>';
		fieldTD += '<td style="text-align: center;"><input type="text" class="productData" '+readOnly+' id="postQty_'+totalCountRow+'" name="postQty_'+totalCountRow+'"></td>';
		fieldTD += '<td style="text-align: center;"><input type="text" class="productData" '+readOnly+' id="socialQty_'+totalCountRow+'" name="socialQty_'+totalCountRow+'"></td>';
		fieldTD += '<td style="text-align: center;"><select class="productData" '+disabled+' id="outSourced_'+totalCountRow+'" name="outSourced_'+totalCountRow+'">'+outSourcedDom+'</select></td>';
		fieldTD += '<td style="text-align: center;"><textarea class="productData" '+readOnly+' id="productDescription_'+totalCountRow+'" name="productDescription_'+totalCountRow+'" style="width:280px;" cols="50" rows="5"></textarea></td>';
		
		
	
		if (totalCountRow != 0 && readOnly == ''){
			removeButton = '<td style="padding-left:12px !important"><button onclick="removeRow(\'tr_' + totalCountRow + '\');" type="button" id="customRow" class="btn btn-danger email-address-remove-button" title="Remove"><span class="glyphicon glyphicon-remove"></span></button></td>';
		}
		
		var html = '<tr id="tr_' + totalCountRow + '"> ' + fieldTD + removeButton+' </tr>';
						
		$("#product_table").append(html);
}

function removeRow(rowID) {
    $("#" + rowID).remove();
    var totalCountRow = $('#totalCountRow').val();
    //$('#totalCountRow').val(+totalCountRow - 1);
}

function getRowData(moduleName) {
	
    var data = [];
    //getting current date and time
    var currentdate = new Date();
    /*
	var datetime = currentdate.getDate() + "/"
            + (currentdate.getMonth() + 1) + "/"
            + currentdate.getFullYear() + " @ "
            + currentdate.getHours() + ":"
            + currentdate.getMinutes() + ":"
            + currentdate.getSeconds();
			*/
    //getting all fields value from layout
    $.each($(".productData"), function (index, ele) {
        data.push({name: ele.name, value: ele.value});
    });

    var fieldLength = 6;
    var total_rows = data.length / fieldLength;
    
	$("#totalCountRow").val(total_rows);
    $("#rowData").val(JSON.stringify(data));
    return true;
}	

function dataPop(count){
	open_popup(
			"AOS_Products", 
			600, 
			400, 
			"", 
			true, 
			false, 
			{"call_back_function":"set_return","form_name":"EditView","field_to_name_array":{"cost":"productAmount_"+count,"name":"productName_"+count,"description":"productDescription_"+count}}, 
			"single", 
			true
		);
		
		return false;
}

function set_return(popup_reply_data,count) {
  from_popup_return = true;
  var form_name = popup_reply_data.form_name;
  var name_to_value_array = popup_reply_data.name_to_value_array;
	//$("#socialQty_3").hide();
	
	var countField = 0;
	
	var countPos = '';
  for (var the_key in name_to_value_array) {
		if(countField == 0){
			countPos = the_key.split('_')[1];
			countField++;
		}
	  
    if (the_key == 'toJSON') {
      /* just ignore */
    }
    else {
      var displayValue = name_to_value_array[the_key].replace(/&amp;/gi, '&').replace(/&lt;/gi, '<').replace(/&gt;/gi, '>').replace(/&#039;/gi, '\'').replace(/&quot;/gi, '"');
      ;
      if (window.document.forms[form_name] && window.document.forms[form_name].elements[the_key]) {
        window.document.forms[form_name].elements[the_key].value = displayValue;
        SUGAR.util.callOnChangeListers(window.document.forms[form_name].elements[the_key]);
      }
    }
  }
  
  /*field hide Show Logic */
	var productName = name_to_value_array['productName_'+countPos].toLowerCase();
	var productPrice = name_to_value_array['productAmount_'+countPos];
	/*removing extra zero's*/  
	productPrice = productPrice.split('.');  
	var actProductPrice = productPrice[0];
	productPrice = productPrice[1];
				
	if(~productPrice.indexOf( '0000' ))
		productPrice = productPrice.slice(0,-4);
				
	$("#productAmount_"+countPos).val(actProductPrice+'.'+productPrice);
	
	if(productName.indexOf('social media engagement') < 0){
	   $("#socialQty_"+countPos).hide();
	   $("#postQty_"+countPos).hide();
	}
	else{
	   $("#socialQty_"+countPos).show();
	   $("#postQty_"+countPos).show();
	}
  
}
