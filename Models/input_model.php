<?php
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
  function create_input($user,$name)
  {
    db_query("INSERT INTO input (userid,name) VALUES ('$user','$name')");
  }

  function create_input_timevalue($user,$name,$time,$value)
  {
    db_query("INSERT INTO input (userid,name,time,value,status) VALUES ('$user','$name','$time','$value','0')");
  }

  function set_input_timevalue($id, $time, $value)
  {
    $time = date("Y-n-j H:i:s", $time);    
    db_query("UPDATE input SET time='$time', value = '$value' WHERE id = '$id'");
  }

  function set_input_processlist($id,$processlist) {
      $result = db_query("UPDATE input SET processList = '$processlist' WHERE id='$id'");
  }

  function add_input_process($userid,$id,$type,$arg)
  {
    $list = get_input_processlist($userid,$id);
    if ($list) $list .=',';
    $list .= $type.':'.$arg;
    set_input_processlist($id,$list);
  }

  function reset_input_process($userid,$id)
  {
    set_input_processlist($id,"");
  }

  function get_user_inputs($userid)
  {
    $result = db_query("SELECT * FROM input WHERE userid = '$userid'");
    $inputs = array();
    if ($result) {
      while ($row = db_fetch_array($result)) {
        if ($row['status']!=1){ // 1 is a deleted input
        $inputs[] = array($row['id'],$row['name'],strtotime($row['time'])*1000,$row['value']);
        }
      }
    }
    return $inputs;
  }

  function get_input_id($user,$name)
  {
    $result = db_query("SELECT id FROM input WHERE name='$name' AND userid='$user' AND status='0' ");
    if ($result) { $array = db_fetch_array($result); return $array['id']; } 
    else return 0;
  }

  function get_input_name($id)
  {
    $result = db_query("SELECT name FROM input WHERE id='$id'");
    if ($result) { $array = db_fetch_array($result); return $array['name']; } 
    else return 0;
  }

  function get_input_processlist($userid,$id)
  {
    $result = db_query("SELECT processList FROM input WHERE userid='$userid' AND id='$id'");
    $array = db_fetch_array($result);
    return $array['processList'];
  }

  //-----------------------------------------------------------------------------------------------
  // Gets the inputs process list and converts id's into descriptive text
  //-----------------------------------------------------------------------------------------------
  function get_input_processlist_desc($userid,$id)
  {
    $process_list = get_input_processlist($userid,$id);			// Get the input's process list

    $list = array();
    if ($process_list){		
      $array = explode(",", $process_list);			// input process list is comma seperated
      foreach ($array as $row)    				// For all input processes
      {
        $row = explode(":", $row);    				// Divide into process id and arg
        $processid = $row[0]; $arg = $row[1];			// Named variables
        $process = get_process($processid);			// gets process details of id given

        $processDescription = $process[0];			// gets process description
        if ($process[1] == 1) $arg = get_input_name($arg);	// if input: get input name
        if ($process[1] == 2) $arg = get_feed_name($arg);	// if feed: get feed name
        if ($process[1] == 3) $arg = get_feed_name($arg);	// if feed: get feed name

        $list[]=array($processDescription,$arg);		// Populate list array
      }
    }
    return $list;
  }

  function delete_input($userid,$inputid)
  {
    // soft deletion
    db_query("UPDATE input SET status = 1 WHERE userid = '$userid' AND id='$inputid'");
  }

?>
