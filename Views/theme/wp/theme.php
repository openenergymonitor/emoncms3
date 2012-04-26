<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

  <!--
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  -->

<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="user-scalable=no, width=device-width" />
    <!-- Thanks to Baptiste Gaultier for the emoncms dial icon http://bit.ly/zXgScz -->
    <link rel="shortcut icon" href="<?php print $GLOBALS['path']; ?>Views/theme/dark/favicon.png" />
   
    <link rel="stylesheet" type="text/css" href="<?php print $GLOBALS['path']; ?>Views/theme/wp/style.css" />

    <!------------------------------------------------------------------
    APPLE TWEAKS - thanks to Paul Dreed
    ------------------------------------------------------------------->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-startup-image" href="<?php print $GLOBALS['path']; ?>Views/theme/dark/ios_load.png">
    <link rel="apple-touch-icon" href="<?php print $GLOBALS['path']; ?>Views/theme/dark/logo_normal.png">


    <title>emoncms</title>
  </head>
  <body>
    <div class="wrapper">

      <!------------------------------------------------------
      HEADER
      ------------------------------------------------------->
      <div class="header">
		  <ul id="top-menu">
			  <li><a style="padding:0" href='<?php echo $GLOBALS['path']; ?>dashboard/view'><img id="emoncms-logo" src="<?php print $GLOBALS['path']; ?>Views/theme/wp/emoncms logo.png" /></a></li>
			  <?php print $menu; ?>
		  </ul>
		  <div><?php echo $user; ?></div>
      </div>
      <div style='clear:both; height:28px;'></div>
      <?php if ($message) { ?>
      <div id="message"><?php print $message; ?></div>
      <?php } ?>
      <!------------------------------------------------------
      CONTENT
      ------------------------------------------------------->
      <div class="content">
          <?php print $content; ?>
      </div>

      <div style="clear:both; height:37px;"></div> 
    </div> <!----- END OF WRAPPER --->

    <!------------------------------------------------------
    FOOTER
    ------------------------------------------------------->
    <div class="footer">
      Powered by <a href="http://openenergymonitor.org">openenergymonitor.org</a>
    </div>
</body>
</html>
