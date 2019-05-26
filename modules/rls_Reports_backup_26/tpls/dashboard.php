<!-- script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script -->
<script type="text/javascript" src="modules/rls_Reports/js/jquery.cookie.js"></script>
<!--  script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js"></script -->

<style>
    .chartCanvas {
        height: 600px !important;
    }
</style>

<?php
global $sugar_config;
if(array_key_exists('suitecrm_version',$sugar_config)){
    $version = explode('.',$sugar_config['suitecrm_version']);
    if($version[0]>7){
        echo '<script type="text/javascript" src="modules/rls_Reports/libs/tabs/rls_tabs.js"></script>';
    } elseif($version[0]==7 && $version[1]>=2){
        echo '<script type="text/javascript" src="modules/rls_Reports/libs/tabs/rls_tabs.js"></script>';
    }
}
?>


<script type="text/javascript" src="modules/rls_Reports/js/dashboard/MySugar.js?v=<?php echo uniqid() ?>"></script>
<script type="text/javascript" src="modules/rls_Reports/js/dashboard/dashlets.js?v=<?php echo uniqid() ?>"></script>

<script type="text/javascript" src="modules/rls_Reports/js/dashboard-onready.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/dashboard/Dashboard.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/dashboard/Dashboard.Layout.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/dashboard/Dashboard.Tabs.js"></script>
<script type="text/javascript" src="modules/rls_Reports/js/dashboard/Dashboard.Dashlets.js"></script>



<!-- link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" -->
<link href="modules/rls_Reports/css/dashboard.css" rel="stylesheet" type="text/css">

<link href="include/SugarCharts/Jit/css/base.css?v=<?php echo uniqid() ?>" rel="stylesheet" type="text/css">                
<script type="text/javascript" src="include/SugarCharts/Jit/js/mySugarCharts.js?v=<?php echo uniqid() ?>"></script>
<script type="text/javascript" src="include/SugarCharts/Jit/js/sugarCharts.js?v=<?php echo uniqid() ?>"></script>
<script type="text/javascript" src="include/SugarCharts/Jit/js/Jit/jit.js?v=<?php echo uniqid() ?>"></script>

<div class="dashboard">
    <div id="tabs">
        <ul>            
            <?php echo $view->displayTabs() ?>
        </ul>
    </div>

</div><!-- End demo -->
<?php echo $view->displayAppendedScripts() ?>
