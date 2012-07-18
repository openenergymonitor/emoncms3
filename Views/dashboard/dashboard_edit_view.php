<?php global $session,$path; ?>

  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
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
  <button style="float:right; margin-top:6px;" id="configure-dashboard-button">Configure</button>
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

  <div id="configure-dashboard" style="position:absolute; top:0px; left:0px; width:938px; background-color:rgba(255,255,255,0.9); border: 1px solid #ddd;">
    <div style="padding:20px;">

        <form id="confform" action="">
          <label><?php echo _("Dashboard name: "); ?></label>
          <input type="text" name="name" value="<?php echo $dashboard['name']; ?>" />
          <label><?php echo _("Menu name: (lowercase a-z only)"); ?></label>
          <input type="text" name="alias" value="<?php echo $dashboard['alias']; ?>" />
          <label><?php echo _("Description: "); ?></label>           
          <textarea name="description"><?php echo $dashboard['description']; ?></textarea>
 
          <table>
            <tr>
              <td width="112"><?php echo _("Main: "); ?></td>
              <td><input type="checkbox" name="main" value="1" <?php
              if ($dashboard['main'] == true)
                echo "checked";
              ?> /></td>
            </tr>
            <tr>
              <td><?php echo _("Published: "); ?></td>
              <td><input type="checkbox" name="published" value="1" <?php
              if ($dashboard['published'] == true)
                echo "checked";
              ?> /></td>
            </tr>
            <tr>
              <td><?php echo _("Public: "); ?></td>
              <td><input type="checkbox" name="public" value="1" <?php
              if ($dashboard['public'] == true)
                echo "checked";
              ?> /></td>
            </tr>

          </table>
        <br>        
        <input type="button" class="btn" id='configure-save' value="Save configuration" />
        </form>
    </div>
  </div>

</div>


<script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/designer.js"></script>
<script type="application/javascript">

  var dashid = <?php echo $dashboard['id']; ?>;
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
      url :  path+"dashboard/set",
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
