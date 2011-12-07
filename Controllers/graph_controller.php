<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  //---------------------------------------------------------------------
  // The html content on this page could be seperated out into a view
  //---------------------------------------------------------------------

  function graph_controller()
  {
    if ($_POST['form'] == "graph")
    {
      $feedid = $_POST['feedid'];
      $feedname = $_POST['feedname'];

      $userid = $_SESSION['userid'];
      $apikey = get_apikey_read($userid);

      $content = '<div style="margin-right:3%; margin-left:3%;">';

      if ($_POST['sel'] ==1)
      {
        $content .= "<h2>Realtime: ".$feedname."</h2>";
        $content .= '<div class="lightbox" style="margin-bottom:20px; "><iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/realtime.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe></div>';

        $content .= "<div class='lightbox'>";
        $content .= "<h3>Embed this graph</h3>";
        $content .= htmlspecialchars('<iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/realtime.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe>');
        $content .= "</div>";
      }

      if ($_POST['sel'] ==2)
      {
        $content .= "<h2>Raw data: ".$feedname."</h2><p>With Level-of-detail zooming</p>";
        $content .= '<div class="lightbox" style="margin-bottom:20px"><iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/rawdata.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe></div>';

        $content .= "<div class='lightbox'>";
        $content .= "<h3>Embed this graph</h3>";
        $content .= htmlspecialchars('<iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/rawdata.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe>');
        $content .= "</div>";
      }

      if ($_POST['sel'] ==3)
      {
        $content .= "<h2>Bar graph view: ".$feedname."</h2>";
        $content .= '<div class="lightbox" style="margin-bottom:20px"><iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/bargraph.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe></div>';

        $content .= "<div class='lightbox'>";
        $content .= "<h3>Embed this graph</h3>";
        $content .= htmlspecialchars('<iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/bargraph.php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe>');
        $content .= "</div>";
      }
 
        $content .=" </div>";

    }
    else 
    {
      $content = "Please select feed to view from feeds page";
    }

    return $content;
  }

?>
