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
    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";

    //---------------------------------------------------------------------------------------------------------
    // Set feed tag
    // http://yoursite/emoncms/feed/tag?id=1&tag=tag
    //---------------------------------------------------------------------------------------------------------
    if ($action == "tag" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $session['userid'])) {

        $newfeedtag = preg_replace('/[^\w\s-.]/','',$_GET["tag"]);	// filter out all except for alphanumeric white space and dash and full stop
        $newfeedtag = db_real_escape_string($newfeedtag);

        set_feed_tag($feedid,$newfeedtag);
        $output['message'] = "feed tag changed";
      } else $output['message'] = "feed does not exist";

      if ($format == 'html') header("Location: list");	// Return to feed list page
    }

    //---------------------------------------------------------------------------------------------------------
    // Rename a feed
    // http://yoursite/emoncms/feed/rename?id=1&name=newname
    //---------------------------------------------------------------------------------------------------------
    if ($action == "rename" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $session['userid'])) {

        $newfeedname = preg_replace('/[^\w\s-.]/','',$_GET["name"]);	// filter out all except for alphanumeric white space and dash
        $newfeedname = db_real_escape_string($newfeedname);

        set_feed_name($feedid,$newfeedname);
        $output['message'] = "Feed renamed";
      } else $output['message'] = "Feed does not exist";

      if ($format == 'html') header("Location: list");	// Return to feed list page
    }

    //---------------------------------------------------------------------------------------------------------
    // Delete a feed
    // http://yoursite/emoncms/feed/delete?id=1
    //--------------------------------------------------------------------------------------------------------- 
    if ($action == "delete" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $session['userid'])) {
        delete_feed($userid,$feedid);
        $output['message'] = "feed ".get_feed_name($feedid)." deleted";
      } else $output['message'] = "Feed does not exist";

    }


    //---------------------------------------------------------------------------------------------------------
    // List
    // http://yoursite/emoncms/feed/list.html
    // http://yoursite/emoncms/feed/list.json
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'list' && $session['read'])
    {
      $feeds = get_user_feeds($session['userid']);
    
      if ($format == 'json') $output['content'] = json_encode($feeds);
      if ($format == 'html') $output['content'] = view("feed/list_view.php", array('feeds' => $feeds));
    }

    //---------------------------------------------------------------------------------------------------------
    // View
    // http://yoursite/emoncms/feed/view.html?id=1
    // http://yoursite/emoncms/feed/view.json?id=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'view' && $session['read'])
    {
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid,$session['userid']))
      {
        $feed = get_feed($feedid);
      }

      if ($format == 'json')
      {
        $output['content'] = json_encode($feed);
        // Allow for AJAX from remote source
        if ($_GET["callback"]) $output['content'] = $_GET["callback"]."(".json_encode($feed).");";
      }
      if ($format == 'html') $output['content'] = view("feed/feed_view.php", array('feed' => $feed));
    }

    //---------------------------------------------------------------------------------------------------------
    // current feed value
    // http://yoursite/emoncms/feed/value?id=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'value' && $session['read'])
    {
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid,$session['userid'])) $output['content'] = get_feed_value($feedid);
    }

    //---------------------------------------------------------------------------------------------------------
    // get feed data
    // http://yoursite/emoncms/feed/data?id=1&start=000&end=000&res=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'data' && $session['read'])
    {
      $feedid = intval($_GET['id']);
      
      // Check if feed belongs to user
      if (feed_belongs_user($feedid,$session['userid']))
      {
        $start = floatval($_GET['start']);
        $end = floatval($_GET['end']);
        $resolution = intval($_GET['res']);
        $data = get_feed_data($feedid,$start,$end,$resolution);
        $output['content'] = json_encode($data);
      }
    }

    return $output;
  }
?>


