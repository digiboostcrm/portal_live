/**
 * The file used to set layout for portal
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
$(function () {
    var droppableOptionsField = {
        accept: '.le_field ,.field',
        drop: function (event, ui) {
            $('#layout_modified').val('1');
            if ($(ui.draggable).hasClass('field')) {
                var parent = $(ui.draggable).parent();
                if ($(this).find('div.field').length != 0) {
                    $(parent).html('<div class="field">' + $(this).find('div.field').html() + '<div>');
                } else {
                    $(parent).html('<span>(filler)</span>');
                }
                $(this).html('<div class="field">' + $(ui.draggable).html() + '<div>');
                $(ui.draggable).remove();
            } else {
                var check_field = $(this).find('div').find('.field_label').text();
                if (check_field != '') {
                    $(this).find('button').remove();
                    // Remove Checkbox of required feild
                    $(this).find('.required-field').remove();
                    var elehtml = $(this).find('.field').html();
                    var divhtml = "<div data-fhirq-type=\"question\" class=\"le_field field-div ui-state-default ui-draggable\" style=\"z-index: 99999;\"><div class='field'>" + elehtml + "</div></div>";
                    $('#fieldlist').append(divhtml);
                    $("#fieldlist > .le_field").draggable({
                        connectToSortable: '.sortable-ul',
                        revert: 'invalid'
                    });
                    $(this).html($(ui.draggable).html());
                    $(ui.draggable).fadeOut();
                    $(ui.draggable).remove();
                } else {
                    $(this).html($(ui.draggable).html());
                    $(ui.draggable).fadeOut();
                }
            }
            
             $(this).find('div').find('div').remove();
            // Start -- New Field add and checkobox append to that div
            if ($(this).find('.required-field').length == 0)
            {
                if ($(this).find('.default_require').html() == 'true') {
                    $(this).find('div').append('<input type="checkbox" class="required-field" name="required-field" style="float:left;" onclick="requireThis(this);" checked disabled>');
                } else {
                    $(this).find('div').append('<input type="checkbox" class="required-field" name="required-field" style="float:left;" onclick="requireThis(this);">');
                }
            }
          // End
          
            if ($(this).find('.remove-field').length == 0)
            {
                $(this).find('div').append('<button class="remove-field" style="float:right;" onclick="removeThis(this);">x</button>');
            }

            $('#divNewRow > div > .le_row_panel > div >.le_field >.field').draggable({revert: 'invalid'});
        }
    };
    var droppableOptionsRow = {
        accept: '.new-row',
        greedy: true, /* only drop the element in one place */
        hoverClass: "droppable-hover", /* highlight the current drop zone */
        drop: function (event, ui) {
            $d = $(ui.draggable).clone();
            $d.removeClass('new-row');
            $d.removeClass('le_row');
            $d.addClass('added-new-row');
            $d.addClass('le_row_panel');
            $d.addClass(($d.attr('data-fhirq-type') == 'group') ? 'editor-group' : 'editor-question');
            $d.find('.panel_name').remove();
            /* add a button so the element can be removed if no longer necessary */
            var $removeBtn = $('<button class="remove-choice" style="float:right;">x</button>').click(function () {
                removeRows($(this));
                $(this).parent().parent().remove();
            });
            $d.children('div').append($removeBtn);
            $(this).append($d);

            $('.added-new-row').find('div').children('.special').addClass('fc-inner').css('display', 'inline-block');
            $('.added-new-row').find('div').children('.special').droppable(droppableOptionsField).sortable();
        }
    };
    var panel_index = $('#panel_seq').val();
    panel_index++;
    var droppableOptionsPanel = {
        accept: '.new-panel',
        greedy: true, /* only drop the element in one place */
        hoverClass: "droppable-hover", /* highlight the current drop zone */
        drop: function (event, ui) {
            /* clone, because many elements can be added - it's a copy, not a move */
            $d = $(ui.draggable).clone();
            $d.removeClass('new-panel');
            $d.removeClass('le_panel_portal');
            $d.addClass('added-new-panel');
            $d.addClass('le_panel');
            $d.addClass(($d.attr('data-fhirq-type') == 'group') ? 'editor-group' : 'editor-question');
            /* add a button so the element can be removed if no longer necessary */
            var $removePnlBtn = $('<button class="remove-choice" style="float:right;">x</button>').click(function () {
                removeRows($(this));
                $(this).parent().remove();
            });

            var $changeNameBtn = $('<img name="edit_panelname" src="index.php?entryPoint=getImage&themeName=RacerX&imageName=edit_inline.gif" style="cursor:pointer;" alt="">').click(function () {
                console.log($(this).parent().find('.panel_name'));
                var panelTitle = $(this).parent().find("span.panel_name").html();

                var panel_Lbl = '';
                if (panelTitle.indexOf("New Panel") > -1) {
                    panel_Lbl = "";
                }
                else {
                    panel_Lbl = panelTitle;
                }

                var panelInput = $('<input class="panel_title" id="panel" type="text" value="' + panel_Lbl + '"  placeholder="Enter Panel Name" >').blur(function () {
                    //var regex = new RegExp("^[a-zA-Z0-9 ]+$");
                    var value = $(this).val();
                   
                    if (value.length == 0) {
                        $(this).parent().siblings("img[name='edit_panelname']").show();
                        $(this).parent().html(panelTitle);
                    }
                    var regex = new RegExp("^[a-zA-Z0-9_ ]*$");
                    var key = $(this).val();
                    if (!regex.test(key)) {
                        alert("Please use only alphanumeric or alphabetic characters");
                        $("input[name='save']").attr('disabled', true);
                        return false;
                    }
                    else {
                        $(this).parent().siblings("img[name='edit_panelname']").show();
                        $(this).parent().html($(this).val());
                        $("input[name='save']").attr('disabled', false);
                        return true;
                    }
                })
                        .keypress(function (event) {
                            if (event.keyCode == 13) {

                                var d = $("#panel").val();
                                if (d.length == 0) {
                                    event.preventDefault();
                                }
                                else {
                                    var value = $("#panel").val();
                                    if (value.length == 0) {
                                        $("#panel").val(panelTitle);

                                    }
                                    var regex = new RegExp("^[a-zA-Z0-9_ ]*$");
                                    var key = $(this).val();
                                    if (!regex.test(key)) {
                                        alert("Please use only alphanumeric or alphabetic characters");
                                        $("input[name='save']").attr('disabled', true);
                                        return false;
                                    }
                                    else {
                                        $(this).parent().siblings("img[name='edit_panelname']").show();
                                        $(this).parent().html($(this).val());
                                        $("input[name='save']").attr('disabled', false);
                                       return true;
                                    }
                                }
                            }
                });
                $(this).parent().find("span.panel_name").html('');
                $(this).parent().find("span.panel_name").append(panelInput);
                $(this).parent().find("input.panel_title").focus();
                $(this).hide();
            });

            if (typeof panel_index == 'undefined')
            var panelCount = panel_index;

            $d.find('.panel_id').attr('id', 'le_panelid_');

            $d.find('.panel_id').html('lbl_editview_panel');
            $d.find('.panel_name').attr('id', 'le_panelname_' + panel_index);
            $d.find('.panel_name').html('New Panel ' + panel_index);
            $('#le_panellabel_').on('click', function () {
                editPanelProperties(panelCount);
            });
            $d.find('.panel_name').after($removePnlBtn);
            $d.find('.panel_name').after($changeNameBtn);
            $('#panel_seq').val(panel_index);
            panel_index++;

            $d.append('<div class="le_row_panel added-new-row special filter-div droppable"><div><div class="le_field special filter-div droppable" id="1016" style="float: left;clear: none;"><div><span>(filler)</span><span class="field_name">(filler)</span></div></div>\n\
<div class="le_field special filter-div droppable" id="1017" style="float: left;clear: none;"><div><span>(filler)</span><span class="field_name">(filler)</span></div></div></div></div>')
            /* make the new element droppable i.e. to support groups within groups etc... */
            var $removeRowBtn = $('<button class="remove-choice" style="float:right;">x</button>').click(function () {
                removeRows($(this));
                $(this).parent().parent().remove();
            });
            $d.children('div > .le_row_panel').children('div').append($removeRowBtn);
            $(this).append($d);

            $('.added-new-row').find('div').children('.special').addClass('fc-inner').css('display', 'inline-block');
            $('.added-new-row').find('div').children('.special').droppable(droppableOptionsField).sortable();

            $('.added-new-panel').addClass('field-container');
            $('.added-new-panel').droppable(droppableOptionsRow).sortable({items: "> div"});

            $('#divNewRow > div > .le_row_panel > div >.le_field >.field').draggable({revert: 'invalid'});
        }
    };
    $('#divNewRow > div > .le_row_panel > div >.le_field >.field').draggable({
        revert: 'invalid'
    });

    // when saved layout display stop dragging le-field
    $('#divNewRow > .special > div > .le_field').draggable({
        disabled: true
    });

    $('.added-new-panel').droppable(droppableOptionsRow).sortable({items: "> div"});

    // for saved edit layout

    $('.added-new-row').find('div').children('.special').droppable({
        accept: '.le_field ,.field',
        drop: function (event, ui) {
            if ($(ui.draggable).hasClass('field')) {
                var parent = $(ui.draggable).parent();
                if ($(this).find('div.field').length != 0) {
                    $(parent).html('<div class="field">' + $(this).find('div.field').html() + '<div>');
                } else {
                    $(parent).html('<span>(filler)</span>');
                }
                $(this).html('<div class="field">' + $(ui.draggable).html() + '<div>');
                $(ui.draggable).remove();
            } else {
                var check_field = $(this).find('div').find('.field_label').text();
                if (check_field != '') {
                    $(this).find('button').remove();
                    $(this).find('.required-field').remove();
                    $(this).find('.field_required').text('false');
                    var elehtml = $(this).find('.field').html();
                    var divhtml = "<div data-fhirq-type=\"question\" class=\"le_field field-div ui-state-default ui-draggable\" style=\"z-index: 99999;\"><div class='field'>" + elehtml + "</div></div>";
                    $(this).closest('div').html($(ui.draggable).html());
                    $(ui.draggable).fadeOut();
                    $(ui.draggable).remove();
                    $('#fieldlist').append(divhtml);
                    $("#fieldlist > .le_field").draggable({
                        connectToSortable: '.sortable-ul',
                        revert: 'invalid'
                    });
                    
                } else {
                    $(this).html($(ui.draggable).html());
                    $(ui.draggable).fadeOut();
                }
            }
            // Start -- New Field add and checkobox append to that div
            if ($(this).find('.required-field').length == 0)
            {
				 $(this).find('div').find('div').remove();
                if ($(this).find('.default_require').html() == 'true') {
                    $(this).find('div').append('<input type="checkbox" class="required-field" name="required-field" style="float:left;" onclick="requireThis(this);" checked disabled>');
                }
                else if ($(this).find('.field_required').html() == 'true') {
                    $(this).find('div').append('<input type="checkbox" class="required-field" name="required-field" style="float:left;" onclick="requireThis(this);" checked>');
                }
                else {
                    $(this).find('div').append('<input type="checkbox" class="required-field" name="required-field" style="float:left;" onclick="requireThis(this);">');
                }

            }
           // End 
            if ($(this).find('.remove-field').length == 0) {
                $(this).find('div').append('<button class="remove-field" onclick="removeThis(this);">x</button>');
            }
            $('#divNewRow > div > .le_row_panel > div >.le_field >.field').draggable({revert: 'invalid'});
        }
    }).sortable();

    $("#divNewRow").droppable(droppableOptionsPanel).sortable();
    $(".new-row").draggable({
        helper: 'clone',
        revert: 'invalid'
    });
    $(".new-panel").draggable({
        helper: 'clone',
        revert: 'invalid'
    });
    $(".le_field").draggable({
        helper: 'clone',
        revert: 'invalid'
    });

    // Start Listview Drag and drop
    $('#droppable_list').droppable({
        accept: '.field-div',
        drop: function (event, ui) {
            $('#layout_modified').val('1');
            $(this).append(ui.draggable.removeAttr("style").addClass('sel-field-div').removeClass('field-div ui-state-default draggable_list ui-draggable'));
            if (!$(ui.draggable).find(".remove-field").length) {
                $(ui.draggable).append('<button class="remove-field" onclick="removeListElement(this);">x</button>');
            }
            $('.sel-field-div').draggable({
                handle: "div",
// Change by Govind: Resolve sorting issues and drag again after remove elemen.
                appendTo: "body",
                connectToSortable: '.sortable-ul',
                revert: 'invalid',
                  cancel: 'span'
            });
        }
    });

    // for saved list layout
    $('.sel-field-div').draggable({
        handle: "div",
// Change by Govind: Resolve sorting issues and drag again after remove elemen.
        appendTo: "body",
        connectToSortable: '.sortable-ul',
        revert: 'invalid',
         cancel: 'span'
    });

    $('#droppable_list').sortable();
    $("#droppable_list").disableSelection();
    $('.draggable_list').draggable({
        handle: "div",
        appendTo: "body",
        connectToSortable: '.sortable-ul',
        revert: 'invalid'
    });
    // End Listview Drag and drop

    $('.remove-choice').click(function () {
      // Start -- remove checkbox from div
        $(this).parent().find(".required-field").remove();
        removeRows($(this));
        if ($(this).parent().hasClass('le_panel'))
        {
            $(this).parent().find(".required-field").remove();
            $(this).parent().remove();
        }
        else {
            $(this).parent().parent().find(".required-field").remove();
            $(this).parent().parent().remove();
        }

    });

    $("img[name='edit_panelname']").click(function () {
        var panelTitle = $(this).parent().find("span.panel_name").html();
       
        var panel_Lbl = '';
        if (panelTitle.indexOf("New Panel") > -1) {
            panel_Lbl = "";
        }
        else {
            panel_Lbl = panelTitle;
        }
        
        var panelInput = $('<input class="panel_title" type="text" id="panel" value="' + panel_Lbl + '" placeholder="Enter Panel Name">').on("blur", function (e) {
           
            
            var value = $(this).val();
            
            if (value.length == 0) {
                $(this).parent().siblings("img[name='edit_panelname']").show();
                $(this).parent().html(panelTitle);
            }
            var regex = new RegExp("^[a-zA-Z0-9_ ]*$");
            var key = $(this).val();
            if (!regex.test(key)) {
                alert("Please use only alphanumeric or alphabetic characters");
                $("input[name='save']").attr('disabled', true);
                return false;
            }
            else {
                $(this).parent().siblings("img[name='edit_panelname']").show();
                $(this).parent().html($(this).val());
                $("input[name='save']").attr('disabled', false);
                return true;
            }
        })
                .keypress(function (event) {
                    if (event.keyCode == 13) {

                        var d = $("#panel").val();
                        if (d.length == 0) {
                            event.preventDefault();
                        }
                        else {
                            var value = $("#panel").val();
                            if (value.length == 0) {
                                $("#panel").val(panelTitle);
                            }
                            var regex = new RegExp("^[a-zA-Z0-9_ ]*$");
                            var key = $(this).val();
                            if (!regex.test(key)) {
                                alert("Please use only alphanumeric or alphabetic characters");
                                $("input[name='save']").attr('disabled', true);
                                return false;
                            }
                            else {
                                $(this).parent().siblings("img[name='edit_panelname']").show();
                                $(this).parent().html($(this).val());
                                $("input[name='save']").attr('disabled', false);
                                return true;
                            }
                        }
                    }
        });
        $(this).parent().find("span.panel_name").html('');
        $(this).parent().find("span.panel_name").append(panelInput);
        $(this).parent().find("input.panel_title").focus();
        $(this).hide();
    });
});

