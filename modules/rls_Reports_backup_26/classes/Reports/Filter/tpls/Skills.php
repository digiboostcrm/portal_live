<div id='grid_<?php echo str_replace('-', '_', $field_guide); ?>'></div>

<input type="hidden" id='<?php echo str_replace('-', '_', $field_guide); ?>'
       name='<?php echo str_replace('-', '_', $field_guide); ?>'
       value='<?php echo $current_value ?>'></input>
<input type="hidden"
       id="filter_values-<?php echo $settings['control_name'] . '_'
           . $settings['field_guide']; ?>"
       name="wizard[DisplayFilters][<?php echo $settings['control_name'] . '_'
           . $settings['field_guide']; ?>][value]"
       value='<?php echo $current_value ?>'>
</input>

<input type="hidden"
       id='grid_data_<?php echo str_replace('-', '_', $field_guide); ?>'
       name='grid_data_<?php echo str_replace('-', '_',
           $field_guide); ?>'></input>

<input type="hidden"
       id='or_and_<?php echo str_replace('-', '_', $field_guide); ?>'
       name='or_and_<?php echo str_replace('-', '_', $field_guide); ?>'></input>
<input type="hidden"
       id='templ_id_<?php echo str_replace('-', '_', $field_guide); ?>'
       name='templ_id_<?php echo str_replace('-', '_',
           $field_guide); ?>'></input>

<link rel="stylesheet" href="custom/include/RLS/jqwidgets/styles/jqx.base.css"
      type="text/css"/>

<script type="text/javascript"
        src="custom/include/RLS/jqwidgets/jqx-all.js"></script>


