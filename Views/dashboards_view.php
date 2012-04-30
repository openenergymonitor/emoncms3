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

<link rel="stylesheet" type="text/css" href="<?php echo $path;?>Views/theme/common/style.css" />

  <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  
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
	$("button").button();	
	});

</script>

<br><button>New dashboard</button>
<br>

<?php
	
	while ($row = $dashboards->fetch_array(MYSQLI_NUM)) {
		//printf ("%s (%s)\n", $row[0], $row[1]);
		
	echo '<table><tr><td><div class="dashboard-preview"></div></td></tr><tr><td><button>-</button><button>clone</button><button>rename</button><button>preview</button></td></tr><table>';

	}
			
?>