var editPanelProperties = function (panelId)
{
    panelId = "" + panelId;
    var key_label = document.getElementById('le_panelid_' + panelId).innerHTML.replace(/^\s+|\s+$/g, '');
    var value_label = document.getElementById('le_panelname_' + panelId).innerHTML.replace(/^\s+|\s+$/g, '');
    var params = "module=ModuleBuilder&action=editProperty&view_module=" + encodeURIComponent(ModuleBuilder.module)
            + (ModuleBuilder.package ? "&view_package=" + encodeURIComponent(ModuleBuilder.package) : "")
            + "&view=" + encodeURIComponent(view) + "&id_label=le_panelname_" + encodeURIComponent(panelId) + "&name_label=label_" + encodeURIComponent(key_label.toUpperCase())
            + "&title_label=" + encodeURIComponent(SUGAR.language.get("ModuleBuilder", "LBL_LABEL_TITLE")) + "&value_label=" + encodeURIComponent(value_label);
    ModuleBuilder.getContent(params);
};

function removeThis(parElem) {
    removeFields($(parElem).parent('div'));
    $(this).parent().parent().remove();
}
// Start -- call function for require field
function requireThis(parElem) {
    
    if ($(parElem).is(":checked")) {
        $(parElem).parent('div');
        $(parElem).parent('div').find('.field_required').html('true');
        $(parElem).parent('div').find('.required-field').attr('checked' , true); 
        
    }
    else {
        $(parElem).parent('div');
        $(parElem).parent('div').find('.field_required').html('false');
        $(parElem).parent('div').find('.required-field').attr('checked' , false); 
    }
}
// End --
function removeFields(ele) {
    
    $(ele).find('button').remove();
    // Start--Remove From Layout , Required false when drop field and checkbox remove
    if ($(ele).find('.default_require').length == '0') {
        $(ele).find('.field_required').html('false');
    }
     $(ele).find(".required-field").remove();
    // End
    $(ele).find('div:not([class])').remove();
    var elehtml = $(ele).closest('div').html();
    var divhtml = "<div data-fhirq-type=\"question\" class=\"le_field field-div ui-state-default ui-draggable\" style=\"z-index: 99999;\"><div class='field'>" + elehtml + "</div></div>";
    $('#fieldlist').append(divhtml);
    $("#fieldlist > .le_field").draggable({
        connectToSortable: '.sortable-ul',
        revert: 'invalid'
    });
    $(".field ui-draggable ui-draggable-handle").find('.field_label').remove();
    $(ele).parent('div').html('<div><span>(filler)</span><span class="field_name">(filler)</span></div>');
}

