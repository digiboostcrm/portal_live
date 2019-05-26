<?php
  //TODO: The code below needs to be refactored to fix bug with new AJSAX Sugar function
  //      It should be hidden to detailView metadata or so...
?>
<link href="include/SugarCharts/Jit/css/base.css?v=<?php echo uniqid() ?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="include/MySugar/javascript/MySugar.js?v=<?php echo uniqid() ?>"></script>
<script type="text/javascript" src="include/SugarCharts/Jit/js/mySugarCharts.js?v=<?php echo uniqid() ?>"></script>
<script type="text/javascript" src="include/SugarCharts/Jit/js/sugarCharts.js?v=<?php echo uniqid() ?>"></script>
<script type="text/javascript" src="include/SugarCharts/Jit/js/Jit/jit.js?v=<?php echo uniqid() ?>"></script>
<script type="text/javascript">
    var activePage = 0;
    current_user_id = "<?php echo $GLOBALS['current_user']->id ?>";
    var moduleName = "<?php echo $_REQUEST['module'] ?>";

    initMySugar();
    initmySugarCharts();
</script>
<?php
  //TODO: The code above needs to be refactored to fix bug with new AJSAX Sugar function
  //      It should be hidden to detailView metadata or so...
?>


<div class="">
  <h4><?php echo $settings['label'] ?></h4>

  <div class="edit view search basic">
    <form action="index.php?module=rls_Reports&action=reportSettingsSave&save_type=update_filters&record=<?php echo $_REQUEST['record'] ?>" method="post" name="FilterValues">
      <?php //echo $filter->display($settings['filter']) ?>

          <div class="rls_wizard_right_container">
              <div class="rls_sortable">
                  <table class="rls_table_sortable" cellpadding="0" cellspacing="0">
                      <tbody id="filter-fields-sortable">
                          <?php echo $display_filters->display($onlyruntime = true) ?>
                      </tbody>
                  </table>
              </div>
          </div>

      <?php if($display_filters->display($onlyruntime = true)): ?>
          <button type="Submit"> <?php echo $mod_strings['LBL_APPLY'] ?> &nbsp;</button>
          <!-- button type="Button" id="report-filter-clear"><?php echo $mod_strings['LBL_CLEAR'] ?></button -->
      <?php endif; ?>

    </form>
  </div>

  <div class="" align="center"><?php echo $chart->display() ?></div>

  <div class=""><?php if (isset($spreadsheet)) echo $spreadsheet->display(); ?></div>
</div>
