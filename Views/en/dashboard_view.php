<!--
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
-->
<?php
global $path, $session, $embed;
?>
<!------------------------------------------------------------------------------------------
Dashboard related javascripts
------------------------------------------------------------------------------------------->
<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $path;?>Vis/flot/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo $path;?>Includes/flot/jquery.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Includes/flot/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Views/Dashboard/common.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Views/Dashboard/widgets/dial.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Views/Dashboard/widgets/led.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Includes/editors/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Includes/editors/ckeditor/adapters/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path;?>Includes/lib/jquery-ui-1.8.20.custom/css/smoothness/jquery-ui-1.8.20.custom.css" />  
<script type="text/javascript" src="<?php echo $path;?>Includes/lib/jquery-ui-1.8.20.custom/js/jquery-ui-1.8.20.custom.min.js"></script>

<!------------------------------------------------------------------------------------------
Dashboard HTML
------------------------------------------------------------------------------------------->

<?php if ($session['write']) { ?>
<div style="width:100%; background-color:#eee; text-align:right; padding:2px;"><button style="margin-right:4px;"><?php echo _("Edit");?></button></div>
<?php } ?>
<div style="text-align:center; width:100%;">
	<div style="width: 960px; margin: 0px auto; padding:0px; text-align:left; margin-bottom:20px; margin-top:5px;">
<div id="tabs">
	<ul>
		<li><a href="#tabs-1"><?php echo _("Edit dashboard");?></a></li>
		<li><a href="#tabs-2"><?php echo _("Configuration");?></a></li>
		<li><a href="#tabs-3"><?php echo _("Preview");?></a></li>		
	</ul>
	<div id="tabs-1">
		<div id="dashboardeditor"></div>	
	</div>
	<div id="tabs-2">
		<form id="confform" action=""> 
    		<?php echo _("Dashboard name: ");?><input type="text" name="name" value='<?php echo $ds_name; ?>' /><br>
    		<?php echo _("Description: ");?><textarea name="description"><?php echo $ds_description; ?></textarea><br>
    		<?php echo _("Main dashboard: ");?><input type="checkbox" name="main" value="main" <?php if ($ds_main==true) echo "checked"; ?> /><br>
		</form>
    		<!--Theme: 
    		<select>
  				<option value="" selected>dark</option>
  				<option value="">wp</option>
			</select>-->
    		<input type="submit" name="submit" class="button" id="submit_btn" value="Save configuration" />  
		</form>
	</div>
	<div id="tabs-3">
		<div id="page"><?php echo $page; ?></div>
		<div style="clear:both;"></div>
	</div>	
	
</div>

<script type="text/javascript">

// Global page vars definition
	var editor = null;
	var path = "<?php echo $path;?>";
	var apikey_read = "<?php echo $apikey_read;?>";
	var apikey_write = "<?php echo $apikey_write;?>";
	
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

window.open( "<?php echo $path;?>dashboard/view?apikey=<?php echo $apikey; ?>&id="+$.urlParam('id')+"&embed=1", null, 'toolbar=yes,location=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=' +
				640 + ',height=' + 420 + ',left=' + 80 );		
	});
		
	// Fired on editor save pressed		
	CKEDITOR.on( 'savePressed', function( ev )
	{				
		// Upload changes to server
		$.ajax({
			type : "POST",
			url :  "<?php echo $path;?>" + "dashboard/set",
			data : "&content=" + encodeURIComponent(ev.data.getData())+"&id="+$.urlParam('id'),
			dataType : 'json',
			success : function() {
			}
		});

		// Update page html
		$("#page").html(ev.data.getData());
					
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
			
			$( "#tabs" ).tabs({
			   select: function(event, ui) { 
			   		if (ui.index == 2) { show_dashboard(); }; 
			    }
			});
						
			// Save dashboard configuration        
            $(".button").click(function() {  
     			//alert($('#confform').serialize());
     			$.ajax({
					type : "POST",
					url :  "<?php echo $path;?>" + "dashboard/setconf",
					data : $('#confform').serialize()+"&id="+$.urlParam('id'),
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
	        customConfig : '<?php echo $path;?>Includes/editors/ckeditor_settings.js'
	    });
	});

</script>