function removeRows(ele) {

    if ($(ele).parent().hasClass('le_panel')) {
        var child_element = $(ele).parent().find('div > .le_field');
    } else {
        var child_element = $(ele).parent().parent().find('div > .le_field');
    }

    $.each(child_element, function (key, value) {
        if ($(value).find('div').hasClass('field')) {
            $(value).find('div').removeClass('ui-draggable');
            $(value).find('button').remove();
            var field_html = $(value).html();
            var divhtml = "<div data-fhirq-type=\"question\" class=\"le_field field-div ui-state-default ui-draggable\" style=\"z-index: 99999;\">" + field_html + "</div>";
            $('#fieldlist').append(divhtml);
            $("#fieldlist > .le_field").draggable({
                connectToSortable: '.sortable-ul',
                revert: 'invalid'
            });
        }
    });
}

function removeListElement(ele) {
    var t = $(ele).closest('div');
    $(t).addClass('field-div');
    $(t).addClass('le_field');
    $(t).addClass('field-div');
    $(t).addClass('ui-state-default');
    $(t).addClass('draggable');
    $(t).addClass('ui-draggable');
    $(t).removeClass('sel-field-div');
    $(t).find('button').remove();
    $(t).css('position', 'relative');
    $('#fieldlist').append($(t));
    $(t).draggable({
        handle: "div",
        appendTo: "body",
        connectToSortable: '.sortable-ul',
        revert: 'invalid'
    });
}
function get_fields(module, layout_type) {
    var layout_modifiedVal = $('#layout_modified').val();
    var confirmBox = true;
    if (layout_modifiedVal == '1') {
        confirmBox = confirm('You have changed layout. Are you sure you want to move without saving ?');
    }
    if (confirmBox) {
        $('body').append('<div id="backgroundpopup"  style="background:none repeat scroll 0 0 #000000; opacity: 0.05;z-index: 999;display: none;position: fixed;top: 0;left: 0;right: 0;bottom: 0;">&nbsp;</div>');
        $('.list-layout-block').css('display', 'block');
        $('#fieldlist').html('<img src="index.php?entryPoint=getImage&themeName=default&imageName=loading.gif" border="0" />');
        $('#layout_type').val(layout_type);
        $('#fieldlayout').remove();
        $('#backgroundpopup').fadeIn();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {'module': 'Administration', 'action': 'portalHandler', 'method': 'getModuleFields', 'module_name': module, 'layout_type': layout_type},
            success: function (data) {

                $('#fieldlist').html('');
                var field_defs = $.parseJSON(data);
                var counter = 1;
                var html = '<div data-fhirq-type=\"group\" class=\"le_panel_portal special new-panel\" style=\"display: block;\"><span class=\"panel_name\">New Panel</span><span class=\"panel_id\">lbl_portal_editview_panel</span></div>';
                html += '<div data-fhirq-type=\"group\" class=\"le_row special new-row\" style=\"display: block;\"><div><span class=\"panel_name\" style=\"width:100%\">New Row</span><div class=\"le_field special  filter-div droppable\" id=\"1014\" style=\"float: left;clear: none;\"><div><span>(filler)</span><span class=\"field_name\">(filler)</span></div></div><div class=\"le_field special filter-div droppable\" id=\"1015\" style=\"float: left;clear: none;\"><div><span>(filler)</span><span class=\"field_name\">(filler)</span></div></div></div></div>';
                $.each(field_defs['allfields'], function (key, value) {
                    if (value.label_value != null)
                        html += '<div data-fhirq-type=\"question\" class=\"le_field field-div ui-state-default \" id=\"' + counter + '\" style=\"z-index: 9999;\"><div class=\'field\'><span id=\"le_label_' + counter + '\">' + value.label_value + '</span> <span class=\"field_name\">' + value.name + '</span> <span class=\"field_label\">' + value.vname + '</span> <span class=\"field_type\">' + value.type + '</span>  <span class=\"field_required\">' + value.required + '</span> <span class=\"field_options\">' + value.options + '</span>';
                   // Start-- If Default Required Field then add span of default require
                    if (value.default_require == 'true') {
                        html += '<span  class=\"default_require\" style=\"display: none;\">' + value.default_require + '</span>';
                    }
                  // End
                    if (value.type == 'relate')
                        html += '<span class=\"id_name\" style=\"display: none;\">' + value.id_name + '</span><span id=\"le_tabindex_' + counter + '\" class=\"field_tabindex\"></span></div></div>'
                     else
                        html += '<span id=\"le_tabindex_' + counter + '\" class=\"field_tabindex\"></span></div></div>'
                     
                    counter++;
                });
                var html1 = '';
                if (field_defs['setfields']) {
                    var panel_c = field_defs['setfields']['panel_index']
                }
                else {
                    var panel_c = 0;
                }
                html1 += "<span id='sync_to_detailview' style='float: left;'><input type='hidden' id='panel_seq' value=" + panel_c + "></span>";

                if (field_defs['setfields']) {
                    $.each(field_defs['setfields']['module'], function (panel_no, panel) {
                        var panel_id = panel.panel_id.toLowerCase();
                        html1 += '<div data-fhirq-type=\"group\" class=\"special ui-draggable added-new-panel le_panel editor-group field-container ui-droppable ui-sortable\" style=\"display: block;\">';
                        html1 += '<span class=\"panel_name\" id=\"le_panelname_' + panel_id + '\">' + panel.panel_name + '</span>';
                        html1 += '<img name=\"edit_panelname\" src=\"index.php?entryPoint=getImage&themeName=RacerX&imageName=edit_inline.gif\" style=\"cursor:pointer;\" ><button class=\"remove-choice\" style=\"float:right;\">x</button><span class=\"panel_id\">' + panel_id + '</span>';

                        $.each(panel.rows, function (row_no, row) {
                            html1 += '<div class=\"special ui-draggable added-new-row le_row_panel editor-group\" style=\"display: block;\"><div>';
                            $.each(row, function (key, value) {
                                if (value.label_value != null) {
                                   // var options = [];
//                                    $.each(value.options, function (key, value) {
//                                        $.each(this, function (key, value) {
//                                            options.push(value);
//                                        });
//                                    });
                                    html1 += '<div class=\"le_field special  filter-div droppable ui-draggable fc-inner ui-droppable\" id=\"1014\" style=\"float: left; clear: none; display: inline-block;\">';
                                    html1 += '<div class=\"field ui-draggable\" style=\"position: relative;\"><span id=\"le_label_1\">' + value.label_value + '</span> <span class=\"field_name\">' + value.name + '</span> <span class=\"field_label\">' + value.label + '</span> <span class=\"field_type\">' + value.type + '</span>  <span class=\"field_required\">' + value.required + '</span> <span class=\"field_options\">' + value.options + '</span>';
                                    if(value.type == 'relate'){
                                        html1 += '<span class=\"id_name\" style=\"display:none;\">' + value.id_name + '</span>';
                                    }
                                    else{
                                        html1 += '<span id=\"le_tabindex_1\" class=\"field_tabindex\"></span>';
                                    }
                                  // Start -- If Fields required by sugar default then disable and checked otherwise custom require then only checked
                                    if ((value.default_require == 'true')) {
                                        html1 += '<span  class=\"default_require\" style=\"display: none;\">' + value.default_require + '</span><input type="checkbox" class="required-field" name="required-field" style="float:left;" onclick="requireThis(this);" checked disabled>';
                                    } else if (value.required == 'true') {
                                        html1 += '<input type="checkbox" class="required-field" name="required-field" style="float:left;" onclick="requireThis(this);" checked>';
                                    }
                                    else {
                                        html1 += '<input type="checkbox" class="required-field" name="required-field" style="float:left;" onclick="requireThis(this);">';
                                    }
                                 // End 
                                    html1 += '<button class=\"remove-field\" onclick=\"removeThis(this);\">x</button></div></div>';
                                    counter++;
                                } else {
                                    html1 += '<div class=\"le_field special  filter-div droppable ui-draggable fc-inner ui-droppable\" id=\"1014\" style=\"float: left; clear: none; display: inline-block;\">';
                                    html1 += '<div><span>(filler)</span><span class=\"field_name\">(filler)</span></div>';
                                    html1 += '</div>';
                                }
                            });
                            html1 += '<button class=\"remove-choice\" style=\"float:right;\">x</button></div></div>';
                        });
                        html1 += '</div>';
                    });
                }
                $('#fieldlist').append(html);
                if (layout_type == 'edit')
                    var display_layout = 'Edit View';
                else
                    var display_layout = 'Detail View';
                $('#fieldlist').after('<div id=\"fieldlayout\" class=\"layout_div\"><input type=\"hidden\" id=\"layout_modified\" value=\"0\" /><div class=\"panel_name\" style=\"width: 97%;\"><span style=\"float:right;width:100%;text-align: center;\">' + display_layout + ' Layout</span></div><div id=\"divNewRow\" style=\"min-height: 500px;margin-top: 38px;width: 100%;\">' + html1 + '</div></div></form>');
                $.getScript("custom/biz/js/layout-set.js");
                $('#divNewRow > div > .le_row_panel > div >.le_field >.field').draggable({revert: 'invalid'});
                // for Edit layout add sync to detailview checkbox.
                if (layout_type == 'edit')
                {
                    var syncCheckbox = $('<span id="sync_to_detailview" style="float: left;"><input type="checkbox" id="syncCheckbox" onclick="confirmSynx(this)">&nbsp;Copy to DetailView&nbsp;</span>');
                    $("#fieldlayout > .panel_name").append(syncCheckbox);
                }
                $('#backgroundpopup').fadeOut();
                $('#backgroundpopup').remove();
            }
        });
    }
}

