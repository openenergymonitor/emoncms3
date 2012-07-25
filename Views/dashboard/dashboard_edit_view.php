<?php global $session,$path; ?>

  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.min.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgetlist.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/render.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/dial.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/led.js"></script>
  <script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/cylinder.js"></script>

<!-- tool menu TODO:is the same at dashboard_thumb_view so it could be include from one place to share code -->
<div align="right">
  <a  data-toggle="modal" href="#myModal"><i class="icon-wrench"></i></a>  
  <a href="#" onclick="$.ajax({type : 'POST',url :  path + 'dashboard/new.json  ',data : '',dataType : 'json',success : location.reload()});"><i class="icon-plus-sign"></i></a>
  <a href="<?php echo $path; ?>dashboard/thumb"><i class="icon-th-large"></i></a>
  <a href="<?php echo $path; ?>dashboard/list"><i class="icon-th-list"></i></a>     
</div>

<!-- TODO put in separated unit -->
<div class="modal hide fade" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Dashboard Configuration</h3>
  </div>
  <div class="modal-body">
    <form id="confform" action="">
      <label><?php echo _("Dashboard name: "); ?></label>
      <input type="text" name="name" value="<?php echo $dashboard['name']; ?>" />
      <label><?php echo _("Menu name: (lowercase a-z only)"); ?></label>
      <input type="text" name="alias" value="<?php echo $dashboard['alias']; ?>" />
      <label><?php echo _("Description: "); ?></label>           
      <textarea name="description"><?php echo $dashboard['description']; ?></textarea>
 	</form>
      <table>
        <tr>
          <td width="112"><?php echo _("Main: "); ?></td>
          <td><input type="checkbox" name="main" id="chk_main" value="1" <?php
            if ($dashboard['main'] == true)
              echo "checked";
            ?> /></td>
        </tr>
        <tr>
          <td><?php echo _("Published: "); ?></td>
          <td><input type="checkbox" name="published" id="chk_published" value="1" <?php
            if ($dashboard['published'] == true)
              echo "checked";
            ?> /></td>
          </tr>
        <tr>
          <td><?php echo _("Public: "); ?></td>
          <td><input type="checkbox" name="public" id="chk_public" value="1" <?php
            if ($dashboard['public'] == true)
              echo "checked";
            ?> /></td>
          </tr>
      </table>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <a href="#" id="configure-save" class="btn btn-primary">Save changes</a>
  </div>
</div>
    
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

  $("#configure-save").click(function (){
  	// serialize doesnt return unchecked checkboxes so manual url must be built
  	$main = '0';  
  	$public = '0';
  	$published = '0';
  	
  	if ($("#chk_main").is(":checked")) $main = '1';
	if ($("#chk_public").is(":checked")) $public = '1';
  	if ($("#chk_published").is(":checked")) $published = '1';
  	//
  	
    $.ajax({
      type : "POST",
      url :  path+"dashboard/setconf",
      //data : $('#confform').serialize()+"&id="+dashid,   // serialize doesnt return unchecked checkboxes
      data : $('#confform').serialize()+"&id="+dashid+"&main="+$main+"&public="+$public+"&published="+$published,      
      dataType : 'json',
      success : function() {}
      //success : location.reload()    //// if reload, the editor content not saved is lost!! what to do?
    });
    $('#myModal').modal('hide');
  });
</script>
