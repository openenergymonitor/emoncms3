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
<script type="application/javascript" src="<?php echo $path;?>Vis/Dashboard/common.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $path;?>Views/theme/common/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $path;?>Includes/lib/jquery-ui-1.8.19.custom/css/smoothness/jquery-ui-1.8.19.custom.css" />  
<script type="application/javascript" src="<?php echo $path;?>Includes/lib/jquery-ui-1.8.19.custom/js/jquery-ui-1.8.19.custom.min.js"></script>
  
<!------------------------------------------------------------------------------------------
Dashboard HTML
------------------------------------------------------------------------------------------->
<div style="text-align:center; width:100%;">
</div>
<script type="application/javascript">

// Global page vars definition
	var path = "<?php echo $path;?>";
	var apikey_read = "<?php echo $apikey_read;?>";
	var apikey_write = "<?php echo $apikey_write;?>";
	
// CKEditor Events and initialization
	$(document).ready(function() 
	{
	$(".new-dashboard-button").button();
	$(".delete-dashboard-button").button();	
	
	$(".new-dashboard-button").click(function(){
   	$.ajax({
			type : "POST",
			url :  "<?php echo $path;?>" + "dashboards/set",
			data : "&content=" + encodeURIComponent('ev.data.getData()'),
			dataType : 'json',
			success : function() { }
		});
	});
			
	$(".delete-dashboard-button").click(function(){
   	$.ajax({
			type : "POST",
			url :  "<?php echo $path;?>" + "dashboards/set",
			data : "&content=" + encodeURIComponent('ev.data.getData()'),
			dataType : 'json',
			success : function() { }
		});
	}); 
	
	});

</script>

<div class="new-dashboard-button">New dashboard</div>

<?php
	
	while ($row = $dashboards->fetch_array(MYSQLI_NUM)) {
		//printf ("%s (%s)\n", $row[0], $row[1]);
		
	echo '<table><tr><td><div class="dashboard-preview"></div></td></tr><tr><td><div class="delete-dashboard-button">-</div><button>clone</button><button>rename</button><button>preview</button></td></tr><table>';

	}
			
?>