function confirmSynx(el) {
    if (el.checked) {
        alert("This option sync Portal's EditView layout to the corresponding DetailView layout.");
    }
}

function get_list_fields(module_label,module, view_type) {
    var layout_modifiedVal = $('#layout_modified').val();
    var confirmBox = true;
    if (layout_modifiedVal == '1') {
        confirmBox = confirm('You have changed layout. Are you sure you want to move without saving ?');
    }
    if (confirmBox) {
        $('body').append('<div id="backgroundpopup"  style="background:none repeat scroll 0 0 #000000; opacity: 0.05;z-index: 999;display: none;position: fixed;top: 0;left: 0;right: 0;bottom: 0;">&nbsp;</div>');
        $('#fieldlist').html('<img src="index.php?entryPoint=getImage&themeName=default&imageName=loading.gif" border="0" />');
        $('.list-layout-block').css('display', 'block');
        $('#layout_type').val('list');
        $('#fieldlayout').remove();
        $('#backgroundpopup').fadeIn();
        $.ajax({
            url: 'index.php',
            type: 'POST',
            data: {'module': 'Administration', 'action': 'portalHandler', 'method': 'getModuleFields', 'module_name': module, 'layout_type': view_type},
            success: function (data) {

                $('#fieldlist').html('');
                var field_defs = $.parseJSON(data);
                var counter = 1;
                var html = '<div class=\"le_row special\" id=\"1013\" style=\"display: block;\"><span class=\"panel_name\" style=\"text-align: center;\">' + module_label + ' Fields</span><div class=\"le_field special\" id=\"1014\"><div><span>(filler)</span><span class=\"field_name\">(filler)</span></div></div><div class=\"le_field special\" id=\"1015\"><div><span>(filler)</span><span class=\"field_name\">(filler)</span></div></div></div>';
                $.each(field_defs['allfields'], function (key, value) {
                    if (value.label_value != null)
                        html += '<div class=\"le_field field-div ui-state-default draggable_list\" id=\"' + counter + '\"><div><span id=\"le_label_' + counter + '\">' + value.label_value + '</span> <span class=\"field_name\">' + value.name + '</span><span class=\"field_type\">' + value.type + '</span> <span class=\"field_label\">' + value.vname + '</span>';
                        if(value.type == 'relate')
                        html += '<span class=\"id_name\" style=\"display:none;\">' + value.id_name + '</span><span id=\"le_tabindex_' + counter + '\" class=\"field_tabindex\"></span></div></div>';
                        else
                        html += '<span id=\"le_tabindex_' + counter + '\" class=\"field_tabindex\"></span></div></div>';
                    counter++;
                });
                var html1 = '';
                if (field_defs['setfields']) {
                    $.each(field_defs['setfields'], function (key, value) {
                        if (value.label_value != null) {
                            html1 += '<div class=\"le_field sel-field-div\" id=\"' + counter + '\"><div><span id=\"le_label_1\">' + value.label_value + '</span><span class=\"field_name\">' + value.name + '</span><span class=\"field_label\">' + value.vname + '</span><span class=\"field_type\">' + value.type + '</span><span class=\"field_required\">' + value.required + '</span> <span class=\"field_options\">' + value.options + '</span>';
                            if(value.type == 'relate')
                          html1 += '<span class=\"id_name\" style=\"display:none;\">' + value.id_name + '</span><span id=\"le_tabindex_1\" class=\"field_tabindex\"></span></div><button class=\"remove-field\" onclick=\"removeListElement(this);\">x</button></div>';
                             else
                          html1 += '<span id=\"le_tabindex_1\" class=\"field_tabindex\"></span></div><button class=\"remove-field\" onclick=\"removeListElement(this);\">x</button></div>';
                            counter++;
                        }
                    });
                }
                $('#fieldlist').append(html);
                $('#fieldlist').after('<div id=\"fieldlayout\" class=\"layout_div list\"><input type=\"hidden\" id=\"layout_modified\" value=\"0\" /><div class=\"panel_name\" style=\"width: 97%;position: relative;text-align: center;\">List View Layout</div><div id=\"droppable_list\" style=\"min-height: 500px;margin-top: 0;width: 100%;\">' + html1 + '</div></div></form>');
                $.getScript("custom/biz/js/layout-set.js");
                $('#backgroundpopup').fadeOut();
                $('#backgroundpopup').remove();
            }
        });
    }
}

