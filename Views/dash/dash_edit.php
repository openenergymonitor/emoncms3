<?php global $session,$path; ?>

  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dash/widgetlist.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/common2.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/dial2.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/led.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/cylinder.js"></script>

<?php if ($session['write'] && $_SESSION['editmode'] ==TRUE) { ?>
<div style="width:100%; background-color:#ddd;">
  <div style="margin: 0px auto; text-align:left; width:940px;">

    <span style="float:left; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Dashboards:</span>
    <ul class="greydashmenu">
      <?php echo $menu; ?>
      <li><a href="../dashboards/new"><b>+ New</b></a></li>
    </ul>

    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="../dashboard/view?id=<?php echo $dashid; ?>">CKEditor</a></li>
      <li><a href="source?id=<?php echo $dashid; ?>">Source</a></li>
    </ul>
    <span style="float:right; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Edit:</span>

    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="view?id=<?php echo $dashid; ?>">View</a></li>
    </ul>

    <div style="clear:both"></div>
  </div>
</div><br>
<?php } ?>

<div class='lightbox' >
<div style="margin: 0px auto; text-align:left; width:800px;">

<div style="background-color:#ddd; padding:4px;">
  <span id="widget-buttons"></span>
  <span id="when-selected">
  <button id="options-button">Options</button>
  <button id="delete-button">Delete</button>
  </span>
  
  <button style="float:right; margin:6px;" id="save-dashboard">Save</button>
  <button style="float:right; margin-top:6px;" id="configure-dashboard-button">Configure</button>
</div>

<div id="page-container" style="height:400px; position:relative;">
  <div id="page"><?php echo $dashboard['ds_content']; ?></div>
  <canvas id="can" width="800px" height="400px" style="position:absolute; top:0px; left:0px; margin:0; padding:0;"></canvas>

  <div id="testo" style="position:absolute; top:0px; left:0px; width:798px; background-color:rgba(255,255,255,0.9); border: 1px solid #ddd;">
    <div style="padding:20px;">
      <div id="box-options"></div>
      <input id='options-save' type='button' value='save'/ >
    </div>
  </div>

  <div id="configure-dashboard" style="position:absolute; top:0px; left:0px; width:798px; background-color:rgba(255,255,255,0.9); border: 1px solid #ddd;">
    <div style="padding:20px;">

        <form id="confform" action="">
          <label><?php echo _("Dashboard name: "); ?></label>
          <input type="text" name="name" value="<?php echo $dashboard['ds_name']; ?>" />
          <label><?php echo _("Description: "); ?></label>           
          <textarea name="description"><?php echo $dashboard['ds_description']; ?></textarea>
          <label><?php echo _("Main dashboard: "); ?></label>
          <input type="checkbox" name="main" value="main" <?php
          if ($dashboard['ds_main'] == true)
            echo "checked";
          ?> />
        <br>        
        <input type="button" class="btn" id='configure-save' value="Save configuration" />
        </form>
    </div>
  </div>

</div>

</div>
</div>

<script type="text/javascript" src="<?php echo $path; ?>Views/dash/designer.js"></script>
<script type="application/javascript">

  var dashid = <?php echo $dashid; ?>;
  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";
  $("#testo").hide();
  $("#configure-dashboard").hide();

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

  $("#configure-dashboard-button").click(function (){
    $("#configure-dashboard").show();
  });

  $("#configure-save").click(function (){
    $("#configure-dashboard").hide();
    $.ajax({
      type : "POST",
      url :  path+"dashboard/setconf",
      data : $('#confform').serialize()+"&id="+dashid,
      dataType : 'json',
      success : function() { }
    });
  });
</script>
