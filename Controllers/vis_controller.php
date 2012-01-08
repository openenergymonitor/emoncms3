<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    USER CONTROLLER ACTIONS		ACCESS

    realtime?feedid=1			read
    rawdata?feedid=1			read
    bargraph?feedid=1			read

  */

  //---------------------------------------------------------------------
  // The html content on this page could be seperated out into a view
  //---------------------------------------------------------------------

  function vis_controller()
  {
    require "Models/feed_model.php";
    global $session, $action, $format;

    if ($session['read']) $apikey = get_apikey_read($session['userid']);

    $content = '<div style="margin-right:3%; margin-left:3%;">';

    // emoncms/vis/realtime?feedid=16&feedname=power
    if ($action == "realtime" && $session['read'])
    {
      $feedid = intval($_GET['feedid']); $feedname = get_feed_name($feedid);

      $content .= "<h2>Realtime: ".$feedname."</h2>";
      $content .= '<div class="lightbox" style="margin-bottom:20px; "><iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/realtime.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe></div>';

      $content .= "<div class='lightbox'>";
      $content .= "<h3>Embed this graph</h3>";
      $content .= htmlspecialchars('<iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/realtime.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe>');
      $content .= "</div>";
    }

    // emoncms/vis/rawdata?feedid=16&feedname=power
    if ($action == "rawdata" && $session['read'])
    {
      $feedid = intval($_GET['feedid']); $feedname = get_feed_name($feedid);

      $content .= "<h2>Raw data: ".$feedname."</h2><p>With Level-of-detail zooming</p>";
      $content .= '<div class="lightbox" style="margin-bottom:20px"><iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/rawdata.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe></div>';

      $content .= "<div class='lightbox'>";
      $content .= "<h3>Embed this graph</h3>";
      $content .= htmlspecialchars('<iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/rawdata.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe>');
      $content .= "</div>";
    }

    // emoncms/vis/bargraph?feedid=16&feedname=power
    if ($action == "bargraph" && $session['read'])
    {
      $feedid = intval($_GET['feedid']); $feedname = get_feed_name($feedid);

      $content .= "<h2>Bar graph view: ".$feedname."</h2>";
      $content .= '<div class="lightbox" style="margin-bottom:20px"><iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/bargraph.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe></div>';

      $content .= "<div class='lightbox'>";
      $content .= "<h3>Embed this graph</h3>";
      $content .= htmlspecialchars('<iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/bargraph.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe>');
      $content .= "</div>";
    }
 
    $content .=" </div>";

    return $content;
  }

?>
