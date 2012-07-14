<?php
/*
  All Emoncms code is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  ---------------------------------------------------------------------
  Emoncms - open source energy visualisation
  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org
*/

global $path, $session, $embed;
?>
<!------------------------------------------------------------------------------------------
Dashboard related javascripts
------------------------------------------------------------------------------------------->
<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/render.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/dial.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Views/dashboard/js/widgets/led.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Includes/editors/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $path; ?>Includes/editors/ckeditor/adapters/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path; ?>Includes/lib/jquery-ui-1.8.20.custom/css/smoothness/jquery-ui-1.8.20.custom.css" />
<script type="text/javascript" src="<?php echo $path; ?>Includes/lib/jquery-ui-1.8.20.custom/js/jquery-ui-1.8.20.custom.min.js"></script>

<!------------------------------------------------------------------------------------------
Dashboard HTML
------------------------------------------------------------------------------------------->

<?php if ($session['write'] && $_SESSION['editmode'] ==TRUE) { ?>
<div style="width:100%; background-color:#ddd;">
  <div style="margin: 0px auto; text-align:left; width:940px;">

    <span style="float:left; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Dashboards:</span>
    <ul class="greydashmenu">
      <?php echo $menu; ?>
      <li><a href="new"><b>+ New</b></a></li>
    </ul>

    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="edit?id=<?php echo $dashboard['id']; ?>">Draw</a></li>
      <li><a href="source?id=<?php echo $dashboard['id']; ?>">Source</a></li>
    </ul>
    <span style="float:right; color:#888; font: 13px/27px sans-serif; font-weight:bold; ">Edit:</span>

    <ul class="greydashmenu" style="float:right; padding-right:5px;">
      <li><a href="view?id=<?php echo $dashboard['id']; ?>">View</a></li>
    </ul>

    <div style="clear:both"></div>
  </div>
</div><br>
<?php } ?>

<div style="text-align:center; width:100%;">
  <div style="width: 960px; margin: 0px auto; padding:0px; text-align:left; margin-bottom:20px; margin-top:5px;">
  
        <div id="dashboardeditor"></div>

        <br>

        <form id="confform" action="">
          <label><?php echo _("Dashboard name: "); ?></label>
          <input type="text" name="name" value="<?php echo $dashboard['name']; ?>" />
          <label><?php echo _("Description: "); ?></label>           
          <textarea name="description"><?php echo $dashboard['description']; ?></textarea>
          <label><?php echo _("Main dashboard: "); ?></label>
          <input type="checkbox" name="main" value="main" <?php
          if ($dashboard['main']== true)
            echo "checked";
          ?> />
        <br>        
        <input type="button" class="btn" id="submit_btn" value="Save configuration" />
        </form>

        <div id="page-container" style="height:400px; position:relative;">
          <div id="page">
            <?php echo $dashboard['content']; ?>
          </div>
        </div>
        <div style="clear:both;"></div>
  </div>
</div>

<script type="text/javascript">
  $("#page-container").hide();
  // Global page vars definition
  var editor = null;
  var path = "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";
  var apikey_write = "<?php echo $apikey_write; ?>";
  var dashid = <?php echo $dashboard['id']; ?>;

  var redraw = 1; 
  var ckeditor_widget_id = 0;

  $.urlParam = function(name){
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
  }

  // CKEditor Events and initialization

  // Fired on editor instance ready
  CKEDITOR.on( 'instanceReady', function( ev )
  {
  // Set rules for div tag
  ev.editor.dataProcessor.writer.setRules( 'div',{
  // Indicates that this tag causes indentation on line breaks inside of it.
  indent : true,

  // Inserts a line break before the <div> opening tag.
  breakBeforeOpen : true,

  // No inserts a line break after the <div> opening tag.
  breakAfterOpen : false,

  // No inserts a line break before the </div> closing tag.
  breakBeforeClose : false,

  // Inserts a line break after the </div> closing tag.
  breakAfterClose : true
  });

  // Set here the css style so each dashboard can have it own
  // theme and visual design styles for the editor
  //ev.editor.config.contentsCss = [path+'Views/theme/dark/style.css',path+'Views/theme/common/visualdesign_style.css'];
  //CKEDITOR.config.contentsCss = path+'Views/theme/common/visualdesign_style.css';

  // Place page html in edit area ready for editing
  ev.editor.setData( $("#page").html() );

  // On instance ready we show the preview
  //show_dashboard();
  });

  // Fired on editor preview pressed
  CKEDITOR.on( 'previewPressed', function( ev )
  {
    window.open( path+"dashboard/view?id="+dashid, null,'toolbar=yes,location=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=' + 640 + ',height=' + 420 + ',left=' + 80 );
  });

  // Fired on editor save pressed
  CKEDITOR.on( 'savePressed', function( ev )
  {
  // Upload changes to server
  $.ajax({
  type : "POST",
  url :  path+"dashboard / set",
  data : "&content=" + encodeURIComponent(ev.data.getData())+"&id="+dashid,
  dataType : 'json',
  success : function() {
  }
  });

  // Run javascript
  update();
  });

  $(document).ready(function()
  {
  // Hide the editor on load
  //          element = document.getElementById('dashboardeditor');
  //         element.style.display = 'none';

  // Hide/show feature
  $(function() {
  // Create button style
  //$( "button").button();

  // Save dashboard configuration
  $("#submit_btn").click(function() {
  //alert($('#confform').serialize());
  $.ajax({
  type : "POST",
  url :  path+"dashboard / setconf",
  data : $('#confform').serialize()+"&id="+dashid,
  dataType : 'json',
  success : function() {
  }
  });

  return false;
  });

  // toggle editor style
  /*$( "button" ).click(
  function() {
  element = document.getElementById('dashboardeditor');

  /*if (element.style.display != 'none')
  {
  element.style.display = 'none';
  $(this).html('Edit');
  }
  else
  {
  element.style.display = 'block';
  $(this).html('Hide editor');
  }
  });*/
  });

  // Load the dasboard editor settings from file
  editor = CKEDITOR.appendTo( 'dashboardeditor',
  {
    customConfig : path+'Includes/editors/ckeditor_settings.js'
  });
  });

    </script>