function saveModulelayout() {
    var panel_index = $('#panel_seq').val();
    var layoutFieldsArray = new Array();
    var layout_type = $('#layout_type').val();
    var module = $('#modules').val();
    var exist_field = $('#fieldlayout').find('.remove-field').length;
    if (module != '' && exist_field != 0) {
        if (layout_type == 'list') {
            var gr_total = 0;
            $('#droppable_list').find('.le_field').find('div').each(function () {
                var field = $(this).find('.field_name').text().toUpperCase();
                var field_label = $(this).find('.field_label').text();
                var label_val = $(this).find("span[id^='le_label_']").text();
                var field_type = $(this).find('.field_type').text();
                var id_name = $(this).find('.id_name').text();
                       if ($(this).find('.field_required').text() != 'undefined') {
                            var field_required = $(this).find('.field_required').text();
                        }
                        else {
                            var field_required = 'false';
                        }
                        if ($(this).find('.field_options').text() != 'undefined') {
                            var field_options = $(this).find('.field_options').text();
                        }
                        else {
                            var field_options = '';
                        }
                        
                if(id_name != ''){        
                layoutFieldsArray.push({'name': field, 'label': field_label, 'default': true, 'label_value': label_val,'type': field_type ,'required': field_required, 'options': field_options,'id_name':id_name} );
                }
                else{
                layoutFieldsArray.push({'name': field, 'label': field_label, 'default': true, 'label_value': label_val,'type': field_type ,'required': field_required, 'options': field_options} );
                }            
                if(field == 'TOTAL_AMOUNT'){
                    gr_total = 1;
                }
            });
                if (!gr_total && module == 'AOS_Invoices') {
                    layoutFieldsArray.push({'name': 'TOTAL_AMOUNT', 'label': 'LBL_GRAND_TOTAL', 'default': true, 'label_value': 'Grand Total', 'type': 'currency', 'required': 'false', 'options': 'numeric_range_search_dom', 'id_name': ''});
            }
             var myjson = JSON.stringify(layoutFieldsArray);
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: {'module': 'Administration', 'action': 'portalHandler', 'method': 'saveListLayout', 'sel_module': module, 'layoutFieldsArray': myjson},
                success: function (data) {
                    $('#layout_modified').val('0');
                    alert('Changes saved successfully.');
                }
            });
        }
        if (layout_type == 'edit' || layout_type == 'detail') {
            var syncViews = 0;
            $('#divNewRow').find('.added-new-panel').each(function () {
                var row_panel = new Array();
                var parent_panel = this;
                var panel_name = $(parent_panel).find('.panel_name').html();
                var panel_id = $(parent_panel).find('.panel_id').html().toUpperCase();
                $(parent_panel).find('.added-new-row').each(function () {
                    var field_row = new Array();
                    var parent_this = this;
                    $(parent_this).find('.le_field').each(function () {
                        var field_label = $(this).find('.field_label').text();
                        var field = $(this).find('.field_name').text();
                        var field_type = $(this).find('.field_type').text();
                        var label_val = $(this).find("span[id^='le_label_']").text();
                         var id_name = $(this).find('.id_name').text();
                        if ($(this).find('.field_required').text() != 'undefined') {
                            var field_required = $(this).find('.field_required').text();
                        }
                        else {
                            var field_required = 'false';
                        }
                        if ($(this).find('.field_options').text() != 'undefined') {
                            var field_options = $(this).find('.field_options').text();
                        }
                        else {
                            var field_options = '';
                        }
                        if (field_label != '' && field != '') {
                            field_row.push({'name': field, 'label_value': label_val, 'label': field_label, 'type': field_type,'id_name' : id_name,'required': field_required, 'options': field_options});
                        } else {
                            field_row.push({'': ''});
                        }
                    });
                    row_panel.push(field_row);
                });
                layoutFieldsArray.push({'panel_name': panel_name, 'panel_id': panel_id, rows: row_panel});
            });
// Start -- save require feild and check validation
            var runProcess = true;
            var layoutReqFieldArray = new Array();
            var ContactlayoutReqFieldArray = new Array();
            $.each($('#fieldlayout').find('.special'), function () {

                $(this).find('.field').each(function () {
                    var field_name = $(this).find('.field_name').text();
                    var label = $(this).find('span:first').text();
                    var field_req = $(this).find('.field_required').text();
                    if (field_req == 'true') {
                        layoutReqFieldArray.push(label);
                    }

                    if ($('#modules').val() == "Contacts" && layout_type == 'edit') {
                        if (field_name == "username_c" || field_name == "password_c" || field_name == "email") {
                            if (field_req == 'false') {
                                ContactlayoutReqFieldArray.push(label);
                            }
                        }
                    }
                });

            });
            if (layoutReqFieldArray == '') {
                alert("Atleast One Feild must be required");
                runProcess = false;
            }
            if (ContactlayoutReqFieldArray != '') {
                alert("UserName,Password and Email Must Be Required For Contact Module");
                runProcess = false;
            }
            var layout_reqFieldsArray = new Array();
            $('#fieldlist').find('.le_field:visible').each(function () {
                $(this).find('.field').each(function () {
                    var label = $(this).find('span:first').text();
                    var field_name = $(this).find('.field_name').text();
                    var field_req = $(this).find('.field_required').text();
                    var default_require = $(this).find('.default_require').text();
                    if (field_req == 'true') {
                        if ($('#modules').val() != "Contacts") {
                            layout_reqFieldsArray.push(field_name);
                        }
                    }
                    if ($('#modules').val() == "Contacts" && layout_type == 'edit') {
                        if (field_name == "username_c" || field_name == "password_c" || field_name == "email1" || field_name == "last_name" || field_name == "email") {
                            layout_reqFieldsArray.push(label);

                        }
                    }


                });
            });

            if (typeof $("#syncCheckbox") !== 'undefined' && $("#syncCheckbox").is(':checked'))
            {
                syncViews = 1;
            }
            var missing_req_fields = layout_reqFieldsArray.join(',');
            if ($('#modules').val() != "Contacts") {
                if (layout_reqFieldsArray != '' && layoutReqFieldArray != '') {
                    if (!confirm('Are you sure you wish to continue? The following required fields are missing from the layout: ' + missing_req_fields)) {
                        runProcess = false;
                    }
                }
            }
            if (layoutReqFieldArray != '') {

                if (layout_reqFieldsArray != '' && $('#modules').val() == "Contacts") {
                    alert('The following required fields are missing from the layout: ' + missing_req_fields);
                    runProcess = false;
                }


            }
            var myjson = JSON.stringify(layoutFieldsArray);
           
            if (runProcess) {
                $.ajax({url: 'index.php',
                type: 'POST',
                    data: {'module': 'Administration', 'sel_module': module, 'action': 'portalHandler', 'method': 'saveEditLayout', 'layoutFieldsArray': myjson, 'layout_type': layout_type, 'is_synced': syncViews, 'panel_index': panel_index},
                success: function (data) {
                    $('#layout_modified').val('0');
                    alert('Changes saved successfully.');
                }
            });
        }
//            else {
            //                alert('The following required fields are missing from the layout: ' + missing_req_fields);
//            }
//End --
    }
    }
    else
    {
        alert('Please select module or select one field in layout.');
    }
}

function redirectToindex() {
    location.href = 'index.php?module=Administration&action=index';
}
