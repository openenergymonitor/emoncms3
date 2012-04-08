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

    global $action, $format;

error_log("process_controller:".$action." R:".$_SESSION['read']." W:".$_SESSION['write']);    	
    //---------------------------------------------------------------------------------------------------------
    // Get process list of input
    // http://yoursite/emoncms/process/list.html?inputid=1
    // http://yoursite/emoncms/process/list.json?inputid=1
    //---------------------------------------------------------------------------------------------------------
    if ($action == "list" && $_SESSION['read'])
    { 
      $inputid = intval($_GET["inputid"]);
      $input_processlist = get_input_processlist_desc($_SESSION['userid'],$inputid);

      if ($format == 'json') $output = json_encode($input_processlist);
      if ($format == 'html') $output = view("process/list_view.php", array('inputid'=>$inputid, 'input_processlist' => $input_processlist, 'process_list'=>get_process_list()));
    }

    //---------------------------------------------------------------------------------------------------------
    // Add process
    // http://yoursite/emoncms/process/add?inputid=1&type=1&arg=power
    //---------------------------------------------------------------------------------------------------------
    if ($action == "add" && $_SESSION['write']) // write access required
    { 
      $inputid = intval($_GET["inputid"]);
      $processid = intval($_GET["type"]);			// get process type

      $arg = preg_replace('/[^\w\s-.]/','',$_GET["arg"]);	// filter out all except for alphanumeric white space and dash
      $arg = db_real_escape_string($arg);

      $process = get_process($processid);
      if ($process[1] == 0) $arg = floatval($arg);
      if ($process[1] == 1) $arg = get_input_id($_SESSION['userid'],$arg);
      if ($process[1] == 2)
      {
        $id = get_feed_id($_SESSION['userid'],$arg);
        if ($id==0)  $id = create_feed($_SESSION['userid'],$arg,$processid);
        $arg = $id;
      }
      add_input_process($_SESSION['userid'],$inputid,$processid,$arg);

      if ($format == 'html') header("Location: list?inputid=".$inputid);
    }

    if ($action == "test" && $_SESSION['write']) // write access required
    {
		set_time_limit(360);  // Increase PHP limit
    	///$rows = histogram_history(4,1,"2011-08-01","2011-10-01");
    	///$rows = histogram_history(4,1,"2008-01-01","2012-05-01");
    	$rows = histogram_history(4,1,"2011-09-15","2012-05-01");
    	error_log("histogram_history:".$rows);
    }

    return $output;
  }
?>


