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

  // no direct access
  defined('EMONCMS_EXEC') or die(_('Restricted access'));

  function vis_controller()
  {
    require "Models/feed_model.php";
    global $session, $action, $format;

    if ($session['read'])
    {
    	$apikey = get_apikey_read($session['userid']);
    }

    if ($action == 'list' && $session['write'])
    {
      $output['content'] = view("vis_view.php", array());
    }

    // TODO: Include in .css stylesheet and remove the styling-code of class 'lightbox'
    
    // Removed repeated code and stored in $widgetCode
	$widgetCode = '<div class="lightbox" style="margin-bottom:20px; "><iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/'.$action.'php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe></div>';
    $widgetCode .= "<div class='lightbox'>";
    $widgetCode .= "<h3>"._("Embed this graph")."</h3>";
    $widgetCode .= htmlspecialchars('<iframe style="width:100%; height:400px;" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$GLOBALS['path'].'Vis/'.$action.'php?apikey='.$apikey.'&feedid='.$feedid.'"></iframe>');
    $widgetCode .= "</div>";
    $widgetCode .=" </div>";
    
    // emoncms/vis/realtime?feedid=16&feedname=power
    if ($action == "realtime" && $session['read'])
    {
      $feedid = intval($_GET['feedid']);
      $feedname = get_feed_name($feedid);

      $content = '<div style="margin-right:3%; margin-left:3%;">';
      $content .= "<h2>"._("Realtime: ").$feedname."</h2>";
	  $content .= $widgetCode;
      $output['content'] = $content;
    }

    // emoncms/vis/rawdata?feedid=16&feedname=power
	elseif ($action == "rawdata" && $session['read'])
    {
      $feedid = intval($_GET['feedid']); $feedname = get_feed_name($feedid);

      $content = '<div style="margin-right:3%; margin-left:3%;">';
      $content .= "<h2>"._("Raw data: ").$feedname."</h2><p>"._("With Level-of-detail zooming")."</p>";
	  $content .= $widgetCode;
      $output['content'] = $content;
    }

    // emoncms/vis/bargraph?feedid=16&feedname=power
	elseif ($action == "bargraph" && $session['read'])
    {
      $feedid = intval($_GET['feedid']); $feedname = get_feed_name($feedid);

      $content = '<div style="margin-right:3%; margin-left:3%;">';
      $content .= "<h2>"._("Bar graph view: ").$feedname."</h2>";
	  $content .= $widgetCode;
      $output['content'] = $content;
    }

    // emoncms/vis/bargraph?feedid=16&feedname=power
	elseif ($action == "histgraph" && $session['read'])
    {
      $feedid = intval($_GET['feedid']);
      $feedname = get_feed_name($feedid);


      $content = '<div style="margin-right:3%; margin-left:3%;">';
      $content .= "<h2>"._("All-time histogram graph view: ").$feedname."</h2>";
	  $content .= $widgetCode;
      $output['content'] = $content;
    }

	elseif ($action == 'multigraph' && $session['read'])
    {
      if ($session['write'])
      {
      	$write_apikey = get_apikey_write($session['userid']);
      }
      $output['content'] = view("vis/multigraph.php", array('write_apikey'=>$write_apikey));
    }

 
    return $output;
  }

?>
