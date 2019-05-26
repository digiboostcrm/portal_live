<div style="display:none;width:150px;" id="<?php echo $control_name . '_' . $field_guide;?>-begin_range_div">
    <input
        name="wizard[DisplayFilters][<?php echo $control_name . '_' . $field_guide;?>][value][]"
        size="15"
        id="filter_values-<?php echo $control_name . '_' . $field_guide;?>-0"
        value="<?php  echo ((isset($current_value[0]) and $current_value[0]) ? ((float)$current_value[0]) : '') ?>">
</div>

<div style="display:none;width:300px;" id="<?php echo $control_name . '_' . $field_guide;?>-between_range_div">
    <input
        name="wizard[DisplayFilters][<?php echo $control_name . '_' . $field_guide;?>][value][]"
        size="15"
        id="filter_values-<?php echo $control_name . '_' . $field_guide;?>-0"
        value="<?php echo ((isset($current_value[1]) and $current_value[1]) ? ((float)$current_value[1]) : '') ?>">

    <?php echo $app_strings['LBL_AND']?>

    <input
        name="wizard[DisplayFilters][<?php echo $control_name . '_' . $field_guide;?>][value][]"
        size="15"
        id="filter_values-<?php echo $control_name;?>-2"
        value="<?php echo ((isset($current_value[2]) and $current_value[2]) ? ((float)$current_value[2]) : '') ?>">
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
        YAHOO.util.Dom.setStyle('<?php echo $control_name . '_' . $field_guide;?>-begin_range_div', 'display', 'none');
        YAHOO.util.Dom.setStyle('<?php echo $control_name . '_' . $field_guide;?>-between_range_div', 'display', 'none');
            
        //value = $("#filter_values-sbr_periods_filter").val();
        value = YAHOO.util.Dom.get("filter_values-<?php echo $control_name . '_' . $field_guide;?>").value;
        if (value == "Is Between") {
            //$("#date_closed_between_range_div").show();
            YAHOO.util.Dom.setStyle('<?php echo $control_name . '_' . $field_guide;?>-between_range_div', 'display', 'block');
        } else {
            //$("#date_closed_range_div").show();
            YAHOO.util.Dom.setStyle('<?php echo $control_name . '_' . $field_guide;?>-begin_range_div', 'display', 'block');
        }
    }/**/
    
</script>
