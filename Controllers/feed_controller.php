<?php 
  /*
    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    FEED CONTROLLER ACTIONS		ACCESS

    tag?id=1&tag=tag			write
    rename?id=1&name=newname		write
    delete?id=1				write
    list				read
    view?id=1				read
    value?id=1				read
    data?id=1&start=000&end=000&res=1	read
    
  */
  function feed_controller()
  {
    require "Models/feed_model.php";
    global $action, $format;

    //---------------------------------------------------------------------------------------------------------
    // Set feed tag
    // http://yoursite/emoncms/feed/tag?id=1&tag=tag
    //---------------------------------------------------------------------------------------------------------
    if ($action == "tag" && $_SESSION['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $_SESSION['userid'])) {

        $newfeedtag = preg_replace('/[^\w\s-.]/','',$_GET["tag"]);	// filter out all except for alphanumeric white space and dash and full stop
        $newfeedtag = db_real_escape_string($newfeedtag);

        set_feed_tag($feedid,$newfeedtag);
        $output = "feed tag changed";
      } else $output = "feed does not exist";

      if ($format == 'html') header("Location: list");	// Return to feed list page
    }

    //---------------------------------------------------------------------------------------------------------
    // Rename a feed
    // http://yoursite/emoncms/feed/rename?id=1&name=newname
    //---------------------------------------------------------------------------------------------------------
    if ($action == "rename" && $_SESSION['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $_SESSION['userid'])) {

        $newfeedname = preg_replace('/[^\w\s-.]/','',$_GET["name"]);	// filter out all except for alphanumeric white space and dash
        $newfeedname = db_real_escape_string($newfeedname);

        set_feed_name($feedid,$newfeedname);
        $output = "feed renamed";
      } else $output = "feed does not exist";

      if ($format == 'html') header("Location: list");	// Return to feed list page
    }

    //---------------------------------------------------------------------------------------------------------
    // Delete a feed
    // http://yoursite/emoncms/feed/delete?id=1
    //--------------------------------------------------------------------------------------------------------- 
    if ($action == "delete" && $_SESSION['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $_SESSION['userid'])) {
        delete_feed($userid,$feedid);
        $output = "feed ".$feedid." deleted";
      } else $output = "feed does not exist";

      if ($format == 'html') header("Location: list");	// Return to feed list page
    }


    //---------------------------------------------------------------------------------------------------------
    // List
    // http://yoursite/emoncms/feed/list.html
    // http://yoursite/emoncms/feed/list.json
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'list' && $_SESSION['read'])
    {
      $feeds = get_user_feeds($_SESSION['userid']);
    
      if ($format == 'json') $output = json_encode($feeds);
      if ($format == 'html') $output = view("feed/list_view.php", array('feeds' => $feeds));
    }

    //---------------------------------------------------------------------------------------------------------
    // View
    // http://yoursite/emoncms/feed/view.html?id=1
    // http://yoursite/emoncms/feed/view.json?id=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'view' && $_SESSION['read'])
    {
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid,$_SESSION['userid']))
      {
        $feed = get_feed($feedid);
      }

      if ($format == 'json') $output = json_encode($feed);
      if ($format == 'html') $output = view("feed/feed_view.php", array('feed' => $feed));
    }

    //---------------------------------------------------------------------------------------------------------
    // current feed value
    // http://yoursite/emoncms/feed/value?id=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'value' && $_SESSION['read'])
    {
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid,$_SESSION['userid'])) $output = get_feed_value($feedid);
    }

    //---------------------------------------------------------------------------------------------------------
    // get feed data
    // http://yoursite/emoncms/feed/data?id=1&start=000&end=000&res=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'data' && $_SESSION['read'])
    {
      $feedid = intval($_GET['id']);

      // Check if feed belongs to user
      if (feed_belongs_user($feedid,$_SESSION['userid']))
      {
        $start = floatval($_GET['start']);
        $end = floatval($_GET['end']);
        $resolution = intval($_GET['res']);
        $data = get_feed_data($feedid,$start,$end,$resolution);
        $output = json_encode($data);
      }
    }

    return $output;
  }
?>


