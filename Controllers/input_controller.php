<?php 
  /*
   All Emoncms code is released under the GNU Affero General Public License.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    INPUT CONTROLLER ACTIONS		ACCESS

    list				read
    delete?id=1				write

  */
function input_controller()
{
  require "Models/input_model.php";
  global $session, $action, $format;

  $output['content'] = "";
  $output['message'] = "";
  //---------------------------------------------------------------------------------------------------------
  // List inputs
  // http://yoursite/emoncms/input/list.html
  // http://yoursite/emoncms/input/list.json
  //---------------------------------------------------------------------------------------------------------
  if ($action == 'list' && $session['read'])
  {
    $inputs = get_user_inputs($session['userid']);

    if ($format == 'json') $output['content'] = json_encode($inputs);
    if ($format == 'html') $output['content'] = view("input/list_view.php", array('inputs' => $inputs));
  }

  //---------------------------------------------------------------------------------------------------------
  // Delete an input
  // http://yoursite/emoncms/input/delete?id=1
  //---------------------------------------------------------------------------------------------------------
  if ($action == "delete" && $session['write'])
  { 
    delete_input($session['userid'] ,intval($_GET["id"]));
    $output['message'] = "Input deleted";
  }

  return $output;
}

?>


