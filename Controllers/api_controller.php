<?php 
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

function api_controller()
{
  global $session,$action;
  require "Models/input_model.php";
  require "Models/feed_model.php";
  require "Models/process_model.php";
  
      
  // POST arduino posts up to emoncms 				
  if ($action == 'post' && $session['write'])
  {
    $node = intval($_GET['node']);
    $json = db_real_escape_string($_GET['json']);
    $csv = db_real_escape_string($_GET['csv']);
  }

  if ($csv)
  {
    $values = explode(',', $csv);
    $i = 0;
    foreach ($values as $value)
    {
      $i++; 
      if ($node) $key = $i; else $key = "csv".$i;
      $datapairs[] = $key.":".$value;
    }
  }	

  if ($json)
  {
    // preg_replace strips out everything appart from alphanumeric characters, whitespace and -.:,
    $json = preg_replace('/[^\w\s-.:,]/','',$json);
    $datapairs = explode(',', $json);
  }

  if ($json || $csv)
  {
    $time = time();						// get the time - data recived time
    if (isset($_GET["time"]))
    {
      $time = intval($_GET["time"]);	// - or use sent timestamp if present 
    }
    $inputs = register_inputs($session['userid'],$node,$datapairs,$time);          // register inputs
    process_inputs($session['userid'],$inputs,$time);                        // process inputs to feeds etc
    $output['message'] = "ok";
  }

  return $output;
}

  //-------------------------------------------------------------------------
  function register_inputs($userid,$nodeid,$datapairs,$time)
  {
  //--------------------------------------------------------------------------------------------------------------
  // 2) Register incoming inputs
  //--------------------------------------------------------------------------------------------------------------
  $inputs = array();
  foreach ($datapairs as $datapair)       
  {
    $datapair = explode(":", $datapair);
    $name = preg_replace('/[^\w\s-.]/','',$datapair[0]); 	// filter out all except for alphanumeric white space and dash
    $value = floatval($datapair[1]);		

    if ($nodeid) $name = "node".$nodeid."_".$name;

    $id = get_input_id($userid,$name);				// If input does not exist this return's a zero

    if ($id==0) {
      $id = create_input_timevalue($userid,$name,$nodeid,$time,$value);	// Create input if it does not exist

      // auto_configure_inputs($userid,$id,$name);

    } else {			
      $inputs[] = array($id,$time,$value);	
      set_input_timevalue($id,$time,$value);			// Set time and value if it does
    }
  }

  return $inputs;
  }

  function process_inputs($userid,$inputs,$time)
  {
  //--------------------------------------------------------------------------------------------------------------
  // 3) Process inputs according to input processlist
  //--------------------------------------------------------------------------------------------------------------
	  foreach ($inputs as $input)            
	  {
	    $id = $input[0];
	    $input_processlist =  get_input_processlist($userid,$id);
	    if ($input_processlist)
	    {
	      $processlist = explode(",",$input_processlist);				
	      $value = $input[2];
	      foreach ($processlist as $inputprocess)    			        
	      {
	        $inputprocess = explode(":", $inputprocess); 		// Divide into process id and arg
	        $processid = $inputprocess[0];				// Process id
	        $arg = $inputprocess[1];	 			// Can be value or feed id
	
	        $process_list = get_process_list();
	        $process_function = $process_list[$processid][2];	// get process function name
	        $value = $process_function($arg,$time,$value);		// execute process function
	      }
	    }
	  }
  }

?>