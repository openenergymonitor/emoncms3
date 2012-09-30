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
      $user = get_user($session['userid']);
      $output['content'] = view("api_view.php", array('user' => $user));
    }


    // vis/realtime?feedid=1
    if ($action == "realtime" && $session['read'])
    {
      $feedid = intval($_GET['feedid']);
      $output['content'] = view("vis/realtime.php", array('feedid'=>$feedid,'feedname'=>get_feed_name($feedid)));
    }

    // vis/rawdata?feedid=1
    if ($action == "rawdata" && $session['read'])
    {
      $feedid = intval($_GET['feedid']);
      $output['content'] = view("vis/rawdata.php", array('feedid'=>$feedid,'feedname'=>get_feed_name($feedid)));
    }

    // vis/bargraph?feedid=2
    if ($action == "bargraph" && $session['read'])
    {
      $feedid = intval($_GET['feedid']);
      $output['content'] = view("vis/bargraph.php", array('feedid'=>$feedidtrystan,'feedname'=>get_feed_name($feedid)));
    }

    if ($action == 'smoothie' && $session['read'])
    {
      $output['content'] = view("vis/smoothie/smoothie.php", array());
    }

    // vis/histgraph?feedid=3
    if ($action == "histgraph" && $session['read'])
    {
      $feedid = intval($_GET['feedid']);
      $output['content'] = view("vis/histgraph.php", array('feedid'=>$feedid,'feedname'=>get_feed_name($feedid)));
    }

    // vis/dailyhistogram?power=  &kwhd=  &whw= 
    if ($action == 'dailyhistogram' && $session['read'])
    {
      $output['content'] = view("vis/dailyhistogram/dailyhistogram.php", array());
    }

    if ($action == 'zoom' && $session['read'])
    {
      $output['content'] = view("vis/zoom/zoom.php", array());
    }
    
    if ($action == 'comparison' && $session['read'])
    {
      $output['content'] = view("vis/comparison/comparison.php", array());
    }

    if ($action == 'stacked' && $session['read'])
    {
      $output['content'] = view("vis/stacked.php", array());
    }

    if ($action == 'threshold' && $session['read'])
    {
      $output['content'] = view("vis/threshold.php", array());
    }

    if ($action == 'simplezoom' && $session['read'])
    {
      $output['content'] = view("vis/simplezoom.php", array());
    }

    if ($action == "orderbars" && $session['read'])
    {
      $feedid = intval($_GET['feedid']);
      $output['content'] = view("vis/orderbars.php", array('feedid'=>$feedid,'feedname'=>get_feed_name($feedid)));
    }

    if ($action == 'orderthreshold' && $session['read'])
    {
      $output['content'] = view("vis/orderthreshold.php", array());
    }

	elseif ($action == 'multigraph' && $session['read'])
    {
      if ($session['write'])
      {
      	$write_apikey = get_apikey_write($session['userid']);
      }
      $output['content'] = view("vis/multigraph.php", array('write_apikey'=>$write_apikey));
    }

    // vis/rawdata?feedid=1
    if ($action == "edit" && $session['write'])
    {
      $feedid = intval($_GET['feedid']);
      $output['content'] = view("vis/edit.php", array('feedid'=>$feedid,'feedname'=>get_feed_name($feedid), 'type'=>get_feed_datatype($feedid)));
    }
 
    return $output;
  }

?>