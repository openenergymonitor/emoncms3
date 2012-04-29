<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

  <!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  -->


<?php
  $apikey_read = $_GET['apikey'];
  $path = dirname("http://".$_SERVER['HTTP_HOST'].str_replace('Vis/Dashboard', '', $_SERVER['SCRIPT_NAME']))."/";
?>

<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="user-scalable=no, width=device-width" />

    <link rel="stylesheet" type="text/css" href="../../Views/theme/dark/style.css" />

<!------------------------------------------------------------------------------------------
  Dashboard related javascripts
------------------------------------------------------------------------------------------->
<!--[if IE]><script language="javascript" type="text/javascript" src="../flot/excanvas.min.js"></script><![endif]-->
	<script type="text/javascript" src="../flot/jquery.js"></script>
	<script type="text/javascript" src="../flot/jquery.flot.js"></script>
	<script type="text/javascript" src="common.js"></script>
	<script type="text/javascript" src="widgets/dial.js"></script>
	<script type="text/javascript" src="widgets/led.js"></script>
	<title>emoncms</title>
  </head>
  
  <body>
<!------------------------------------------------------------------------------------------
  Dashboard HTML
------------------------------------------------------------------------------------------->
    <div id="page"></div>  
<script>

// Global page vars definition
	var path = "<?php echo $path;?>";
	var apikey_read = "<?php echo $apikey_read;?>";
	
	$(function() {
	// Get dashboard from server
    $.ajax({                                      
      type: "GET",
      url: path+"dashboard/view.json?apikey="+apikey_read,           
      dataType: 'json',   
      async: 'true',
      success: function(data) 
      {
        $("#page").html(data);
      }
    });
    //-------------------------------------------------

    show_dashboard();
});

</script>

</body>
</html>
