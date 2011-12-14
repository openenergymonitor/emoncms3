<?php 
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */
function feed_controller()
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

  if ($_POST["form"] == "tag")
  { 
    $feedid = intval($_POST["feedid"]);
    if (feed_belongs_user($feedid,$userid)) {
      $newfeedtag = $_POST["tag"];
      set_feed_tag($feedid,$newfeedtag);
    }
  }

  if ($_POST["form"] == "rename")
  { 
    $feedid = intval($_POST["feedid"]);
    if (feed_belongs_user($feedid,$userid)) {
      $newfeedname = $_POST["feedname"];
      set_feed_name($feedid,$newfeedname);
    }
  }

  if ($_POST["form"] == "delete")
  { 
    $feedid = intval($_POST["feedid"]);
    if (feed_belongs_user($feedid,$userid)) {
      delete_feed($userid,$feedid);
    }
  }

  $inputs = get_user_inputs($userid);
  $feeds = get_user_feeds($userid);

  $process_list = get_process_list();
  // Render view
  $content = view("feed_view.php",array('apikey_read' => $apikey_read,'apikey_write' => $apikey_write, 'inputs' => $inputs, 'inputsel' => $inputid, 'feeds' => $feeds, 'input_processlist' => $input_processlist, 'process_list'=>$process_list));

  return $content;
}

?>


