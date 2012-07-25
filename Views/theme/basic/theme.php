<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
  /*
  All Emoncms code is released under the GNU Affero General Public License.
  See COPYRIGHT.txt and LICENSE.txt.

  ---------------------------------------------------------------------
  Emoncms - open source energy visualisation
  Part of the OpenEnergyMonitor project:
  http://openenergymonitor.org
  */

  global $session;
?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- Thanks to Baptiste Gaultier for the emoncms dial icon http://bit.ly/zXgScz -->
    <link rel="shortcut icon" href="<?php echo get_theme_path(); ?>/favicon.png" />
    <link href="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo get_theme_path(); ?>/style.css" />

    <!-- APPLE TWEAKS - thanks to Paul Dreed -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-startup-image" href="<?php print $GLOBALS['path']; ?>Views/theme/dark/ios_load.png">
    <link rel="apple-touch-icon" href="<?php print $GLOBALS['path']; ?>Views/theme/dark/logo_normal.png">
    <title>Emoncms</title>
  </head>
  <body style="padding-top:42px;" >
    <!------------------------------------------------------
    HEADER
    ------------------------------------------------------->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <?php echo $mainmenu.$runmenu; ?> 
        </div>
      </div>
    </div>
		
    <?php if ($message) { ?>     	
    <div class="alert alert-info">
      <button class="close" data-dismiss="alert">×</button>
      <strong>Message: </strong><?php print $message; ?>
    </div>
    <?php } ?>

    <!------------------------------------------------------
    GREY SUBMENU
    ------------------------------------------------------->
    <?php if ($submenu) { ?>  
    <div style="width:100%; background-color:#ddd; height:27px;">
      <div style="margin: 0px auto; text-align:left; width:940px;">
        <?php echo $submenu; ?> 
      </div>
    </div>
    <?php } ?>
    <!------------------------------------------------------
    CONTENT
    ------------------------------------------------------->     	
    <div class="content">
      <div style="margin: 0px auto; max-width:940px; padding:10px;">
        <?php print $content; ?>
      </div>
    </div>

    <div style="clear:both; height:37px;"></div> 
    <!------------------------------------------------------
    FOOTER
    ------------------------------------------------------->
    <div class="footer">Powered by <a href="http://openenergymonitor.org">openenergymonitor.org</a></div>  
    
    
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- do not remove, for dev tests -->
    <!-- 
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/jquery.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/google-code-prettify/prettify.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-alert.js"></script>    
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
    -->
    
    <!-- needed for modal -->
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/jquery.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-modal.js"></script>
    <script src="<?php print $GLOBALS['path']; ?>Includes/lib/bootstrap/js/bootstrap-transition.js"></script>
  </body>
</html>
