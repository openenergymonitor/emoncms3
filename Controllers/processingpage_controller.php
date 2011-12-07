<?php 
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
function processingpage_controller()
{

  if (!$_SESSION['valid']) return "Sorry, you must be logged in to see this page";

  require "Models/process_model.php";
  require "Models/input_model.php";
  require "Models/feed_model.php";

  $userid = $_SESSION['userid'];

  if ($_POST["form"] == "input")
  { 
    $inputid = intval($_POST["id"]);
    $input_processlist = get_input_processlist_desc($inputid);
  }

  if ($_POST["form"] == "process")
  { 
    $inputid = intval($_POST["id"]);
    $processid = intval($_POST["sel"]);			// get process type
    $arg = $_POST["arg"];

    $process = get_process($processid);
    if ($process[1] == 0) $arg = floatval($arg);
    if ($process[1] == 1) $arg = get_input_id($userid,$arg);
    if ($process[1] == 2)
    {
      $id = get_feed_id($userid,$arg);
      if ($id==0)  $id = create_feed($userid,$arg);
      $arg = $id;
    }
    add_input_process($inputid,$processid,$arg);
    $input_processlist = get_input_processlist_desc($inputid);
  }

  $inputs = get_user_inputs($userid);

  $process_list = get_process_list();
  // Render view
  $content = view("processingpage_view.php",array('inputsel' => $inputid, 'input_processlist' => $input_processlist, 'process_list'=>$process_list));

  return $content;
}

?>


