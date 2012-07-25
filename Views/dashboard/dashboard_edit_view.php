<?php global $session,$path; ?>

  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgetlist.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/render.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/dial.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/led.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/cylinder.js"></script>
    
<div style="background-color:#ddd; padding:4px;">
  <span id="widget-buttons"></span>
  <span id="when-selected">
  <button id="options-button">Options</button>
  <button id="delete-button">Delete</button>
  </span>
  
  <button style="float:right; margin:6px;" id="save-dashboard">Save</button>
</div>

<div id="page-container" style="height:400px; position:relative;">
  <div id="page"><?php echo $dashboard['content']; ?></div>
  <canvas id="can" width="940px" height="400px" style="position:absolute; top:0px; left:0px; margin:0; padding:0;"></canvas>

  <div id="testo" style="position:absolute; top:0px; left:0px; width:938px; background-color:rgba(255,255,255,0.9); border: 1px solid #ddd;">
    <div style="padding:20px;">
      <div id="box-options"></div>
      <input id='options-save' type='button' value='save'/ >
    </div>
  </div> 
</div>

<script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/designer.js"></script>
<script type="application/javascript">

  var dashid = <?php echo $dashboard['id']; ?>;
  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";
  $("#testo").hide();
  
  var redraw = 0;
  var reloadiframe = 0;

  var grid_size = 20;

  dashboard_designer("#can",grid_size,widgets);

  show_dashboard();

  $("#save-dashboard").click(function (){
    $.ajax({
      type : "POST",
      url :  path+"dashboard/set",
      data : "&content=" + encodeURIComponent($("#page").html())+"&id="+dashid,
      dataType : 'json',
      success : function() { } 
    });
  });
</script>
