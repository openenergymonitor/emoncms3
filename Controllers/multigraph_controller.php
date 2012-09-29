<?php
  /*
   All Emoncms code is released under the GNU General Public License v3.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org
  */

  // no direct access
  defined('EMONCMS_EXEC') or die('Restricted access');

  function multigraph_controller()
  {
    require "Models/multigraph_model.php";
    global $action, $format, $session;

    $output['content'] = "";
    $output['message'] = "";

    if ($action == "save" && $session['write'])
    {
      $output['message'] = "saving";
      $json = preg_replace('/[^\w\s-.?@%&:[]{},]/','',$_POST['data']);

      set_multigraph($session['userid'], $json);
    }

	elseif ($action == "get" && $session['read'])
    {
      $output['content'] =  get_multigraph($session['userid']);
    }

    return $output;
  }

?>