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

function create_input($user, $name)
{
  db_query("INSERT INTO input (userid,name) VALUES ('$user','$name')");
}

function create_input_timevalue($user, $name, $nodeid, $time, $value)
{
  $time = date("Y-n-j H:i:s", $time);
  db_query("INSERT INTO input (userid,name,nodeid,time,value) VALUES ('$user','$name','$nodeid','$time','$value')");
  $inputid = db_insert_id();
  return $inputid;
}

function set_input_timevalue($id, $time, $value)
{
  $time = date("Y-n-j H:i:s", $time);
  db_query("UPDATE input SET time='$time', value = '$value' WHERE id = '$id'");
}

function set_input_processlist($id, $processlist)
{
  $result = db_query("UPDATE input SET processList = '$processlist' WHERE id='$id'");
}

function add_input_process($userid, $id, $type, $arg)
{
  $list = get_input_processlist($userid, $id);
  if ($list)
    $list .= ',';
  $list .= $type . ':' . $arg;
  set_input_processlist($id, $list);
}

/******
* delete input process by index
******/
function delete_input_process($userid, $id, $index)
{
  $process_list = get_input_processlist($userid, $id);
  $array = explode(",", $process_list);
  $index = $index - 1; // Array is 0-based. Index from process page is 1-based.
  
  $list = array(); // process list array to hold new list
  // input process list is comma seperated

  for($i = 0; $i < count($array); $i++) { // For all input processes
    if ($i != $index) {
      // Append each process to list except for the one we are deleting
      $list[end($list)+1] = $array[$i];
    }
  }
  // Save new process list
  set_input_processlist($id, implode(",", $list));
}

/******
* move_input_process - move process up/down list of processes by $moveby (eg. -1, +1)
******/
function move_input_process($userid, $id, $index, $moveby)
{
  if (($moveby > 1) || ($moveby < -1)) return false;  // Only support +/-1 (logic is easier)

  $process_list = get_input_processlist($userid, $id);
  $array = explode(",", $process_list);
  $index = $index - 1; // Array is 0-based. Index from process page is 1-based.
  
  $newindex = $index + $moveby; // Calc new index in array
  // Check if $newindex is greater than size of list
  if ($newindex > (count($array)-1)) $newindex = (count($array)-1);
  // Check if $newindex is less than 0
  if ($newindex < 0) $newindex = 0;
  
  $replace = $array[$newindex]; // Save entry that will be replaced
  $array[$newindex] = $array[$index];
  $array[$index] = $replace;

  // Save new process list
  set_input_processlist($id, implode(",", $array));
  return true;
}

function reset_input_process($userid, $id)
{
  set_input_processlist($id, "");
}

  //-----------------------------------------------------------------------------------------------
  // This function gets a users input list, its used to create the input/list page
  //-----------------------------------------------------------------------------------------------
function get_user_inputs($userid)
{
  $result = db_query("SELECT * FROM input WHERE userid = '$userid'");
  $inputs = array();
  if ($result)
  {
    while ($row = db_fetch_array($result))
    {
      $inputs[] = array(
        $row['id'],
        $row['name'],
        strtotime($row['time']) * 1000,
        $row['value'], 'nodeid'=>$row['nodeid']
      );
    }
  }
  return $inputs;
}

function get_user_inputsbynode($userid)
{
  $result = db_query("SELECT * FROM input WHERE userid = '$userid' ORDER BY nodeid");
  $inputs = array();
  if ($result)
  {
    while ($row = db_fetch_array($result))
    {
      $inputs[] = array(
        $row['id'],
        $row['name'],
        strtotime($row['time']) * 1000,
        $row['value'], 'nodeid'=>$row['nodeid']
      );
    }
  }
  return $inputs;
}

//-----------------------------------------------------------------------------------------------
// Return a list of users input ids and names
//-----------------------------------------------------------------------------------------------
function get_user_input_names($userid)
{
  $result = db_query("SELECT id,name FROM input WHERE userid = $userid ORDER BY name ASC");
    $inputs = array();
    if ($result) {
      while ($row = db_fetch_array($result)) {
        $inputs[] = array($row['id'],$row['name']);
      }
    }
    return $inputs;
}

function get_input_id($user, $name)
{
  $result = db_query("SELECT id FROM input WHERE name='$name' AND userid='$user'");
  if ($result)
  {
    $array = db_fetch_array($result);
    return $array['id'];
  }
  else
    return 0;
}

function get_input_name($id)
{
  $result = db_query("SELECT name FROM input WHERE id='$id'");
  if ($result)
  {
    $array = db_fetch_array($result);
    return $array['name'];
  }
  else
    return 0;
}

function get_input_processlist($userid, $id)
{
  $result = db_query("SELECT processList FROM input WHERE userid='$userid' AND id='$id'");
  $array = db_fetch_array($result);
  return $array['processList'];
}

//-----------------------------------------------------------------------------------------------
// Gets the inputs process list and converts id's into descriptive text
//-----------------------------------------------------------------------------------------------
function get_input_processlist_desc($userid, $id)
{
  $process_list = get_input_processlist($userid, $id);
  // Get the input's process list

  $list = array();
  if ($process_list)
  {
    $array = explode(",", $process_list);
    // input process list is comma seperated
    foreach ($array as $row)// For all input processes
    {
      $row = explode(":", $row);
      // Divide into process id and arg
      $processid = $row[0];
      $arg = $row[1];
      // Named variables
      $process = get_process($processid);
      // gets process details of id given

      $processDescription = $process[0];
      // gets process description
      if ($process[1] == ProcessArg::INPUTID)
        $arg = get_input_name($arg);
      // if input: get input name
      elseif ($process[1] == ProcessArg::FEEDID)
        $arg = get_feed_name($arg);
      // if feed: get feed name

      $list[] = array(
        $processDescription,
        $arg
      );
      // Populate list array
    }
  }
  return $list;
}

function delete_input($userid, $inputid)
{
  // Inputs are deleted permanentely straight away rather than a soft delete
  // as in feeds - as no actual feed data will be lost
  db_query("DELETE FROM input WHERE userid = '$userid' AND id = '$inputid'");
}
?>
