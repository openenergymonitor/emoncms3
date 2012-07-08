<?php global $path; ?>

  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dash/widgetlist.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/common2.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/dial2.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/led.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/cylinder.js"></script>

<style>

</style>

<div class='lightbox' >
  <div style="margin: 0px auto; text-align:left; width:800px;">

  <div id="page-container" style="height:400px; position:relative;">
    <div id="page"><?php echo $page['ds_content']; ?></div>
  </div>

  </div>
</div>

<script type="application/javascript">
  var dashid = <?php echo $dashid; ?>;
  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";

  var redraw = 1;
  show_dashboard();
</script>
