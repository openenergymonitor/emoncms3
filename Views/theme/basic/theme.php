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
global $embed, $session;
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<!-- Thanks to Baptiste Gaultier for the emoncms dial icon http://bit.ly/zXgScz -->
		<link rel="shortcut icon" href="<?php echo get_theme_path(); ?>/favicon.png" />
		
    <link href="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">		
		<link href="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo get_theme_path(); ?>/style.css" />

		<!------------------------------------------------------------------
		APPLE TWEAKS - thanks to Paul Dreed
		------------------------------------------------------------------->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="apple-touch-startup-image" href="<?php print $GLOBALS['path']; ?>Views/theme/dark/ios_load.png">
		<link rel="apple-touch-icon" href="<?php print $GLOBALS['path']; ?>Views/theme/dark/logo_normal.png">
		<title>Emoncms</title>
	</head>
	<body <?php if (!$embed) { ?> style="padding-top:42px; " <?php } ?> >	  
		<!------------------------------------------------------
		HEADER
		------------------------------------------------------->
		<?php if ($path != $menu) { ?>
			<?php if (!$embed) { ?>
				<div class="navbar navbar-fixed-top">
	    			<div class="navbar-inner">
	    				<div class="container">
	    					<?php
	    						echo $menu.$addmenu; 
	    					?> 
						</div>
	    			</div>
	    		</div>
			<?php } 
		} ?>				
		
		<?php if ($message) { ?>     	
		<div class="alert alert-info">
    			<button class="close" data-dismiss="alert">×</button>
    			<strong>Message: </strong><?php print $message; ?>
    		</div>
		<?php } ?>
		
		<!------------------------------------------------------
		CONTENT
		------------------------------------------------------->     	
    <div>
		  <?php print $content; ?>
		</div>

		<div style="clear:both; height:37px;"></div> 
		
		</div> <!----- END OF WRAPPER --->

		<!------------------------------------------------------
		FOOTER
		------------------------------------------------------->
    <?php if (!$embed) { ?>
		  <div class="footer">Powered by <a href="http://openenergymonitor.org">openenergymonitor.org</a></div>		  
    <?php } ?>   
    	    
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/jquery.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/google-code-prettify/prettify.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-transition.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-alert.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-modal.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-dropdown.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-scrollspy.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-tab.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-tooltip.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-popover.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-button.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-collapse.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-carousel.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-typeahead.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/application.js"></script>
    
	</body>
</html>
