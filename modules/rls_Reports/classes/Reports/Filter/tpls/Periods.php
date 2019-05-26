<div style="display:none;width:150px;" id="<?php echo $control_name . '_' . $field_guide;?>-date_closed_range_div">
    <input type="text" style="width:100px !important;" size="11" title=""
            value="<?php echo $this->ranges['range_date_closed']['to_display']?>"
            id="<?php echo $control_name . '_' . $field_guide;?>-range_date_closed"
            name="wizard[DisplayFilters][<?php echo $control_name . '_' . $field_guide; ?>][value][range_date][<?php echo $control_name . '_' . $field_guide;?>-range_date_closed]" autocomplete="off">

    <img border="0" id="<?php echo $control_name . '_' . $field_guide;?>-date_closed_trigger" absmiddle="" src="<?php echo SugarThemeRegistry::current()->getImageURL('jscalendar.gif')?>">
    <script type="text/javascript">
        Calendar.setup ({
            inputField : "<?php echo $control_name . '_' . $field_guide;?>-range_date_closed",
            daFormat : "<?php echo $timedate->get_cal_date_format()?> %H:%M",
            button : "<?php echo $control_name . '_' . $field_guide;?>-date_closed_trigger",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
        });
    </script>
</div>

<div style="display:none;width:300px;" id="<?php echo $control_name . '_' . $field_guide;?>-date_closed_between_range_div">
    <input type="text" style="width:100px !important;" size="11" tabindex="" title=""
           value="<?php echo $this->ranges['start_range_date_closed']['to_display']?>"
           id="<?php echo $control_name . '_' . $field_guide;?>-start_range_date_closed"
           name="wizard[DisplayFilters][<?php echo $control_name . '_' . $field_guide; ?>][value][range_date][<?php echo $control_name . '_' . $field_guide;?>-start_range_date_closed]" autocomplete="off">
    <img border="0" id="<?php echo $control_name . '_' . $field_guide;?>-start_range_date_closed_trigger" absmiddle="" src="<?php echo SugarThemeRegistry::current()->getImageURL('jscalendar.gif')?>">
    <script type="text/javascript">
        Calendar.setup ({
            inputField : "<?php echo $control_name . '_' . $field_guide;?>-start_range_date_closed",
            daFormat : "<?php echo $timedate->get_cal_date_format()?> %H:%M",
            button : "<?php echo $control_name . '_' . $field_guide;?>-start_range_date_closed_trigger",
            singleClick : true,
            dateStr : "",
            step : 1,
            weekNumbers:false
        });
    </script>

    <?php echo $app_strings['LBL_AND']?>

    <input type="text" maxlength="10" style="width:100px !important;" size="11" tabindex="" title=""
           value="<?php echo $this->ranges['end_range_date_closed']['to_display']?>"
           id="<?php echo $control_name . '_' . $field_guide;?>-end_range_date_closed"
           name="wizard[DisplayFilters][<?php echo $control_name . '_' . $field_guide; ?>][value][range_date][<?php echo $control_name . '_' . $field_guide;?>-end_range_date_closed]" autocomplete="off">
    <img border="0" id="<?php echo $control_name . '_' . $field_guide;?>-end_range_date_closed_trigger" absmiddle="" src="<?php echo SugarThemeRegistry::current()->getImageURL('jscalendar.gif')?>">
    <script type="text/javascript">
        Calendar.setup ({
            inputField : "<?php echo $control_name . '_' . $field_guide;?>-end_range_date_closed",
            daFormat : "<?php echo $timedate->get_cal_date_format()?> %H:%M",
            button : "<?php echo $control_name . '_' . $field_guide;?>-end_range_date_closed_trigger",
            singleClick : true,
            dateStr : "",
            step : 1,
            weekNumbers:false
        });
    </script>
</div>


<script type="text/javascript">

    YAHOO.util.Event.onDOMReady(function(){
        checkDateClosed_<?php echo $func_key;?>();
        YAHOO.util.Event.on('filter_values-<?php echo $control_name . '_' . $field_guide;?>', 'change', function(){
        //$("#filter_values-sbr_periods_filter").bind("change", function(){
            checkDateClosed_<?php echo $func_key;?>();
        });/**/
    });

    /**
     * Display or hide date fields, according with dropdown type.
     *
     **/
    function checkDateClosed_<?php echo $func_key;?>()
    {
        //$("#date_closed_range_div").hide();
        //$("#date_closed_between_range_div").hide();
        YAHOO.util.Dom.setStyle('<?php echo $control_name . '_' . $field_guide;?>-date_closed_range_div', 'display', 'none');
        YAHOO.util.Dom.setStyle('<?php echo $control_name . '_' . $field_guide;?>-date_closed_between_range_div', 'display', 'none');
            
        //value = $("#filter_values-sbr_periods_filter").val();
        value = YAHOO.util.Dom.get("filter_values-<?php echo $control_name . '_' . $field_guide;?>").value;
        if (value == "between") {
            //$("#date_closed_between_range_div").show();
            YAHOO.util.Dom.setStyle('<?php echo $control_name . '_' . $field_guide;?>-date_closed_between_range_div', 'display', 'block');
        } else if (value == "=" ||
                   value == "not_equal" ||
                   value == "greater_than" ||
                   value == "less_than"
        ) {
            //$("#date_closed_range_div").show();
            YAHOO.util.Dom.setStyle('<?php echo $control_name . '_' . $field_guide;?>-date_closed_range_div', 'display', 'block');
        }
    }
    
</script>