<script type="text/javascript">
    var or_flag_<?php echo str_replace('-', '_', $field_guide); ?> = 0;
    var stored_val_<?php echo str_replace('-', '_', $field_guide); ?> = document.getElementById('<?php echo str_replace('-', '_', $field_guide); ?>').value;

    var data_fields_<?php echo str_replace('-', '_', $field_guide); ?> = [
        {name: 'id', type: 'string'},
        {name: 'parent_id', type: 'string'},
        {name: 'name', type: 'string'},
        {name: 'is_checked', type: 'string'}
    ];

    // prepare the data of ComboBox
    var source_ComboBox_<?php echo str_replace('-', '_', $field_guide); ?> =
    {
        datatype: "json",
        datafields: [
            {name: 'val'},
            {name: 'lab'}
        ],
        url: "index.php?module=RLS_Skills&action=getListSkills&to_pdf=1",
        async: false
    };

    var source_array_<?php echo str_replace('-', '_', $field_guide); ?> = [];
    var source_obj_<?php echo str_replace('-', '_', $field_guide); ?>;

    var dataAdapter_ComboBox_<?php echo str_replace('-', '_', $field_guide); ?> = new $.jqx.dataAdapter(source_ComboBox_<?php echo str_replace('-', '_', $field_guide); ?>, {
        autoBind: true,
        beforeLoadComplete: function (records) {
            $.each(records, function (i, v) {
                source_array_<?php echo str_replace('-', '_', $field_guide); ?>[v.val] = v.lab;
            });
            source_obj_<?php echo str_replace('-', '_', $field_guide); ?> = records;
        }
    });

    // RLS Vic 15.02.16 begin
    // prepare the data of Templates DropDown
    var source_Templ_DD_<?php echo str_replace('-', '_', $field_guide); ?> = {
        datatype: "json",
        url: "index.php?module=RLS_TemplateSkills&action=getTemplateList&to_pdf=1",
        //async: true,
        datafields: [
            {name: 'val'},
            {name: 'lab'},
            {name: 'tree'}
        ]
    };
    var dataAdapter_Templ_DD_<?php echo str_replace('-', '_', $field_guide); ?> = new $.jqx.dataAdapter(source_Templ_DD_<?php echo str_replace('-', '_', $field_guide); ?>);
    // end

    data_columns_<?php echo str_replace('-', '_', $field_guide); ?> = [
        {
            text: 'Name',
            dataField: "name",
            align: 'center',
            columnType: "template",

            createEditor: function (row, cellvalue, editor, cellText, width, height) {
                // construct the editor.
                editor.jqxComboBox({
                    source: source_obj_<?php echo str_replace('-', '_', $field_guide); ?>,
                    width: '100%',
                    height: '100%',
                    displayMember: "lab",
                    valueMember: "val"
                });
            },

            initEditor: function (row, cellvalue, editor, celltext, width, height) {
                // set the editor's current value. The callback is called each time the editor is displayed.
                editor.jqxComboBox('selectItem', cellvalue);
                //editor.jqxComboBox('val', cellvalue);
            },

            getEditorValue: function (row, cellvalue, editor) {
                // return the editor's value.
                if (editor.val() != '') return editor.val();
                return cellvalue;
            },

            cellsRenderer: function (row, column, value) {
                if (source_array_<?php echo str_replace('-', '_', $field_guide); ?>[value] != undefined) return source_array_<?php echo str_replace('-', '_', $field_guide); ?>[value];
                return value;
            }
        }
    ];

    var skills_params_<?php echo str_replace('-', '_', $field_guide); ?> = document.getElementById('<?php echo str_replace('-', '_', $field_guide); ?>').value;
    //console.log ('skills_params'); console.log (skills_params);


    var templateSkillsId_<?php echo str_replace('-', '_', $field_guide); ?> = '<?php echo $templateSkillsId; ?>';
    var dataTreeGrid_<?php echo str_replace('-', '_', $field_guide); ?> = '<?php echo $dataTreeGrid; ?>';
    var dataListBox_<?php echo str_replace('-', '_', $field_guide); ?> = '<?php echo $dataListBox; ?>';


    var work_status_<?php echo str_replace('-', '_', $field_guide); ?> = '';
    var show_toolbar = true;
    //if (templateSkillsId_<?php echo str_replace('-', '_', $field_guide); ?> && dataTreeGrid_<?php echo str_replace('-', '_', $field_guide); ?> && dataListBox_<?php echo str_replace('-', '_', $field_guide); ?>)
    //	work_status_<?php echo str_replace('-', '_', $field_guide); ?> = 'template';

    if (work_status_<?php echo str_replace('-', '_', $field_guide); ?> == 'template') {
        var col_def_str_<?php echo str_replace('-', '_', $field_guide); ?> = '<?php echo $dataListBox; ?>';
        var col_def_obj = JSON.parse (col_def_str_<?php echo str_replace('-', '_', $field_guide); ?>);
        //var col_def_<?php echo str_replace('-', '_', $field_guide); ?> = {};
        var col_def_<?php echo str_replace('-', '_', $field_guide); ?> = [];
        for (var i in col_def_obj) {
            if (col_def_obj[i].hidden || col_def_obj[i].name == 'name') continue;
            col_def_<?php echo str_replace('-', '_', $field_guide); ?>.push ({
                value: {
                    fildname: col_def_obj[i].name,
                    label: col_def_obj[i].label,
                    type: col_def_obj[i].val.type,
                    ext: col_def_obj[i].val.ext
                },
                label: col_def_obj[i].label
            });
        }
    }

    else {
        col_def_str_<?php echo str_replace('-', '_', $field_guide); ?> = '<?php echo $colDef; ?>';
        col_def_<?php echo str_replace('-', '_', $field_guide); ?> = col_def_str_<?php echo str_replace('-', '_', $field_guide); ?> ? JSON.parse (col_def_str_<?php echo str_replace('-', '_', $field_guide); ?>) : {};
    }


    var list_enum_<?php echo str_replace('-', '_', $field_guide); ?> = '<?php echo $listEnumJSON; ?>';
    if (list_enum_<?php echo str_replace('-', '_', $field_guide); ?>) {
        list_enum_<?php echo str_replace('-', '_', $field_guide); ?> = JSON.parse (list_enum_<?php echo str_replace('-', '_', $field_guide); ?>);
    }

    var list_enum_arr_<?php echo str_replace('-', '_', $field_guide); ?> = [];
    for (var i in list_enum_<?php echo str_replace('-', '_', $field_guide); ?>) {
        var f_name = list_enum_<?php echo str_replace('-', '_', $field_guide); ?>[i].fildname;
        list_enum_arr_<?php echo str_replace('-', '_', $field_guide); ?>[f_name] = [];
        for (var key in list_enum_<?php echo str_replace('-', '_', $field_guide); ?>[i].opt) {
            list_enum_arr_<?php echo str_replace('-', '_', $field_guide); ?>[f_name].push ({
                val: key,
                label: list_enum_<?php echo str_replace('-', '_', $field_guide); ?>[i].opt[key]
            });
        }
    }

    var col_num_<?php echo str_replace('-', '_', $field_guide); ?> = 3 + col_def_<?php echo str_replace('-', '_', $field_guide); ?>.length;
    var col_width_<?php echo str_replace('-', '_', $field_guide); ?> = 100.00 / col_num_<?php echo str_replace('-', '_', $field_guide); ?> + '%';

    var fields_names_<?php echo str_replace('-', '_', $field_guide); ?> = [];


    for (var i in col_def_<?php echo str_replace('-', '_', $field_guide); ?>) {
        if (typeof(col_def_<?php echo str_replace('-', '_', $field_guide); ?>[i]) == 'object') {
            var data_field = {};
            var data_column = {};
            switch (col_def_<?php echo str_replace('-', '_', $field_guide); ?>[i].value.type) {
                case 'date':

                    data_column = {
                        cellsFormat: 'yyyy-MM-dd',
                        columnType: "template",
                        createEditor: function (row, cellvalue, editor, cellText, width, height) {
                            editor.jqxDateTimeInput ({
                                width: '100%',
                                height: '100%',
                                formatString: "yyyy-MM-dd"
                            });
                        },

                        initEditor: function (row, cellvalue, editor, celltext, width, height) {
                            // set the editor's current value. The callback is called each time the editor is displayed.
                            if (cellvalue != undefined)
                                editor.jqxDateTimeInput('val', new Date(cellvalue));
                            else
                                editor.jqxDateTimeInput('val', new Date());
                        },

                        getEditorValue: function (row, cellvalue, editor) {
                            // return the editor's value.
                            return editor.val();
                        }
                    };
                    data_field.type = 'date';
                    break;

                case 'enum':

                    data_column = {
                        columnType: "template",

                        createEditor: function (row, cellvalue, editor, cellText, width, height) {
                            var source = list_enum_arr_<?php echo str_replace('-', '_', $field_guide); ?>[this.datafield]
                            console.log ('this.dataField');
                            console.log (this.datafield);
                            editor.jqxDropDownList({
                                selectedIndex: 0,
                                source: source,
                                width: '100%',
                                height: '100%',
                                checkboxes: true
                            });
                        },

                        initEditor: function (row, cellvalue, editor, celltext, width, height) {
                            // set the editor's current value. The callback is called each time the editor is displayed.
                            editor.jqxDropDownList('selectItem', cellvalue);
                        },

                        getEditorValue: function (row, cellvalue, editor) {
                            // return the editor's value.
                            return editor.val();
                        },

                        cellsRenderer: function (row, column, value) {
                            //return list_enum_arr_<?php echo str_replace('-', '_', $field_guide); ?>[value];
                            return value;
                        }

                    };

                    data_field.type = 'string';
                    break;

                case 'int':
                    data_field.type = 'int';
                    break;

                default:
                    data_field.type = 'string';
                    break;
            }

            data_column.text = col_def_<?php echo str_replace('-', '_', $field_guide); ?>[i].value.label;
            data_column.dataField = col_def_<?php echo str_replace('-', '_', $field_guide); ?>[i].value.fildname;
            data_column.align = 'center';
            data_column.width = (col_def_<?php echo str_replace('-', '_', $field_guide); ?>[i].value.fildname == 'name') ?
            3 * col_width_<?php echo str_replace('-', '_', $field_guide); ?> : col_width_<?php echo str_replace('-', '_', $field_guide); ?>;

            data_field.name = col_def_<?php echo str_replace('-', '_', $field_guide); ?>[i].value.fildname;

            data_fields_<?php echo str_replace('-', '_', $field_guide); ?>.push (data_field);
            data_columns_<?php echo str_replace('-', '_', $field_guide); ?>.push (data_column);

            fields_names_<?php echo str_replace('-', '_', $field_guide); ?>[i] = col_def_<?php echo str_replace('-', '_', $field_guide); ?>[i].value.fildname;
        }

    }


    var skills_fields_<?php echo str_replace('-', '_', $field_guide); ?> = fields_names_<?php echo str_replace('-', '_', $field_guide); ?>.join(',');

    var storedArr = [];
    var storedArr_<?php echo str_replace('-', '_', $field_guide); ?> = [];
    if (stored_val_<?php echo str_replace('-', '_', $field_guide); ?>) {
        storedArr = JSON.parse (stored_val_<?php echo str_replace('-', '_', $field_guide); ?>);
        storedArr_<?php echo str_replace('-', '_', $field_guide); ?> = processStoredNode(storedArr, null, fields_names_<?php echo str_replace('-', '_', $field_guide); ?>, []);
    }


    var newRowID_<?php echo str_replace('-', '_', $field_guide); ?> = null;
    // prepare the data
    var source_<?php echo str_replace('-', '_', $field_guide); ?> =
    {
        dataType: "json",
        dataFields: data_fields_<?php echo str_replace('-', '_', $field_guide); ?>,
        timeout: 10000,
        hierarchy: {
            keyDataField: {name: 'id'},
            parentDataField: {name: 'parent_id'}
            //root: 'records'
        },
        //id: 'id',
        localData: storedArr_<?php echo str_replace('-', '_', $field_guide); ?>,

        addRow: function (rowID, rowData, position, parentID, commit) {
            // synchronize with the server - send insert command
            // call commit with parameter true if the synchronization with the server is successful
            // and with parameter false if the synchronization failed.
            // you can pass additional argument to the commit callback which represents the new ID if it is generated from a DB.
            commit(true);
            newRowID_<?php echo str_replace('-', '_', $field_guide); ?> = rowID;
        },
        updateRow: function (rowID, rowData, commit) {
            // synchronize with the server - send update command
            // call commit with parameter true if the synchronization with the server is successful
            // and with parameter false if the synchronization failed.
            commit(true);
        },
        deleteRow: function (rowID, commit) {
            // synchronize with the server - send delete command
            // call commit with parameter true if the synchronization with the server is successful
            // and with parameter false if the synchronization failed.
            commit(true);
        }
    };


    var dataAdapter_<?php echo str_replace('-', '_', $field_guide); ?> = new $.jqx.dataAdapter(source_<?php echo str_replace('-', '_', $field_guide); ?>, {
        loadComplete: function () {
            // data is loaded.
        }
    });


    render_flag_<?php echo str_replace('-', '_', $field_guide); ?> = true;
    $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid(
        {
            //width: '100%',
            width: '800px',
            height: '200px',
            source: dataAdapter_<?php echo str_replace('-', '_', $field_guide); ?>,

            editable: true,
            sortable: true,
            checkboxes: true,

            showToolbar: true,
            showStatusbar: false,

            altRows: true,

            editSettings: {
                saveOnPageChange: true,
                saveOnBlur: true,
                saveOnSelectionChange: true,
                cancelOnEsc: true,
                saveOnEnter: true,
                editSingleCell: true, // RLS Vic
                editOnDoubleClick: true,
                editOnF2: true
            },

            ready: function () {
                var rows = $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('getRows');
                expend_node(rows);
                function expend_node(node) {
                    for (var i in node) {
                        if (node[i].is_checked == '1') $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('checkRow', node[i].uid);
                        if (node[i].records) {
                            $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('expandRow', node[i].uid);
                            expend_node(node[i].records);
                        }
                    }
                }

                setHidenInput_<?php echo str_replace('-', '_', $field_guide); ?>();
            },

            pagerButtonsCount: 8,
            toolbarHeight: 35,
            renderToolbar: function (toolBar) {
                if (!render_flag_<?php echo str_replace('-', '_', $field_guide); ?>) return; // RLS Vic
                var toTheme = function (className) {
                    //if (theme == "")
                    return className;
                    //return className + " " + className + "-" + theme;
                }
                // appends buttons to the status bar.
                var container = $("<div id='div_container_<?php echo str_replace('-', '_', $field_guide); ?>'; style='overflow: hidden; position: relative; height: 100%; width: 100%;'></div>");
                var buttonTemplate_add0Button = "<div id='add0Button_<?php echo str_replace('-', '_', $field_guide); ?>'; style='float: left; padding: 3px; margin: 2px;'><div id='add0_<?php echo str_replace('-', '_', $field_guide); ?>'; style='margin: 4px; width: 16px; height: 16px;'></div></div>";
                var buttonTemplate_addButton = "<div id='addButton_<?php echo str_replace('-', '_', $field_guide); ?>'; style='float: left; padding: 3px; margin: 2px;'><div id='add_<?php echo str_replace('-', '_', $field_guide); ?>'; style='margin: 4px; width: 16px; height: 16px;'></div></div>";
                var buttonTemplate_editButton = "<div id='editButton_<?php echo str_replace('-', '_', $field_guide); ?>'; style='float: left; padding: 3px; margin: 2px;'><div id='edit_<?php echo str_replace('-', '_', $field_guide); ?>'; style='margin: 4px; width: 16px; height: 16px;'></div></div>";
                var buttonTemplate_deleteButton = "<div id='deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>'; style='float: left; padding: 3px; margin: 2px;'><div id='delete_<?php echo str_replace('-', '_', $field_guide); ?>'; style='margin: 4px; width: 16px; height: 16px;'></div></div>";
                var buttonTemplate_cancelButton = "<div id='cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>'; style='float: left; padding: 3px; margin: 2px;'><div id='cancel_<?php echo str_replace('-', '_', $field_guide); ?>'; style='margin: 4px; width: 16px; height: 16px;'></div></div>";
                var buttonTemplate_updateButton = "<div id='updateButton_<?php echo str_replace('-', '_', $field_guide); ?>'; style='float: left; padding: 3px; margin: 2px;'><div id='update_<?php echo str_replace('-', '_', $field_guide); ?>'; style='margin: 4px; width: 16px; height: 16px;'></div></div>";

                //var buttonTemplate_orButton = "<div id='orButton_" + <?php echo str_replace('-', '_', $field_guide); ?> + "'; style='float: right; padding: 3px; margin: 2px;'><input type='checkbox' id='or_<?php echo str_replace('-', '_', $field_guide); ?>' name='or_<?php echo str_replace('-', '_', $field_guide); ?>'/><label for='payt2'>OR</label></div>";
                var buttonTemplate_orButton = "<div style='float: left; padding: 0px; margin: 4px 12px;'><div style='margin: 0px; width: 0px; height: 0px;'></div></div>";
                var ddTemplate_templateDropDown = "<div  style='float: left; padding: 0px; margin: 4px 12px;'><div style='margin: 0px; width: 0px; height: 0px;'></div></div>";

                var add0Button_<?php echo str_replace('-', '_', $field_guide); ?> = $(buttonTemplate_add0Button);
                var addButton_<?php echo str_replace('-', '_', $field_guide); ?> = $(buttonTemplate_addButton);
                var editButton_<?php echo str_replace('-', '_', $field_guide); ?> = $(buttonTemplate_editButton);
                var deleteButton_<?php echo str_replace('-', '_', $field_guide); ?> = $(buttonTemplate_deleteButton);
                var cancelButton_<?php echo str_replace('-', '_', $field_guide); ?> = $(buttonTemplate_cancelButton);
                var updateButton_<?php echo str_replace('-', '_', $field_guide); ?> = $(buttonTemplate_updateButton);
                var orButton_<?php echo str_replace('-', '_', $field_guide); ?> = $(buttonTemplate_orButton);
                var templateDropDown_<?php echo str_replace('-', '_', $field_guide); ?> = $(ddTemplate_templateDropDown);

                container.append(add0Button_<?php echo str_replace('-', '_', $field_guide); ?>);
                //container.append(addButton_<?php echo str_replace('-', '_', $field_guide); ?>);
                container.append(editButton_<?php echo str_replace('-', '_', $field_guide); ?>);
                container.append(deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>);
                container.append(cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>);
                container.append(updateButton_<?php echo str_replace('-', '_', $field_guide); ?>);
                container.append(orButton_<?php echo str_replace('-', '_', $field_guide); ?>);
                container.append(templateDropDown_<?php echo str_replace('-', '_', $field_guide); ?>);
                toolBar.append(container);

                add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({
                    cursor: "pointer",
                    enableDefault: false,
                    disabled: false,
                    height: 25,
                    width: 25
                });
                add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.find('div:first').addClass(toTheme('jqx-menu-minimized-button'));
                add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.jqxTooltip({
                    position: 'bottom',
                    content: "Add to the Main level"
                });

                addButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({
                    cursor: "pointer",
                    enableDefault: false,
                    disabled: true,
                    height: 25,
                    width: 25
                });
                addButton_<?php echo str_replace('-', '_', $field_guide); ?>.find('div:first').addClass(toTheme('jqx-icon-plus'));
                addButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxTooltip({
                    position: 'bottom',
                    content: "Add"
                });

                editButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({
                    cursor: "pointer",
                    disabled: true,
                    enableDefault: false,
                    height: 25,
                    width: 25
                });
                editButton_<?php echo str_replace('-', '_', $field_guide); ?>.find('div:first').addClass(toTheme('jqx-icon-edit'));
                editButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxTooltip({
                    position: 'bottom',
                    content: "Edit"
                });

                deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({
                    cursor: "pointer",
                    disabled: true,
                    enableDefault: false,
                    height: 25,
                    width: 25
                });
                deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.find('div:first').addClass(toTheme('jqx-icon-delete'));
                deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxTooltip({
                    position: 'bottom',
                    content: "Delete"
                });

                updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({
                    cursor: "pointer",
                    disabled: true,
                    enableDefault: false,
                    height: 25,
                    width: 25
                });
                updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.find('div:first').addClass(toTheme('jqx-icon-save'));
                updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxTooltip({
                    position: 'bottom',
                    content: "Save Changes"
                });

                cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({
                    cursor: "pointer",
                    disabled: true,
                    enableDefault: false,
                    height: 25,
                    width: 25
                });
                cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.find('div:first').addClass(toTheme('jqx-icon-cancel'));
                cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxTooltip({
                    position: 'bottom',
                    content: "Cancel"
                });

                orButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxSwitchButton ({
                    height: 24,
                    width: 64,
                    checked: false,
                    onLabel: 'OR',
                    offLabel: 'AND'
                });
                orButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxTooltip ({
                    position: 'bottom',
                    content: "Select OR / AND logic"
                });
                orButton_<?php echo str_replace('-', '_', $field_guide); ?>.on ('change', function (event) {
                    document.getElementById ('or_and_<?php echo str_replace('-', '_', $field_guide); ?>').value = event.args.checked ? 'or' : 'and';
                    setHidenInput_<?php echo str_replace('-', '_', $field_guide); ?>();
                });
                if (or_flag_<?php echo str_replace('-', '_', $field_guide); ?>) orButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxSwitchButton ('check');

                templateDropDown_<?php echo str_replace('-', '_', $field_guide); ?>.jqxDropDownList ({
                    source: dataAdapter_Templ_DD_<?php echo str_replace('-', '_', $field_guide); ?>,
                    selectedIndex: -1,
                    placeHolder: 'Template:',
                    width: '200',
                    height: '25',
                    displayMember: "lab",
                    valueMember: "val"
                });
                templateDropDown_<?php echo str_replace('-', '_', $field_guide); ?>.on ('change', function (event) {
                    document.getElementById ('templ_id_<?php echo str_replace('-', '_', $field_guide); ?>').value = event.args.item.originalItem.val;

                    if (event.args.item.originalItem.tree) {
                        storedArr = JSON.parse (html_entity_decode(event.args.item.originalItem.tree));
                        //storedArr = (event.args.item.originalItem.tree);
                    }

                    storedArr_<?php echo str_replace('-', '_', $field_guide); ?> = processStoredNode(storedArr, null, fields_names_<?php echo str_replace('-', '_', $field_guide); ?>, []);

                    source_<?php echo str_replace('-', '_', $field_guide); ?>.localdata = storedArr_<?php echo str_replace('-', '_', $field_guide); ?>;


                    //source_<?php echo str_replace('-', '_', $field_guide); ?>.localdata = storedArr;
                    render_flag_<?php echo str_replace('-', '_', $field_guide); ?> = false;
                    $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid ('updateBoundData');
                    render_flag_<?php echo str_replace('-', '_', $field_guide); ?> = true;
                    setHidenInput_<?php echo str_replace('-', '_', $field_guide); ?>();
                });


                var updateButtons_<?php echo str_replace('-', '_', $field_guide); ?> = function (action) {
                    switch (action) {
                        case "Select":
                            addButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            editButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            break;
                        case "Unselect":
                            addButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            //add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            editButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            break;
                        case "Edit":
                            addButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            editButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            break;
                        case "End Edit":
                            addButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            editButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: false});
                            cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                            break;
                    }
                    addButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton({disabled: true});
                    setHidenInput_<?php echo str_replace('-', '_', $field_guide); ?>();
                }


                var rowKey = null;
                $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').on('rowSelect', function (event) {
                    var args = event.args;
                    rowKey = args.key;
                    updateButtons_<?php echo str_replace('-', '_', $field_guide); ?>('Select');
                });
                $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').on('rowUnselect', function (event) {
                    updateButtons_<?php echo str_replace('-', '_', $field_guide); ?>('Unselect');
                });
                $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').on('rowEndEdit', function (event) {
                    updateButtons_<?php echo str_replace('-', '_', $field_guide); ?>('End Edit');
                });
                $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').on('rowBeginEdit', function (event) {
                    updateButtons_<?php echo str_replace('-', '_', $field_guide); ?>('Edit');
                });

                $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').on('rowCheck', function (event) {
                    setHidenInput_<?php echo str_replace('-', '_', $field_guide); ?>();
                });
                $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').on('rowUncheck', function (event) {
                    setHidenInput_<?php echo str_replace('-', '_', $field_guide); ?>();
                });


                add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.click(function (event) {
                    if (!add0Button_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton('disabled')) {
                        // add new empty row.
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('addRow', null, {}, 'last', null);
                        // select the first row and clear the selection.
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('clearSelection');
                        updateButtons_<?php echo str_replace('-', '_', $field_guide); ?>('add');
                    }
                });

                addButton_<?php echo str_replace('-', '_', $field_guide); ?>.click(function (event) {
                    if (!addButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton('disabled')) {
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('expandRow', rowKey);
                        // add new empty row.
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('addRow', null, {}, 'last', rowKey);
                        // select the first row and clear the selection.
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('clearSelection');
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('selectRow', newRowID_<?php echo str_replace('-', '_', $field_guide); ?>);
                        // edit the new row.
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('beginRowEdit', newRowID_<?php echo str_replace('-', '_', $field_guide); ?>);
                        updateButtons_<?php echo str_replace('-', '_', $field_guide); ?>('add');
                    }
                });

                cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.click(function (event) {
                    if (!cancelButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton('disabled')) {
                        // cancel changes.
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('endRowEdit', rowKey, true);
                    }
                });

                updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.click(function (event) {
                    if (!updateButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton('disabled')) {
                        // save changes.
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('endRowEdit', rowKey, false);
                    }
                });

                editButton_<?php echo str_replace('-', '_', $field_guide); ?>.click(function () {
                    if (!editButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton('disabled')) {
                        $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('beginRowEdit', rowKey);
                        updateButtons_<?php echo str_replace('-', '_', $field_guide); ?>('edit');
                    }
                });

                deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.click(function () {
                    if (!deleteButton_<?php echo str_replace('-', '_', $field_guide); ?>.jqxButton('disabled')) {
                        var selection = $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('getSelection');
                        if (selection.length > 1) {
                            var keys = new Array();
                            for (var i = 0; i < selection.length; i++) {
                                keys.push($('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('getKey', selection[i]));
                            }
                            $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('deleteRow', keys);
                        } else {
                            $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('deleteRow', rowKey);
                        }
                        updateButtons_<?php echo str_replace('-', '_', $field_guide); ?>('delete');
                    }
                });

            }, // renderToolbar

            columns: data_columns_<?php echo str_replace('-', '_', $field_guide); ?>

        }); // grid


    function setHidenInput_<?php echo str_replace('-', '_', $field_guide); ?>() {
        var rows = $('#grid_<?php echo str_replace('-', '_', $field_guide); ?>').jqxTreeGrid('getRows');

        var cache = [];
        var rowsJson = JSON.stringify(rows, function (key, value) {
            if (typeof value === 'object' && value !== null) {
                if (cache.indexOf(value) !== -1) {
                    return; // Circular reference found, discard key
                }
                value.checkedBtn = document.getElementById ('or_and_<?php echo str_replace('-', '_', $field_guide); ?>').value;
                value.templateValue = $("#templ_id_<?php echo str_replace('-', '_', $field_guide); ?>").val();
                cache.push(value); // Store value in our collection

            }
            return value;
        });
        cache = null; // Enable garbage collection

        document.getElementById('grid_data_<?php echo str_replace('-', '_', $field_guide); ?>').value = rowsJson;
        document.getElementById('<?php echo str_replace('-', '_', $field_guide); ?>').value = rowsJson;
        document.getElementById('filter_values-<?php echo $settings['control_name'] . '_' . $settings['field_guide']; ?>').value = rowsJson;
    }


    function processNode(arr, parent_id, checked_arr, fields, result) {
        for (var i in arr) {
            var skill = {
                id: arr[i].name,
                parent_id: parent_id,
                name: arr[i].name
            };
            var checked = '';
            for (var j in checked_arr) {
                if (arr[i].name === checked_arr[j].name) {
                    skill.is_checked = '1';
                    for (var k in fields) {
                        skill[fields[k]] = checked_arr[j][fields[k]];
                    }
                }
            }

            result.push (skill);

            if (arr[i].records) {
                result = processNode(arr[i].records, arr[i].name, checked_arr, fields, result);
            }
        }
        return (result);
    }

    function processStoredNode(arr, parent_id, fields, result) {
        for (var i in arr) {
            if (typeof(arr[i]) == 'object') {
                var skill = {
                    id: arr[i].uid,
                    parent_id: parent_id,
                    name: arr[i].name
                };
                skill.is_checked = arr[i].checked ? '1' : '0';
                for (var k in fields) {
                    skill[fields[k]] = arr[i][fields[k]];
                }
                if (arr[i].checkedBtn == 'or') or_flag_<?php echo str_replace('-', '_', $field_guide); ?>++;
                result.push (skill);

                if (arr[i].records) {
                    result = processStoredNode(arr[i].records, arr[i].uid, fields, result);
                }
            }

        }

        return (result);
    }

</script>
