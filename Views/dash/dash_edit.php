<?php global $path; ?>

  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dash/widgetlist.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/common2.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/dial2.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/led.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/cylinder.js"></script>

<div class='lightbox' >
<div style="margin: 0px auto; text-align:left; width:800px;">

<div style="background-color:#ddd; padding:4px;">
  <span id="widget-buttons"></span>
  <span id="when-selected">
  <button id="options-button">Options</button>
  <button id="delete-button">Delete</button>
  </span>
  
  <button style="float:right; margin:6px;" id="save-dashboard">Save</button>
</div>

<div id="page-container" style="height:400px; position:relative;">
  <div id="page"><?php echo $page['ds_content']; ?></div>
  <canvas id="can" width="800px" height="400px" style="position:absolute; top:0px; left:0px; margin:0; padding:0;"></canvas>
</div>

<div id="testo" style="position:absolute; top:100px; left:150px; width:300px; height:200px; background-color:#eee; padding:20px; border: 1px solid #ddd;">
  <div id="box-options"></div>
  <input id='options-save' type='button' value='save'/ >
</div>

</div>
</div>

<script type="text/javascript" src="<?php echo $path; ?>Views/dash/designer.js"></script>
<script type="application/javascript">

  var dashid = <?php echo $dashid; ?>;
  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";
  $("#testo").hide();

  var redraw = 0;
  var reloadiframe = 0;

  var grid_size = 20;

  dashboard_designer("#can",grid_size,widgets);

  show_dashboard();

  $("#save-dashboard").click(function (){

    console.log($("#page").html());
    $.ajax({
      type : "POST",
      url :  path+"dashboard / set",
      data : "&content=" + encodeURIComponent($("#page").html())+"&id="+dashid,
      dataType : 'json',
      success : function() { } 
    });
  });
</script>
