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

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

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
      elseif ($format == 'html') $output['content'] = view("process/list_view.php", array('inputid'=>$inputid, 'input_processlist' => $input_processlist, 'process_list'=>get_process_list()));
    }

    //---------------------------------------------------------------------------------------------------------
    // Add process
    // http://yoursite/emoncms/process/add?inputid=1&type=1&arg=power
    //---------------------------------------------------------------------------------------------------------
    elseif ($action == "add" && $session['write']) // write access required
    { 
      $inputid = intval($_GET['inputid']);
      $processid = intval($_GET['type']);			// get process type (ProcessArg::)
      $arg = floatval($_GET['arg']);                              // This is actual value, inputid or feedid

      //arg2 is feed name if arg=-1 (create new feed)
      $arg2 = preg_replace('/[^\w\s-.]/','',$_GET['arg2']);	// filter out all except for alphanumeric white space and dash
      $arg2 = db_real_escape_string($arg2);

      $process = get_process($processid);

      $valid = false; // Flag to determine if argument is valid

      switch ($process[1]) {
      case ProcessArg::VALUE:  // If arg type value
        $arg = floatval($arg);
        $id = $arg;
        if ($arg != '') {
          $valid = true;
        } 
        else {
          $output['message'] = 'ERROR: Argument must be a valid number greater or less than 0.';
        }
        break;
      case ProcessArg::INPUTID:  // If arg type input
        // Check if input exists (returns 0 if invalid)
        $name = get_input_name($arg);
        $id = get_input_id($_SESSION['userid'],$name);
        if (($name == '') || ($id == '')) {
          $output['message'] = 'ERROR: Input does not exist!';
        }
        else {
          $valid = true;
        }
        break;
      case ProcessArg::FEEDID:   // If arg type feed
        // First check if feed exists of given feed id and user.
        $name = get_feed_name($arg);
        $id = get_feed_id($_SESSION['userid'],$name);
        if (($name == '') || ($id == '')) {
          // If it doesnt then create a feed, $process[3] is the number of datafields in the feed table
          $id = create_feed($_SESSION['userid'],$arg2, $process[3], $process[4]);
          // Check feed was created successfully
          $name = get_feed_name($id);
          if ($name == '') {
            $output['message'] = 'ERROR: Could not create new feed!';
          }
          else {
            $valid = true;
          }
        }
        else {
          $valid = true;
        }
        break;
      }

      if ($valid) {
        add_input_process($session['userid'],$inputid,$processid,$id);
      }

      if ($format == 'json') {
        $output['message'] = json_encode($output['message']);
      }
      elseif ($format == 'html') header('Location: list?inputid='.$inputid);
    }
    
    //--------------------------------------------------------------------------
    // Delete process 
    // http://yoursite/emoncms/process/delete?inputid=1&processid=1
    //--------------------------------------------------------------------------
    elseif ($action == 'delete' && $session['write']) // write access required
    { 
      $inputid = intval($_GET['inputid']);
      $processid = intval($_GET['processid']);

      delete_input_process($session['userid'],$inputid,$processid);

      if ($format == 'html') header('Location: list?inputid='.$inputid);
    }

    //--------------------------------------------------------------------------
    // Move process 
    // http://yoursite/emoncms/process/move?inputid=1&processid=1&moveby=1/-1
    //--------------------------------------------------------------------------
    elseif ($action == 'move' && $session['write']) // write access required
     { 
      $inputid = intval($_GET['inputid']);
      $processid = intval($_GET['processid']);
      $moveby = intval($_GET['moveby']);

      move_input_process($session['userid'],$inputid,$processid,$moveby);

      if ($format == 'html') header('Location: list?inputid='.$inputid);
    }

    //--------------------------------------------------------------------------
    // Query process
    // http://yoursite/emoncms/process/query?type=1
    // Returns ProcessArg type as int; String description; Array of feedids and names
    //  eg. [2,"Feed",[["1","power"],["3","power-histogram"],["2","power-kwhd"]]]
    //--------------------------------------------------------------------------
    elseif ($action == 'query' && $session['read']) // read access required
    { 
      $inputid = intval($_GET["inputid"]);
      $processid = intval($_GET["type"]);			// get process type

      $arg = preg_replace('/[^\w\s-.]/','',$_GET["arg"]);	// filter out all except for alphanumeric white space and dash
      $arg = db_real_escape_string($arg);

      $process = get_process($processid);

      $newprocess[0] = $process[1]; // Process arg type
      switch($process[1]) {
      case ProcessArg::VALUE:
        $newprocess[1] = "Value";
        break;
      case ProcessArg::INPUTID:
        $newprocess[1] = "Input";
        $newprocess[2] = get_user_input_names($session['userid']);
        break;
      case ProcessArg::FEEDID:
        $newprocess[1] = "Feed";
        $newprocess[2] = get_user_feed_names($session['userid']);
        break;
      default:
        $newprocess[1] = "ERROR";
      }


      if ($format == 'json') $output['content'] = json_encode($newprocess);
      //if ($format == 'html') $output['content'] = $argboxhtml;
    }

    elseif ($action == "test" && $_SESSION['write']) // write access required
    {
      set_time_limit(360);  // Increase PHP limit
      // Create Histogram data - (to feed, from feed, from date, to date).
      // $rows = histogram_history(4,1,"2008-01-01","2012-05-01");
    }

    elseif ($action == "autoconfigure" && $session['write'])
    { 
      $inputs = get_user_inputs($session['userid']);
      foreach ($inputs as $input)
      {
        auto_configure_inputs($session['userid'],$input[0],$input[1]);
      }

      if ($format == 'html') header("Location: ../feed/list");
    }

    return $output;
  }
?>