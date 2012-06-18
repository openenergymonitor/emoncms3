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
global $path;
$apikey_read = $_GET['apikey'];
// Edit mode off, now is time to runtime
$_SESSION['editmode'] = FALSE;
?>

<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="user-scalable=no, width=device-width" />

   <!-- <link rel="stylesheet" type="text/css" href="<?php echo $path; ?>Views/theme/dark/style.css" />-->

<!------------------------------------------------------------------------------------------
  Dashboard related javascripts
------------------------------------------------------------------------------------------->
<!--[if IE]><script language="javascript" type="text/javascript" src="../flot/excanvas.min.js"></script><![endif]-->
	<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Includes/flot/jquery.flot.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/common.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/dial.js"></script>
	<script type="text/javascript" src="<?php echo $path; ?>Views/Dashboard/widgets/led.js"></script>
	<title>emoncms</title>
  </head>
  
<body>

<div id="page"><?php echo $page; ?></div>  
  
<script>
  // Global page vars definition
  var path =  "<?php echo $path; ?>";
  var apikey_read = "<?php echo $apikey_read; ?>";
  show_dashboard();
</script>

</body>
</html>
