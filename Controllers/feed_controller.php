<?php 
  /*
    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    FEED CONTROLLER ACTIONS		ACCESS

    type?id=1&type=0			write
    tag?id=1&tag=tag			write
    rename?id=1&name=newname		write
    delete?id=1				write 
    permanentlydelete			write
    restore?id=1			write
    list				read
    view?id=1				read
    value?id=1				read
    data?id=1&start=000&end=000&res=1	read
    histogram				read
    kwhatpower				read
    edit?id=1&time=...&newvalue=...	write
    
  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

  function feed_controller()
  {
    require "Models/feed_model.php";
    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";

    //---------------------------------------------------------------------------------------------------------
    // Set feed datatype: 0: undefined type, 1: real-time data, 2: daily data, 3: histogram data
    // http://yoursite/emoncms/feed/type?id=1&type=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == "type" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      $type = intval($_GET["type"]);

      if (feed_belongs_user($feedid, $session['userid']))
      {
        set_feed_datatype($feedid,$type);
        $output['message'] = _("Feed type changed");
      }
      else
	  {
	  	$output['message'] = _("Feed does not exist");
	  }

      if ($format == 'html')
	  {
	  	header("Location: view?id=$feedid");	// Return to feed list page
	  }
    }

    //---------------------------------------------------------------------------------------------------------
    // Set feed tag
    // http://yoursite/emoncms/feed/tag?id=1&tag=tag
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == "tag" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $session['userid']))
      {
        $newfeedtag = preg_replace('/[^\w\s-.]/','',$_GET["tag"]);	// filter out all except for alphanumeric white space and dash and full stop
        $newfeedtag = db_real_escape_string($newfeedtag);

        set_feed_tag($feedid,$newfeedtag);
        $output['message'] = _("Feed tag changed");
      }
      else
      {
      	$output['message'] = _("Feed does not exist");
      }

      if ($format == 'html')
	  {
	  	header("Location: list");	// Return to feed list page
	  }
    }

    //---------------------------------------------------------------------------------------------------------
    // Rename a feed
    // http://yoursite/emoncms/feed/rename?id=1&name=newname
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == "rename" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $session['userid']))
      {
        $newfeedname = preg_replace('/[^\w\s-.]/','',$_GET["name"]);	// filter out all except for alphanumeric white space and dash
        $newfeedname = db_real_escape_string($newfeedname);

        set_feed_name($feedid,$newfeedname);
        $output['message'] = _("Feed renamed");
      }
      else
      {
      	$output['message'] = _("Feed does not exist");
	  }

      if ($format == 'html')
	  {
	  	header("Location: list");	// Return to feed list page
	  }
    }

    //---------------------------------------------------------------------------------------------------------
    // Delete a feed ( move to recycle bin, so not permanent )
    // http://yoursite/emoncms/feed/delete?id=1
    //--------------------------------------------------------------------------------------------------------- 
	elseif ($action == "delete" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $session['userid']))
      {
        delete_feed($userid,$feedid);
        $output['message'] = _("Feed ").get_feed_name($feedid)._(" deleted");
      } else $output['message'] = _("Feed does not exist");
    }

    //---------------------------------------------------------------------------------------------------------
    // Permanent delete equivalent to empty recycle bin
    // http://yoursite/emoncms/feed/permanentlydelete
    //--------------------------------------------------------------------------------------------------------- 
    if ($action == "permanentlydelete" && $session['write'])
    { 
      permanently_delete_feeds($session['userid']);
      $output['message'] = "Deleted feeds are now permanently deleted";
    }

    //---------------------------------------------------------------------------------------------------------
    // Restore feed ( if in recycle bin )
    // http://yoursite/emoncms/feed/restore?id=1
    //--------------------------------------------------------------------------------------------------------- 
    if ($action == "restore" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $session['userid'])) {
        restore_feed($userid,$feedid);
      } 
      $output['message'] = "feed restored"; 
      if ($format == 'html') header("Location: list");	// Return to feed list page
    }

    //---------------------------------------------------------------------------------------------------------
    // Feed List
    // http://yoursite/emoncms/feed/list.html
    // http://yoursite/emoncms/feed/list.json
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'list' && $session['read'])
    {
      $del = intval($_GET["del"]);
      $feeds = get_user_feeds($session['userid'],$del);
    
      if ($format == 'json') $output['content'] = json_encode($feeds);
      if ($format == 'html') $output['content'] = view("feed/list_view.php", array('feeds' => $feeds,'del'=>$del));
    }

    //---------------------------------------------------------------------------------------------------------
    // Feed View
    // http://yoursite/emoncms/feed/view.html?id=1
    // http://yoursite/emoncms/feed/view.json?id=1
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'view' && $session['read'])
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
        if ($_GET["callback"])
        {
        	$output['content'] = $_GET["callback"]."(".json_encode($feed).");";
		}
      }

      if ($format == 'html') $output['content'] = view("feed/feed_view.php", array('feed' => $feed));
    }

    //---------------------------------------------------------------------------------------------------------
    // current feed value
    // http://yoursite/emoncms/feed/value?id=1
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'value' && $session['read'])
    {
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid,$session['userid']))
      {
      	$output['content'] = get_feed_value($feedid);
	  }
    }

    //---------------------------------------------------------------------------------------------------------
    // get feed data
    // start: start time, end: end time, dp: number of datapoints in time range to fetch
    // http://yoursite/emoncms/feed/data?id=1&start=000&end=000&dp=1
    //---------------------------------------------------------------------------------------------------------
	elseif ($action == 'data' && $session['read'])
    {
      $feedid = intval($_GET['id']);
      
      // Check if feed belongs to user
      if (feed_belongs_user($feedid,$session['userid']))
      {
        $start = floatval($_GET['start']);
        $end = floatval($_GET['end']);
        $dp = intval($_GET['dp']);
        $data = get_feed_data($feedid,$start,$end,$dp);
        $output['content'] = json_encode($data);
      } else { $output['message'] = "Permission denied"; }
    }

    //---------------------------------------------------------------------------------------------------------
    // get histogram data: energy used at different powers in the time range given
    // http://yoursite/emoncms/feed/histogram?id=1&start=000&end=000
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'histogram' && $session['read'])
    {
      $feedid = intval($_GET['id']);
      
      // Check if feed belongs to user
      if (feed_belongs_user($feedid,$session['userid']))
      {
        $start = floatval($_GET['start']);
        $end = floatval($_GET['end']);
        $data = get_histogram_data($feedid,$start,$end);
        $output['content'] = json_encode($data);
      } else { $output['message'] = "Permission denied"; }
    }

    //---------------------------------------------------------------------------------------------------------
    // get kwh per day at given power range
    // http://yoursite/emoncms/feed/kwhatpower?id=3&min=1000&max=10000
    //---------------------------------------------------------------------------------------------------------
    if ($action == 'kwhatpower' && $session['read'])
    {
      $feedid = intval($_GET['id']);
      // Check if feed belongs to user
      if (feed_belongs_user($feedid,$session['userid']))
      {
        $min = floatval($_GET['min']);
        $max = floatval($_GET['max']);
			
        $data = get_kwhd_atpower($feedid,$min,$max);
        $output['content'] = json_encode($data);

      } else { $output['message'] = "This is not your feed..."; }
    }

    //---------------------------------------------------------------------------------------------------------
    // Set a datapoint at a given time.
    // http://yoursite/emoncms/feed/edit?id=1&time=...&newvalue=... 
    //---------------------------------------------------------------------------------------------------------
    if ($action == "edit" && $session['write'])
    { 
      $feedid = intval($_GET["id"]);
      if (feed_belongs_user($feedid, $session['userid'])) {
        $time = intval($_GET["time"]);
        $value = floatval($_GET["newvalue"]);
        update_feed_data($feedid,time(),$time,$value);
        $output['message'] = "Edit";
      }
    }

    return $output;
  }
?>


