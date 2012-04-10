<?php 
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    PROCESS CONTROLLER ACTIONS		ACCESS

    list?inputid=1			read
    add?inputid=1&type=1&arg=power	write

  */
  function process_controller()
  {
    require "Models/process_model.php";
    require "Models/input_model.php";
    require "Models/feed_model.php";

    global $session, $action, $format;

    $output['content'] = "";
    $output['message'] = "";
    //---------------------------------------------------------------------------------------------------------
    // Get process list of input
    // http://yoursite/emoncms/process/list.html?inputid=1
    // http://yoursite/emoncms/process/list.json?inputid=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == "list" && $session['read'])
    { 
      $inputid = intval($_GET["inputid"]);
      $input_processlist = get_input_processlist_desc($session['userid'],$inputid);

      if ($format == 'json') $output['content'] = json_encode($input_processlist);
      if ($format == 'html') $output['content'] = view("process/list_view.php", array('inputid'=>$inputid, 'input_processlist' => $input_processlist, 'process_list'=>get_process_list()));
    }

    //---------------------------------------------------------------------------------------------------------
    // Add process
    // http://yoursite/emoncms/process/add?inputid=1&type=1&arg=power
    //---------------------------------------------------------------------------------------------------------
    if ($action == "add" && $session['write']) // write access required
    { 
      $inputid = intval($_GET["inputid"]);
      $processid = intval($_GET["type"]);			// get process type

      $arg = preg_replace('/[^\w\s-.]/','',$_GET["arg"]);	// filter out all except for alphanumeric white space and dash
      $arg = db_real_escape_string($arg);

      $process = get_process($processid);
      if ($process[1] == 0) $arg = floatval($arg);
      if ($process[1] == 1) $arg = get_input_id($session['userid'],$arg);
      if ($process[1] == 2)
      {
        $id = get_feed_id($_SESSION['userid'],$arg);
        if ($id==0)  $id = create_feed($_SESSION['userid'],$arg,$processid);
        $arg = $id;
      }
      if ($process[1] == 3) $arg = get_feed_id($session['userid'],$arg);
      add_input_process($session['userid'],$inputid,$processid,$arg);

      if ($format == 'html') header("Location: list?inputid=".$inputid);
    }

    if ($action == "test" && $_SESSION['write']) // write access required
    {
		set_time_limit(360);  // Increase PHP limit
		// Create Histogram data - (to feed, from feed, from date, to date).
    	///$rows = histogram_history(4,1,"2008-01-01","2012-05-01");
    }

    return $output;
  }
?>


