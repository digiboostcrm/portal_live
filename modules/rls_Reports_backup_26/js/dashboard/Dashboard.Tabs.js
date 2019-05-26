/**
 * This object is inteded for Tabs handling
 *
 * */
Dashboard.Tabs = {
    /**
     * Positions of dashlets on start of moving
     *
     * @var object
     */
    startMovePosition: {
        index: 0
    },

    /**
     * The value for maximum tabs count
     *
     * @var integer
     */
    maximumCount: 10,

    /**
     * Adding tab in Progress status
     *
     * @var boolean
     */
    addingInProgress: false,

    /**
     * Init tabs
     *
     */
    init: function () {
        $('#tabs').tabs({
            tabTemplate: '<li><a href="#{href}">#{label}</a> <span class="ui-icon ui-icon-close">Remove Tab</span></li>'
            , cookie: {
                expires: 7
            }
            , add: function (event, ui) {
            }
            , load: function () {
                Dashboard.Dashlets.refreshSequence();
                Dashboard.Dashlets.applySortable();
            }
            , ajaxOptions: {
                error: function (xhr, status, index, anchor) {
                    $(anchor.hash).html(
                        "Couldn't load this tab. We'll try to fix this as soon as possible."
                    );
                }
            }
            , cache: true
        })
            // Applying sortable function
            .find('.ui-tabs-nav')
            .sortable(
            Dashboard.Tabs.getSortableSettings()
        )
            .disableSelection();

        Dashboard.Tabs.refreshAddButton();
    },

    /**
     * Refreshing content for Tab
     *
     * @param string guid    The GUId for the Page
     */
    refreshContent: function (guid) {
        $('#tabs').tabs('load', $('li[alt=' + guid + ']').index());
    },

    /**
     * This method for adding new empty tab
     *
     * */
    addEmpty: function () {
        if (this.addingInProgress) {
            return false;
        }

        var tab_title = $('#tab_title').val();
        var $tabs = $('#tabs');
        this.addingInProgress = true;

        $.get(
            Dashboard.getUrl('DashboardAddEmptyTab')
            , function (response) {
                Dashboard.Tabs.addingInProgress = false;


                var jqueryUI_version = $.ui.version.split('.');
                if (Number(jqueryUI_version[0]) == 1 && Number(jqueryUI_version[1]) < 10) {
                    $tabs.tabs(
                        'add',
                        Dashboard.getUrl('DashboardGetTabContent', 'tab_guid=' + response.guid),
                        '',
                        $tabs.tabs('length') - 1
                    );
                    var $new_tab = $('ul li:not(#dashboard_add_tab)', $tabs).last();
                    $tabs.tabs('select', $new_tab.index());
                } else {
                    $("<li><a href='" + Dashboard.getUrl('DashboardGetTabContent', 'tab_guid=' + response.guid) + "'>" +
                    "</a><span class='ui-icon ui-icon-close'' alt='" + response.guid + "'>Remove Tab</span></li>")
                        .insertBefore("#dashboard_add_tab");
                    $("#tabs").tabs("refresh");

                    var new_row_index = $('#tabs >ul >li').size() - 2;
                    $tabs.tabs('option', 'active', new_row_index);
                    var $new_tab = $('ul li:not(#dashboard_add_tab)', $tabs).last();
                }


                $new_tab
                    .attr('alt', response.guid);
                Dashboard.Tabs.init();
                Dashboard.Tabs.setInputForName(response.guid);
            }
            , 'json'
        );
    },

    /**
     * Check the button for status disabled or enabled
     *
     */
    refreshAddButton: function () {
        var jqueryUI_version = $.ui.version.split('.');
        if (Number(jqueryUI_version[0]) == 1 && Number(jqueryUI_version[1]) < 10) {
            var tabs_length = $('#tabs ul li').length;
        } else {
            var tabs_length = $('#tabs >ul >li').size();
        }

        if (tabs_length >= this.maximumCount) {
            $('#tabs').tabs('disable', tabs_length - 1);
        } else if ($('#dashboard_add_tab').hasClass('ui-state-disabled')) {
            //$('#tabs').tabs('enable', 8); //FIXME: It's not works!
            $('#dashboard_add_tab').removeClass('ui-state-disabled');
        }
    },

    /**
     *
     */
    savePositions: function (parameters) {
        $.get(
            Dashboard.getUrl('DashboardSaveTabPosition')
            , {order: parameters}
            , function (response) {
                //Dashboard.Tabs.refreshContent(parameters.tab_guid);
            }
        );
    },

    /**
     * Remove Tab
     *
     * @param object tab_header    The DOM element for Tab header
     * */
    removeTab: function (tab_header) {
        var $tabs = $('#tabs');
        var guid = tab_header.attr('alt');
        var index = tab_header.index();

        var jqueryUI_version = $.ui.version.split('.');
        if (Number(jqueryUI_version[0]) == 1 && Number(jqueryUI_version[1]) < 10) {
            $tabs.tabs('select', index);
        } else {
            $tabs.tabs('option', 'active', index);
        }
        //


        if (confirm(SUGAR.language.get('rls_Dashboards', 'LBL_ARE_YOU_SURE'))) {
            //$tabs.tabs('remove', index ); // FIXME: Tabs BUG or WTF.. Anyway I'm tired...
            if (Number(jqueryUI_version[0]) == 1 && Number(jqueryUI_version[1]) < 10) {
                if (($tabs.tabs('length') - tab_header.index()) == 2) {
                    var selected_index = tab_header.prev().index();
                } else {
                    var selected_index = tab_header.next().index();
                }

                $tabs.tabs('select', selected_index);
            } else {
                if (($('#tabs >ul >li').size() - tab_header.index()) == 2) {
                    var selected_index = tab_header.prev().index();
                } else {
                    var selected_index = tab_header.next().index();
                }

                //$tabs.tabs('select', selected_index);
                $tabs.tabs('option', 'active', selected_index);
            }
            tab_header.remove();
            $('div#tabs-' + guid).remove();

            Dashboard.Tabs.init();

            $.get(
                Dashboard.getUrl('DashboardRemoveTab', 'tab_guid=' + guid)
            );
        }
    },

    /**
     * Place input for Tab naming
     *
     * @param string guid  The GUID valueof the tab
     * @param string default_value  The current value of the tab
     * */
    setInputForName: function (guid, default_value) {
        var link = $('li[alt=' + guid + '] a');

        if (default_value == undefined) {
            default_value = '';
        }

        link.hide();
        link.before('<input value="' + default_value + '" class="dashboards-input-for-tab-name" type="text" alt="' + guid + '" name="tab_cation[' + guid + ']" />');
        link.prev().focus();

        $('.dashboards-input-for-tab-name').on('blur keydown', function (e) {
            if (
                (e.which != undefined && e.which == '13')
                || (e.which != undefined && e.which == 0)
                || e.which == 0
                || e.which == undefined
            ) {
                Dashboard.Tabs.removeInputForName(guid);
                return false;
            }
        });
    },

    /**
     * Return the object for jQuery UI Sortable function
     *
     */
    getSortableSettings: function () {
        return {
            axis: 'x',
            items: "li:not(#dashboard_add_tab, :first-child)",
            delay: 300,
            cursor: 'move',
            start: function (event, ui) {
                Dashboard.Tabs.startMovePosition.index = $(ui.item).index();
            },
            stop: function (event, ui) {
                var new_index = $(ui.item).index();

                if (Dashboard.Tabs.startMovePosition.index != new_index) {
                    var tabs_positions = [];

                    $('#tabs ul li:not(#dashboard_add_tab)').each(function (index) {
                        tabs_positions.push({
                            tab_guid: $(this).attr('alt'),
                            index: index
                        });
                    });

                    Dashboard.Tabs.savePositions(tabs_positions);
                    Dashboard.Tabs.init();
                }
            }
        };
    },

    /**
     * Removes input for Tab naming
     *
     * @param string guid of the tab
     * */
    removeInputForName: function (guid) {
        var link = $('li[alt=' + guid + '] a');
        var input = link.prev();
        var value = input.val();

        if (value.length == 0) {
            value = 'no_caption';
        }

        $.get(
            Dashboard.getUrl('DashboardSetTabCaption')
            , {
                tab_guid: input.attr('alt'),
                value: value
            }
        );

        link.text(value);
        input.remove();
        link.show();
    }
};
