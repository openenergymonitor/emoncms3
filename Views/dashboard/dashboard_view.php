<?php global $session, $path; ?>

  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgetlist.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/render.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/dial.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/led.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/cylinder.js"></script>

<?php if ($session['write'] && $_SESSION['editmode'] ==TRUE) { ?>
<div style="width:100%; background-color:#ddd;">
  <div style="margin: 0px auto; text-align:left; width:940px;">

    <span style="float:left; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Dashboards:</span>
    <ul class="greydashmenu">
      <?php echo $menu; ?>
      <li><a href="new"><b>+ New</b></a></li>
    </ul>

    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="thumb">Thumb</a></li>
      <li><a href="list">List</a></li>
    </ul>

    <span style="float:right; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">View:</span>

    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="<?php echo $path; ?>dashboard/edit?id=<?php echo $dashboard['id']; ?>">Draw</a></li>
      <li><a href="<?php echo $path; ?>dashboard/ckeditor?id=<?php echo $dashboard['id']; ?>">CKEditor</a></li>
      <li><a href="<?php echo $path; ?>dashboard/source?id=<?php echo $dashboard['id']; ?>">Source</a></li>
    </ul>
    <span style="float:right; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Edit:</span>
    <div style="clear:both"></div>
  </div>
</div><br>
<?php } ?>

<div class='lightbox' >
  <div style="margin: 0px auto; text-align:left; width:800px;">

  <div id="page-container" style="height:400px; position:relative;">
    <div id="page"><?php echo $dashboard['content']; ?></div>
  </div>

  </div>
</div>

<script type="application/javascript">
  var dashid = <?php echo $dashboard['id']; ?>;
  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";

  var redraw = 1;
  show_dashboard();
</script>
