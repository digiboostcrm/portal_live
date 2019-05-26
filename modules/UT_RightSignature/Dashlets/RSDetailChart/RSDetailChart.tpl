<div id='rsdetailchart_{$id}' style='overflow: auto; width: 100%; height: {$height}px; border: 1px #ddd solid'>
    <div id="rs_chart_div">
    </div>
</div>
{literal}
<script type="text/javascript">
    var thisDashId = {/literal}"{$id}"{literal};
    var RSChart;
    function drawRSChart() 
    {
        if("{/literal}{$SPACKETDATA}{literal}" != ''){
            var data = google.visualization.arrayToDataTable([{/literal}{$SPACKETDATA}{literal}]);
            var options = {
                title: "{/literal}{$sRSChartSummTitle}{literal}",
                width: 500,
                height: 300,
                bar: {groupWidth: "55%"},
                legend: { position: "none" },
            };
            RSChart = new google.visualization.ColumnChart(document.getElementById("rs_chart_div"));
            RSChart.draw(data, options);
        }
    }
    YAHOO.util.Get.script("https://www.google.com/jsapi", {
        onSuccess: function(obj) { 
            google.load('visualization', '1.0', {'packages':['corechart'], callback: drawRSChart});
        }
    });
</script>
{/literal}