<!--
All Emoncms code is released under the GNU Affero General Public License.
See COPYRIGHT.txt and LICENSE.txt.

---------------------------------------------------------------------
Emoncms - open source energy visualisation
Part of the OpenEnergyMonitor project:
http://openenergymonitor.org
-->
<?php
global $path;
?>
<!------------------------------------------------------------------------------------------
Dashboard related javascripts
------------------------------------------------------------------------------------------->
<script type="text/javascript" src="<?php echo $path;?>Vis/flot/jquery.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Vis/flot/jquery.flot.js"></script>
<script type="application/javascript" src="<?php echo $path;?>Vis/Dashboard/common.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Vis/Dashboard/widgets/dial.js"></script>
<script type="text/javascript" src="<?php echo $path;?>Vis/Dashboard/widgets/led.js"></script>
<!------------------------------------------------------------------------------------------
Dashboard HTML
------------------------------------------------------------------------------------------->
<div style="text-align:center; width:100%;">
	<div style="width: 960px; margin: 0px auto; padding:0px; text-align:left; margin-bottom:20px; margin-top:20px;">
		<textarea id="dashboardeditor"></textarea>
		<br/>
		<div id="page">
			<?php echo $page; ?>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
<script type="application/javascript">

// Global page vars definition
	var path = "<?php echo $path;?>";
	var apikey_read = "<?php echo $apikey_read;?>";
	var apikey_write = "<?php echo $apikey_write;?>";
	
// CKEditor Events and initialization
	$(document).ready(function() 
	{
		// Load the dasboard editor settings from file
	});

</script>
